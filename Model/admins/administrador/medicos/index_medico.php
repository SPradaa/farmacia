<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    
?>

<?php
$sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$sql->bindParam(':documento', $_SESSION['documento']);
$sql->execute();
$fila = $sql->fetch();
echo"conectado";

$documento=$_SESSION['documento'];
$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];
$direccion = $_SESSION['direccion'];
$telefono =$_SESSION['telefono'];
$correo= $_SESSION['correo'];
$rol = $_SESSION['tipo'];
$nit = $_SESSION[ 'nit'];

$nombre_comple = $nombre .''.$apellido; 

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;


}
?>
<?php 
    
    $sentencia_select=$con->prepare("SELECT * FROM medicos ORDER BY nombre_comple ASC");
    
    $sentencia_select->execute();
    $resultado=$sentencia_select->fetchAll();
    
    
    if(isset($_GET['btn_buscar'])) {
        $buscar = $_GET['buscar'];
    
        // Preparar la consulta SQL
        $consulta = $con->prepare("SELECT * FROM medicos WHERE nombre_comple LIKE :buscar ORDER BY nombre_comple ASC");
    
        // Asignar valor al parámetro
        $buscar = "%$buscar%";
        
        // Vincular el parámetro
        $consulta->bindParam(':buscar', $buscar, PDO::PARAM_STR);
    
        // Ejecutar la consulta
        $consulta->execute();
    
        // Obtener los resultados
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
        
    }
    ?>
    
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Medicos</title>
        <link rel="stylesheet" href="../../css/estilos.css">
    </head>
    <body>
        <div class="contenedor">
            <h2>MEDICOS REGISTRADOS</h2>
            <div class="row mt-3">
        <div class="col-md-6">
        <?php if(isset($_GET['btn_buscar'])): ?>
            <form action="index_medico.php" method="get">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        <?php else: ?>
            <form action="../modulomedico.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        <?php endif; ?>
        </div>
            <div class="barra_buscador">
                <form action="" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar Medico" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                    <a href="create_medico.php" class="btn btn_nuevo">Crear Medico</a>
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>Tipo de Documento</td>
                    <td>Documento</td>
                    <td>Nombre Completo</td>
                    <td>Correo</td>
                    <td>empresa</td>
                    <td>Tipo de Usuario</td>
                    <td>Estado</td>
                    <td>Especialización</td>
                    
                    <td colspan="2">Acción</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM medicos, t_documento, roles, estados, especializacion, empresas
                    WHERE medicos.id_doc = t_documento.id_doc AND medicos.id_estado = estados.id_estado AND
                    medicos.id_rol = roles.id_rol AND medicos.id_esp = especializacion.id_esp and empresas.nit = empresas.nit AND medico.nit = `$nit` AND nombre_comple LIKE ? where  ORDER BY nombre_comple ASC");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['tipo']; ?></td>
                        <td><?php echo $fila['docu_medico']; ?></td>
                        <td><?php echo $fila['nombre_comple']; ?></td>
                        <td><?php echo $fila['correo']; ?></td>
                        <td><?php echo $fila['empresa']; ?></td>
                        <td><?php echo $fila['rol']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><?php echo $fila['especializacion']; ?></td>
                        <td><?php echo $fila['empresa']; ?></td>
                        

                        <td><a href="update_medico.php?docu_medico=<?php echo $fila['docu_medico']; ?>" class="btn__update">Editar</a></td>
                        <td><a href="delete_medico.php?docu_medico=<?php echo $fila['docu_medico']; ?>" class="btn__delete">Eliminar</a></td>
                    </tr>
                <?php 
                    }
                } else {
                    // Mostrar todos los registros si no se ha realizado una búsqueda
                    $consulta = $con->prepare("SELECT * FROM medicos, t_documento, roles, estados, especializacion, empresas
                    WHERE medicos.id_doc = t_documento.id_doc AND medicos.id_estado = estados.id_estado AND
                    medicos.id_rol = roles.id_rol AND medicos.id_esp = especializacion.id_esp and empresas.nit = empresas.nit ORDER BY nombre_comple ASC");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['tipo']; ?></td>
                        <td><?php echo $fila['docu_medico']; ?></td>
                        <td><?php echo $fila['nombre_comple']; ?></td>
                        <td><?php echo $fila['correo']; ?></td>
                        <td><?php echo $fila['empresa']; ?></td>
                        <td><?php echo $fila['rol']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><?php echo $fila['especializacion']; ?></td>
                        <td><a href="update_medico.php?docu_medico=<?php echo $fila['docu_medico']; ?>" class="btn__update">Editar</a></td>
                        <td><a href="delete_medico.php?docu_medico=<?php echo $fila['docu_medico']; ?>" class="btn__delete">Eliminar</a></td>
                    </tr>
                <?php 
                    }
                }
                ?>
            </table>
        </div>
    </body>
    </html>

