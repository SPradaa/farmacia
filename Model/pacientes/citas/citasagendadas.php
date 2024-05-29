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
?>

                <?php
                $sentencia_select = $con->prepare("SELECT citas.documento, citas.fecha, citas.hora, medicos.nombre_comple AS nombre_medico, 
                especializacion.especializacion AS nombre_especializacion, estados.estado AS nombre_estado
                FROM citas 
                JOIN medicos ON citas.docu_medico = medicos.docu_medico 
                JOIN especializacion ON citas.id_esp = especializacion.id_esp 
                JOIN estados ON citas.id_estado = estados.id_estado
                WHERE citas.documento = :user_document
                ORDER BY citas.fecha ASC");
                $sentencia_select->bindParam(':user_document', $user_document, PDO::PARAM_STR);
                $sentencia_select->execute();
                $resultado = $sentencia_select->fetchAll();

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
                <form action="" class="formulario" method="GET">
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
                </tr>
                <?php
                if (isset($_GET['btn_buscar'])) {
                    foreach ($resultados as $fila) {
                ?>
                    <tr>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['fecha']; ?></td>
                        <td><?php echo $fila['hora']; ?></td>
                        <td><?php echo $fila['nombre_medico']; ?></td>
                        <td><?php echo $fila['nombre_especializacion']; ?></td>
                        <td><?php echo $fila['nombre_estado']; ?></td> <!-- Mostrar el nombre del estado -->
                    </tr>
                    <?php
    }
                } else {
                    foreach ($resultado as $fila) {
                ?>
                    <tr>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['fecha']; ?></td>
                        <td><?php echo $fila['hora']; ?></td>
                        <td><?php echo $fila['nombre_medico']; ?></td>
                        <td><?php echo $fila['nombre_especializacion']; ?></td>
                        <td><?php echo $fila['nombre_estado']; ?></td> <!-- Mostrar el nombre del estado -->
                    </tr>
                <?php
                    }
                }
                ?>
            </table>
        </div>
    </body>
</html>
