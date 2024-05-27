<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();
?>

<?php 
$sentencia_select = $con->prepare("SELECT * FROM citas ORDER BY documento ASC");
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll();

if(isset($_GET['btn_buscar'])) {    
    $buscar = $_GET['buscar'];
    $consulta = $con->prepare("SELECT * FROM citas WHERE documento LIKE :buscar");
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
    <title>Autorizacion de medicamentos</title>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <h2>AUTORIZACIONES DISPONIBLES</h2>
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
                    <td></td>
                    <td>Cita</td>
                    <td>Documento</td>
                    <td>Hora</td>
                    <td>Documento médico</td>
                    <td>Especialización</td>
                    <td>Estado</td>
                    <td colspan="2">Acción</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM citas WHERE documento LIKE ?");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><input type="checkbox" name="select_row[]"></td>
                        <td><?php echo $fila['id_cita']; ?></td>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['hora']; ?></td>
                        <td><?php echo $fila['docu_medico']; ?></td>
                        <td><?php echo $fila['especializacion']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><a href="detalle_automedicam.php?documento=<?php echo $fila['documento']; ?>" class="btn__detalle">Ver Detalle</a></td>
                        <td><a href="" class="btn__autorizar" onclick="window.open
                        ('../autorizar/autorizaciones.php?documento=<?php echo $fila['documento'] ?>','','width= 600,height=500, toolbar=NO');void(null);">Autorizar</a>

                  </tr>
                <?php 
                    }
                } else {
                    $consulta = $con->prepare("SELECT citas.id_cita, citas.documento, citas.hora, citas.docu_medico, especializacion.especializacion, estados.estado
                    FROM citas
                    INNER JOIN especializacion ON especializacion.id_esp = citas.id_esp
                    INNER JOIN estados ON estados.id_estado = citas.id_estado;");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>


                    <tr>
                        <td><input type="checkbox" name="select_row[]"></td>
                        <td><?php echo $fila['id_cita']; ?></td>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['hora']; ?></td>
                        <td><?php echo $fila['docu_medico']; ?></td>
                        <td><?php echo $fila['especializacion']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><a href="detalle_automedicam.php?documento=<?php echo $fila['documento']; ?>" class="btn__detalle">Ver Detalle</a></td>
                        <td><a href="" class="btn__autorizar" onclick="window.open
                        ('../autorizar/autorizaciones.php?documento=<?php echo $fila['documento'] ?>','','width= 600,height=500, toolbar=NO');void(null);">Autorizar</a>

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
