<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();
?>

<?php
$sql = $con->prepare("SELECT * FROM medicos WHERE docu_medico = :documento");
$sql->bindParam(':documento', $_SESSION['documento']);
$sql->execute();
$fila = $sql->fetch();

$documento=$_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];
$direccion = $_SESSION['direccion'];
$telefono =$_SESSION['telefono'];
$correo= $_SESSION['correo'];
$rol = $_SESSION['tipo'];
$empresa = $_SESSION[ 'nit'];

$nombre_comple = $nombre . ' ' . $apellido; 

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.html";</script>';
    exit;
}
?>

<?php 
$sentencia_select = $con->prepare("SELECT * FROM citas WHERE DATE(fecha) = CURDATE() ORDER BY hora ASC");
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll();

if(isset($_GET['btn_buscar'])) {
    $buscar = $_GET['buscar'];
    $consulta = $con->prepare("SELECT * FROM citas WHERE documento LIKE :buscar AND DATE(fecha) = CURDATE() ORDER BY hora ASC");
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
        <h2>CITAS AGENDADAS MEDICO <?php echo $nombre; ?></h2>
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
                           WHERE c.documento LIKE ? AND DATE(c.fecha) = CURDATE()
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
                           WHERE DATE(c.fecha) = CURDATE()
                           ORDER BY c.hora ASC");
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

                <?php 
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
