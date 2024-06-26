<?php
// session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?> 
<?php
require_once("../../../../controller/seg.php");
validarSesion();
?>
<?php
$sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
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
$nit = $_SESSION[ 'nit'];

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}
?>

<?php 
    
    $sentencia_select=$con->prepare("SELECT * FROM usuarios ORDER BY nombre ASC");
    
    $sentencia_select->execute();
    $resultado=$sentencia_select->fetchAll();
    
    
    if(isset($_GET['btn_buscar'])) {
        $buscar = $_GET['buscar'];
    
        // Preparar la consulta SQL
        $consulta = $con->prepare("SELECT * FROM usuarios WHERE nombre LIKE :buscar ORDER BY nombre ASC");
    
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
        <title>Usuarios</title>
        <link rel="stylesheet" href="../css/usuarios.css">
    </head>
    <body>
        <div class="contenedor">
            <h2>USUARIOS REGISTRADOS</h2>
            <div class="row mt-3">
        <div class="col-md-6">
        <?php if(isset($_GET['btn_buscar'])): ?>
            <form action="index_usu.php" method="get">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        <?php else: ?>
            <form action="../usuarios.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        <?php endif; ?>
        </div>
            <div class="barra_buscador">
                <form action="" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar Usuario" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                    
                </form>
            </div>
            <div class="table-responsive">
                <table>
                    <tr class="head">
                    <td>Tipo de Documento</td>
                    <td>Documento</td>
                    <td>Nombre</td>
                    <td>Apellido</td>
                    <td>Correo</td>
                    <td>Telefono</td>
                    <td>Departamento</td>
                    <td>Municipio</td>
                    <td>Dirección</td>
                    <td>Tipo de Sangre</td>
                    <td>EPS</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM usuarios, empresas, t_documento, municipios, departamentos, rh
                    WHERE usuarios.nit = '$nit' AND usuarios.nit = empresas.nit AND usuarios.id_doc = t_documento.id_doc AND usuarios.id_municipio = municipios.id_municipio
                    AND municipios.id_depart = departamentos.id_depart AND usuarios.id_rh = rh.id_rh AND nombre LIKE ? ORDER BY nombre ASC");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['tipo']; ?></td>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['apellido']; ?></td>
                        <td><?php echo $fila['correo']; ?></td>
                        <td><?php echo $fila['telefono']; ?></td>
                        <td><?php echo $fila['depart']; ?></td>
                        <td><?php echo $fila['municipio']; ?></td>
                        <td><?php echo $fila['direccion']; ?></td>
                        <td><?php echo $fila['rh']; ?></td>
                        <td><?php echo $fila['empresa']; ?></td>
                        
                    </tr>
                <?php 
                    }
                } else {
                    // Mostrar todos los registros si no se ha realizado una búsqueda
                    $consulta = $con->prepare("SELECT * FROM usuarios, empresas, t_documento, municipios, departamentos, rh
                    WHERE usuarios.nit = '$nit' AND usuarios.nit = empresas.nit AND usuarios.id_doc = t_documento.id_doc AND usuarios.id_municipio = municipios.id_municipio
                    AND municipios.id_depart = departamentos.id_depart AND usuarios.id_rh = rh.id_rh ORDER BY nombre ASC");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['tipo']; ?></td>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['apellido']; ?></td>
                        <td><?php echo $fila['correo']; ?></td>
                        <td><?php echo $fila['telefono']; ?></td>
                        <td><?php echo $fila['depart']; ?></td>
                        <td><?php echo $fila['municipio']; ?></td>
                        <td><?php echo $fila['direccion']; ?></td>
                        <td><?php echo $fila['rh']; ?></td>
                        <td><?php echo $fila['empresa']; ?></td>
                      
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

