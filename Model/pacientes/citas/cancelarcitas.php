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

// Manejo de la lógica de cancelación de citas
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
        echo '<script>window.location="cancelarcitas.php"</script>';
    } else {
        echo '<script>alert("Las citas solo pueden ser canceladas con al menos 24 horas de antelación.");</script>';
        echo '<script>window.location="cancelarcitas.php"</script>';
    }
}

$sentencia_select = $con->prepare("
    SELECT citas.id_cita, citas.documento, citas.fecha, citas.hora, medicos.nombre_comple, 
    especializacion.especializacion, estados.estado
    FROM citas 
    JOIN medicos ON citas.docu_medico = medicos.docu_medico 
    JOIN especializacion ON citas.id_esp = especializacion.id_esp 
    JOIN estados ON citas.id_estado = estados.id_estado
    WHERE citas.documento = :user_document AND citas.id_estado = 1
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
    <link rel="stylesheet" href="../css/estilo.css">
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
                    <td>Documento</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                    <td>Medico</td>
                    <td>Especializacion</td>
                    <td>Estado</td>
                    <td>Acciones</td>
                </tr>
                <?php
                if (isset($_GET['btn_buscar'])) {
                    foreach ($resultado as $fila) {
                        if (strpos($fila['documento'], $_GET['buscar']) !== false) {
                            echo "<tr>
                                    <td>{$fila['documento']}</td>
                                    <td>{$fila['fecha']}</td>
                                    <td>{$fila['hora']}</td>
                                    <td>{$fila['nombre_comple']}</td>
                                    <td>{$fila['especializacion']}</td>
                                    <td>{$fila['estado']}</td>
                                    <td>";
                            if ($fila['estado'] == 'Pendiente') {
                                echo "<form action='' method='post'>
                                        <input type='hidden' name='id_cita' value='{$fila['id_cita']}'>
                                        <input type='hidden' name='fecha' value='{$fila['fecha']}'>
                                        <input type='hidden' name='hora' value='{$fila['hora']}'>
                                        <input type='submit' name='cancelar' value='Cancelar' class='btn btn-danger'>
                                      </form>";
                            }
                            echo    "</td>
                                  </tr>";
                        }
                    }
                } else {
                    foreach ($resultado as $fila) {
                        echo "<tr>
                                <td>{$fila['documento']}</td>
                                <td>{$fila['fecha']}</td>
                                <td>{$fila['hora']}</td>
                                <td>{$fila['nombre_comple']}</td>
                                <td>{$fila['especializacion']}</td>
                                <td>{$fila['estado']}</td>
                                <td>";
                        if ($fila['estado'] == 'Pendiente') {
                            echo "<form action='' method='post'>
                                    <input type='hidden' name='id_cita' value='{$fila['id_cita']}'>
                                    <input type='hidden' name='fecha' value='{$fila['fecha']}'>
                                    <input type='hidden' name='hora' value='{$fila['hora']}'>
                                    <input type='submit' name='cancelar' value='Cancelar' class='btn btn-danger'>
                                  </form>";
                        }
                        echo    "</td>
                              </tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
