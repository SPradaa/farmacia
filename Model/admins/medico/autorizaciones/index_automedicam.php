<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();
?>

<?php 
$sentencia_select = $con->prepare("SELECT * FROM citas ORDER BY id_cita  ASC");
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll();

if(isset($_GET['btn_buscar'])) {    
    $buscar = $_GET['buscar'];
    $consulta = $con->prepare("SELECT * FROM citas WHERE id_cita LIKE :buscar");
    $buscar = "%$buscar%";
    $consulta->bindParam(':buscar', $buscar, PDO::PARAM_STR);
    $consulta->execute();
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas agendadas</title>
    <link rel="stylesheet" href="../../css/estilos1.css">
</head>
<body>
    <div class="contenedor">
        <h2>CITAS AGENDADAS</h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <form action="../modulomedico.php">
                    <input type="submit" value="Regresar" class="btn btn-secondary"/>
                </form>
            </div>
            <div class="barra_buscador">
                <form action="" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar autorización" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>Cita</td>
                    <td>Documento</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                    <td>Documento médico</td>
                    <td>Especialización</td>
                    <td>Estado</td>
                    <td colspan="2">Acción</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM citas WHERE id_cita LIKE ?");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['id_cita']; ?></td>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['fecha']; ?></td>
                        <td><?php echo $fila['hora']; ?></td>
                        <td><?php echo $fila['docu_medico']; ?></td>
                        <td><?php echo $fila['especializacion']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><a href="atender_automedicam.php?documento=<?php echo $fila['documento']; ?>" class="btn__atender">Atender</a></td>
                        <td><a href="" class="btn__autorizar" onclick="window.open('../autorizar/autorizaciones.php?documento=<?php echo $fila['id_cita'] ?>','','width=600,height=500,toolbar=NO');void(null);">Autorizar</a></td> 
                </tr>

                <?php 
                    }
                } else {
                    $consulta = $con->prepare("SELECT c.*, e.especializacion, es.estado
                           FROM citas c
                           JOIN especializacion e ON c.id_esp = e.id_esp
                           JOIN estados es ON c.id_estado = es.id_estado");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>

                    <tr>
                        <td><?php echo $fila['id_cita']; ?></td>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['fecha']; ?></td>
                        <td><?php echo $fila['hora']; ?></td>
                        <td><?php echo $fila['docu_medico']; ?></td>
                        <td><?php echo $fila['especializacion']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><a href="atender_automedicam.php?cedula=<?php echo $fila['documento']; ?>" class="btn__atender">Atender</a></td>
                        <td><a href="" class="btn__autorizar" onclick="window.open('../autorizar/autorizaciones.php?documento=<?php echo $fila['documento'] ?>','','width=600,height=500,toolbar=NO');void(null);">Autorizar</a></td> 
                </tr>
                <?php 
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
