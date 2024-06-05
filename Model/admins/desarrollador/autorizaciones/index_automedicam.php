<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();
?>

<?php 
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

if($buscar != '') {
    $consulta = $con->prepare("SELECT citas.id_cita, citas.documento, citas.fecha, citas.hora, citas.docu_medico, especializacion.especializacion, estados.estado
                                FROM citas
                                INNER JOIN especializacion ON especializacion.id_esp = citas.id_esp
                                INNER JOIN estados ON estados.id_estado = citas.id_estado
                                WHERE citas.id_cita LIKE :buscar
                                ORDER BY citas.id_cita ASC");
    $buscar_param = "%$buscar%";
    $consulta->bindParam(':buscar', $buscar_param, PDO::PARAM_STR);
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
} else {
    $consulta = $con->prepare("SELECT citas.id_cita, citas.documento, citas.fecha, citas.hora, citas.docu_medico, especializacion.especializacion, estados.estado
                                FROM citas
                                INNER JOIN especializacion ON especializacion.id_esp = citas.id_esp
                                INNER JOIN estados ON estados.id_estado = citas.id_estado
                                ORDER BY citas.id_cita ASC");
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas agendadas</title>
    <link rel="stylesheet" href="">
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
                foreach ($resultado as $fila) {
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
                        <td><a href="" class="btn__autorizar" onclick="window.open('../autorizar/autorizaciones.php?documento=<?php echo $fila['documento'] ?>','','width=600,height=500,toolbar=NO');void(null);">Autorizar</a></td>
                    </tr>
                <?php 
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
