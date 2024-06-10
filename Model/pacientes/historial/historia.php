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

// Modificar la consulta para seleccionar todas las citas del paciente
$sentencia_select = $con->prepare("
    SELECT citas.id_cita, citas.documento, usuarios.nombre, usuarios.apellido, citas.fecha, citas.hora, medicos.nombre_comple, 
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
        <h2>HISTORIA MEDICA</h2>
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
                    <td>Acción</td>
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
                                    <td><a href='' class='btn__autorizar' onclick=\"window.open('../histo_clinica/verhisto_clinica.php?documento={$fila['documento']}','','width=1000,height=700,toolbar=NO');void(null);\">Ver His.Clinica</a></td>
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
                                <td><a href='' class='btn__autorizar' onclick=\"window.open('../verhistoria/verhisto_clinica.php?documento={$fila['documento']}','','width=1000,height=700,toolbar=NO');void(null);\">Ver His.Clinica</a></td>
                              </tr>";
                    }
                }
                ?>
            </table>
        </div>
</body>
</html>
