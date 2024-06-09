<?php
session_start();
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

// Asegúrate de que el usuario haya iniciado sesión
if (!isset($_SESSION['documento'])) {
    die("Usuario no autenticado.");
}

$user_document = $_SESSION['documento'];

// Manejo de la lógica de cancelación de citas (este bloque se puede eliminar si ya no se necesita cancelar citas)
if (isset($_POST['cancelar'])) {
    $id_cita = $_POST['id_cita'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    $fechaHoraCita = new DateTime("$fecha $hora");
    $fechaHoraActual = new DateTime();
    $intervalo = $fechaHoraActual->diff($fechaHoraCita);

    if ($intervalo->invert == 0 && $intervalo->days >= 1) {
        $actualizarEstado = $con->prepare("UPDATE citas SET id_estado = 6 WHERE id_cita = :id_cita");
        $actualizarEstado->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
        $actualizarEstado->execute();

        echo '<script>alert("Cita cancelada con éxito.");</script>';
        echo '<script>window.location="citasagendadas.php"</script>';
    } else {
        echo '<script>alert("Las citas solo pueden ser canceladas con al menos 24 horas de antelación.");</script>';
        echo '<script>window.location="citasagendadas.php"</script>';
    }
}

// Modificar la consulta para seleccionar todas las citas independientemente del estado
$sentencia_select = $con->prepare("
    SELECT citas.id_cita, usuarios.nombre, usuarios.apellido, citas.fecha, citas.hora, medicos.nombre_comple, 
    especializacion.especializacion, estados.estado
    FROM citas 
    JOIN medicos ON citas.docu_medico = medicos.docu_medico 
    JOIN especializacion ON citas.id_esp = especializacion.id_esp 
    JOIN estados ON citas.id_estado = estados.id_estado
    JOIN usuarios ON citas.documento = usuarios.documento
    WHERE citas.documento = :user_document
    ORDER BY citas.fecha ASC
");
$sentencia_select->bindParam(':user_document', $user_document, PDO::PARAM_STR);
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <h2>CITAS AGENDADAS</h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <?php if (isset($_GET['btn_buscar'])): ?>
                    <form action="citasagendadas.php" method="get">
                        <input type="submit" value="Regresar" class="btn btn-secondary"/>
                    </form>
                <?php else: ?>
                    <form action="../citas.php">
                        <input type="submit" value="Regresar" class="btn btn-secondary"/>
                    </form>
                <?php endif; ?>
            </div>
            <div class="barra_buscador">
                <form action="citasagendadas.php" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar Cita" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>Nombre</td>
                    <td>Apellido</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                    <td>Médico</td>
                    <td>Especialización</td>
                    <td>Estado</td>
                </tr>
                <?php
                if (isset($_GET['btn_buscar'])) {
                    foreach ($resultado as $fila) {
                        if (strpos($fila['fecha'], $_GET['buscar']) !== false) {
                            echo "<tr>
                                    <td>{$fila['nombre']}</td>
                                    <td>{$fila['apellido']}</td>
                                    <td>{$fila['fecha']}</td>
                                    <td>{$fila['hora']}</td>
                                    <td>{$fila['nombre_comple']}</td>
                                    <td>{$fila['especializacion']}</td>
                                    <td>{$fila['estado']}</td>
                                  </tr>";
                        }
                    }
                } else {
                    foreach ($resultado as $fila) {
                        echo "<tr>
                                <td>{$fila['nombre']}</td>
                                <td>{$fila['apellido']}</td>
                                <td>{$fila['fecha']}</td>
                                <td>{$fila['hora']}</td>
                                <td>{$fila['nombre_comple']}</td>
                                <td>{$fila['especializacion']}</td>
                                <td>{$fila['estado']}</td>
                              </tr>";
                    }
                }
                ?>
            </table>
            </div>
            <div class="espacio">

<div >
        <form action="generar_pdf.php" method="post">
            <button type="submit" class="btn-pdf">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 384 512"><path fill="#ffffff" d="M181.9 256.1c-5-16-4.9-46.9-2-46.9c8.4 0 7.6 36.9 2 46.9m-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7c18.3-7 39-17.2 62.9-21.9c-12.7-9.6-24.9-23.4-34.5-40.8M86.1 428.1c0 .8 13.2-5.4 34.9-40.2c-6.7 6.3-29.1 24.5-34.9 40.2M248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24m-8 171.8c-20-12.2-33.3-29-42.7-53.8c4.5-18.5 11.6-46.6 6.2-64.2c-4.7-29.4-42.4-26.5-47.8-6.8c-5 18.3-.4 44.1 8.1 77c-11.6 27.6-28.7 64.6-40.8 85.8c-.1 0-.1.1-.2.1c-27.1 13.9-73.6 44.5-54.5 68c5.6 6.9 16 10 21.5 10c17.9 0 35.7-18 61.1-61.8c25.8-8.5 54.1-19.1 79-23.2c21.7 11.8 47.1 19.5 64 19.5c29.2 0 31.2-32 19.7-43.4c-13.9-13.6-54.3-9.7-73.6-7.2M377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9m-74.1 255.3c4.1-2.7-2.5-11.9-42.8-9c37.1 15.8 42.8 9 42.8 9"/></svg>
            PDF</button>
        </form>
        </div>

        <div >
        <form action="generar_reporte_excel.php" method="post">
    <button type="submit" class="btn-excel">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 384 512">
            <path fill="#ffffff" d="M181.9 256.1c-5-16-4.9-46.9-2-46.9c8.4 0 7.6 36.9 2 46.9m-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7c18.3-7 39-17.2 62.9-21.9c-12.7-9.6-24.9-23.4-34.5-40.8M86.1 428.1c0 .8 13.2-5.4 34.9-40.2c-6.7 6.3-29.1 24.5-34.9 40.2M248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24m-8 171.8c-20-12.2-33.3-29-42.7-53.8c4.5-18.5 11.6-46.6 6.2-64.2c-4.7-29.4-42.4-26.5-47.8-6.8c-5 18.3-.4 44.1 8.1 77c-11.6 27.6-28.7 64.6-40.8 85.8c-.1 0-.1.1-.2.1c-27.1 13.9-73.6 44.5-54.5 68c5.6 6.9 16 10 21.5 10c17.9 0 35.7-18 61.1-61.8c25.8-8.5 54.1-19.1 79-23.2c21.7 11.8 47.1 19.5 64 19.5c29.2 0 31.2-32 19.7-43.4c-13.9-13.6-54.3-9.7-73.6-7.2M377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9m-74.1 255.3c4.1-2.7-2.5-11.9-42.8-9c37.1 15.8 42.8 9 42.8 9"/></svg>
        Excel
    </button>
</form>
            </div>

</div>

    