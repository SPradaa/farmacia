<?php
// session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

require_once("../../../../controller/seg.php");
validarSesion();


// Obtener el documento del médico de la sesión
$docu_medico = $_SESSION['documento'];

// Configurar la zona horaria a Colombia
date_default_timezone_set('America/Bogota');

// Obtener la hora actual
$hora_actual = date('H:i:s');

// Consulta para obtener las citas del día actual del médico y con id_estado = 1
$sentencia_select = $con->prepare("SELECT * FROM citas WHERE DATE(fecha) = CURDATE() AND docu_medico = :docu_medico AND id_estado = 1 ORDER BY hora ASC");
$sentencia_select->bindParam(':docu_medico', $docu_medico);
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll();

foreach ($resultado as $fila) {
    $hora = $fila['hora'];
    $id_cita = $fila['id_cita'];
    $id_estado = $fila['id_estado'];
    
    // Sumar 5 minutos a la hora de la cita
    $hora_dt = new DateTime($hora);
    $hora_dt->add(new DateInterval('PT5M')); // Suma 5 minutos
    $hora_mas_5min = $hora_dt->format('H:i:s');
    
    // Comparar si la cita está dentro del rango de 5 minutos y está pendiente
    $hora_actual = date('H:i:s');
    if ($hora_actual >= $hora_mas_5min && $id_estado == 1) {
        // Actualizar el estado de la cita a Cancelada (id_estado = 6)
        $actualizar_estado = $con->prepare("UPDATE citas SET id_estado = 6 WHERE id_cita = :id_cita");
        $actualizar_estado->bindParam(':id_cita', $id_cita);
        $actualizar_estado->execute();
    }
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
        <h2>CITAS AGENDADAS MEDICO <?php echo $_SESSION['nombre']; ?></h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <form action="../modulomedico.php">
                    <input type="submit" value="Regresar" class="btn btn-secondary"/>
                </form>
            </div>
            <div class="barra_buscador">
                <form action="" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar por documento" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>Documento</td>
                    <td>Nombre</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                    <td>Estado</td>
                    <td colspan="3">Acción</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT c.*, e.especializacion, es.estado, us.nombre
                           FROM citas c
                           JOIN especializacion e ON c.id_esp = e.id_esp
                           JOIN estados es ON c.id_estado = es.id_estado
                           JOIN usuarios us ON c.documento = us.documento
                           WHERE c.documento LIKE ? AND DATE(c.fecha) = CURDATE() AND c.docu_medico = :docu_medico AND c.id_estado = 1
                           ORDER BY c.hora ASC");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['fecha']; ?></td>
                        <td><?php echo $fila['hora']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><a href="historia_clinica.php?id_cita=<?php echo $fila['id_cita']; ?>" class="btn__atender">Atender</a></td>
                        <td><a href="" class="btn__autorizar" onclick="window.open('../histo_clinica/verhisto_clinica.php?id_cita=<?php echo $fila['id_cita'] ?>','','width=1000,height=700,toolbar=NO');void(null);">Ver His.Clinica</a></td>
                        <td><a href="" class="btn__auto" onclick="window.open('../autorizacion/ver_autorizacion.php?id_cita=<?php echo $fila['id_cita'] ?>','','width=1000,height=700,toolbar=NO');void(null);">Ver Autorizacion</a></td>
                    </tr>
                <?php 
                    }
                } else {
                    $consulta = $con->prepare("SELECT c.*, e.especializacion, es.estado, us.nombre
                           FROM citas c
                           JOIN especializacion e ON c.id_esp = e.id_esp
                           JOIN estados es ON c.id_estado = es.id_estado
                           JOIN usuarios us ON c.documento = us.documento
                           WHERE DATE(c.fecha) = CURDATE() AND c.docu_medico = :docu_medico AND c.id_estado = 1
                           ORDER BY c.hora ASC");
                    $consulta->bindParam(':docu_medico', $docu_medico);
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['fecha']; ?></td>
                        <td><?php echo $fila['hora']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><a href="historia_clinica.php?id_cita=<?php echo $fila['id_cita']; ?>&documento=<?php echo $fila['documento']; ?>&docu_medico=<?php echo $fila['docu_medico']; ?>" class="btn__atender">Atender</a></td>
                        <td><a href="" class="btn__autorizar" onclick="window.open('../histo_clinica/verhisto_clinica.php?id_cita=<?php echo $fila['id_cita'] ?>','','width=1000,height=700,toolbar=NO');void(null);">Ver His.Clinica</a></td> 
                        <td><a href="" class="btn__auto" onclick="window.open('../autorizacion/ver_autorizacion.php?id_cita=<?php echo $fila['id_cita'] ?>','','width=1000,height=700,toolbar=NO');void(null);">Ver Autorizacion</a></td>
                    </tr>
                <?php 
                    }
                }
                ?>
            </table>
        </div>
    </div>
    <script>
    // Función para recargar la página cada 5 segundos
    function recargarPagina() {
        setTimeout(function() {
            location.reload();
        }, 5000);
    }

    // Llamar a la función para iniciar la recarga automática
    recargarPagina();
    </script>
</body>
</html>



