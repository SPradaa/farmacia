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

// Modificar la consulta para seleccionar todas las autorizaciones del paciente, incluyendo nombre y apellido del paciente y del médico, y el nombre del medicamento
$sentencia_select = $con->prepare("
    SELECT 
        autorizaciones.cod_auto, 
        autorizaciones.fecha, 
        usuarios.documento,
        usuarios.nombre, 
        usuarios.apellido, 
        medicos.nombre_comple, 
        medicamentos.nombre AS nombre_medicamento, 
        autorizaciones.presentacion, 
        autorizaciones.cantidad,
        autorizaciones.fecha_venc
    FROM autorizaciones
    JOIN usuarios ON autorizaciones.documento = usuarios.documento
    JOIN medicos ON autorizaciones.docu_medico = medicos.docu_medico
    JOIN medicamentos ON autorizaciones.id_medicamento = medicamentos.id_medicamento
    WHERE autorizaciones.documento = :user_document
    ORDER BY autorizaciones.fecha ASC
");
$sentencia_select->bindParam(':user_document', $user_document, PDO::PARAM_STR);
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorizaciones</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <h2>HISTORIA MEDICA - AUTORIZACIONES</h2>
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
                    <input type="text" name="buscar" placeholder="Buscar Autorización" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>Código Autorización</td>
                    <td>Fecha</td>
                    <td>Nombre Paciente</td>
                    <td>Apellido Paciente</td>
                    <td>Nombre Médico</td>
                    <td>Medicamento</td>
                    <td>Presentación</td>
                    <td>Cantidad</td>
                    <td>Fecha Vencimiento</td>
                    <td>Acción</td>
                </tr>
                <?php
                if (isset($_GET['btn_buscar'])) {
                    foreach ($resultado as $fila) {
                        if (strpos($fila['fecha'], $_GET['buscar']) !== false) {
                            echo "<tr>
                                    <td>{$fila['cod_auto']}</td>
                                    <td>{$fila['fecha']}</td>
                                    <td>{$fila['nombre']}</td>
                                    <td>{$fila['apellido']}</td>
                                    <td>{$fila['nombre_comple']}</td>
                                    <td>{$fila['nombre_medicamento']}</td>
                                    <td>{$fila['presentacion']}</td>
                                    <td>{$fila['cantidad']}</td>
                                    <td>{$fila['fecha_venc']}</td>
                                    <td><a href='' class='btn__autorizar' onclick=\"window.open('../verautorizacion/ver_autorizacion.php?documento={$fila['documento']}','','width=1000,height=700,toolbar=NO');void(null);\">Ver Auto.Medica</a></td>

                                  </tr>";
                        }
                    }
                } else {
                    foreach ($resultado as $fila) {
                        echo "<tr>
                                <td>{$fila['cod_auto']}</td>
                                <td>{$fila['fecha']}</td>
                                <td>{$fila['nombre']}</td>
                                <td>{$fila['apellido']}</td>
                                <td>{$fila['nombre_comple']}</td>
                                <td>{$fila['nombre_medicamento']}</td>
                                <td>{$fila['presentacion']}</td>
                                <td>{$fila['cantidad']}</td>
                                <td>{$fila['fecha_venc']}</td>
                                <td><a href='' class='btn__autorizar' onclick=\"window.open('../verautorizacion/ver_autorizacion.php?documento={$fila['documento']}','','width=1000,height=700,toolbar=NO');void(null);\">Ver Auto.Medica</a></td>


                              </tr>";
                    }
                }
                ?>
            </table>
        </div>
</body>
</html>
