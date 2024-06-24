<?php
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    session_start();
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
$correo = $_SESSION['correo'];
$rol = $_SESSION['tipo'];
$empresa = $_SESSION[ 'nit'];

$nombre_comple = $nombre .''.$apellido; 

// Verificar si se encontró al usuario
if (!$fila) {
    echo '<script>alert("Usuario no encontrado.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}

if(isset($_GET['btn_buscar'])) {
    $buscar = $_GET['buscar'];

    // Preparar la consulta SQL
    $consulta = $con->prepare("SELECT * FROM usuarios WHERE documento LIKE :buscar ORDER BY nombre ASC");

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../desarrollador/css/usuari.css">
    
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
        </div>
    <div class="espacios">

    <div >
            <form action="generar_pdf.php" method="post">
                <button type="submit" class="btn btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 384 512"><path fill="#ffffff" d="M181.9 256.1c-5-16-4.9-46.9-2-46.9c8.4 0 7.6 36.9 2 46.9m-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7c18.3-7 39-17.2 62.9-21.9c-12.7-9.6-24.9-23.4-34.5-40.8M86.1 428.1c0 .8 13.2-5.4 34.9-40.2c-6.7 6.3-29.1 24.5-34.9 40.2M248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24m-8 171.8c-20-12.2-33.3-29-42.7-53.8c4.5-18.5 11.6-46.6 6.2-64.2c-4.7-29.4-42.4-26.5-47.8-6.8c-5 18.3-.4 44.1 8.1 77c-11.6 27.6-28.7 64.6-40.8 85.8c-.1 0-.1.1-.2.1c-27.1 13.9-73.6 44.5-54.5 68c5.6 6.9 16 10 21.5 10c17.9 0 35.7-18 61.1-61.8c25.8-8.5 54.1-19.1 79-23.2c21.7 11.8 47.1 19.5 64 19.5c29.2 0 31.2-32 19.7-43.4c-13.9-13.6-54.3-9.7-73.6-7.2M377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9m-74.1 255.3c4.1-2.7-2.5-11.9-42.8-9c37.1 15.8 42.8 9 42.8 9"/></svg>
                PDF</button>
            </form>
            </div>

            <div >
            <form action="generar_reporte_excel.php" method="post">
        <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 384 512">
                <path fill="#ffffff" d="M181.9 256.1c-5-16-4.9-46.9-2-46.9c8.4 0 7.6 36.9 2 46.9m-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7c18.3-7 39-17.2 62.9-21.9c-12.7-9.6-24.9-23.4-34.5-40.8M86.1 428.1c0 .8 13.2-5.4 34.9-40.2c-6.7 6.3-29.1 24.5-34.9 40.2M248 160h136v328c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V24C0 10.7 10.7 0 24 0h200v136c0 13.2 10.8 24 24 24m-8 171.8c-20-12.2-33.3-29-42.7-53.8c4.5-18.5 11.6-46.6 6.2-64.2c-4.7-29.4-42.4-26.5-47.8-6.8c-5 18.3-.4 44.1 8.1 77c-11.6 27.6-28.7 64.6-40.8 85.8c-.1 0-.1.1-.2.1c-27.1 13.9-73.6 44.5-54.5 68c5.6 6.9 16 10 21.5 10c17.9 0 35.7-18 61.1-61.8c25.8-8.5 54.1-19.1 79-23.2c21.7 11.8 47.1 19.5 64 19.5c29.2 0 31.2-32 19.7-43.4c-13.9-13.6-54.3-9.7-73.6-7.2M377 105L279 7c-4.5-4.5-10.6-7-17-7h-6v128h128v-6.1c0-6.3-2.5-12.4-7-16.9m-74.1 255.3c4.1-2.7-2.5-11.9-42.8-9c37.1 15.8 42.8 9 42.8 9"/></svg>
            Excel
        </button>
    </form>
                </div>

    </div>
    
    <table>
                <tr class="head">
                    <td>Tipo de Documento</td>
                    <td>Documento</td>
                    <td>Nombre</td>
                    <td>Apellido</td>
                    <td>Telefono</td>
                    <td>Correo</td>
                    <td>Ciudad</td>
                    <td>Dirección</td>
                    <td>EPS</td>
                    <td>Tipo_sangre</td>
                    <td>Tipo_Usuario</td>
                    <td>Estado</td>
                    <td colspan="2">Acción</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM usuarios, t_documento, municipios, departamentos, empresas,
                    rh, roles, estados
                    WHERE usuarios.id_doc = t_documento.id_doc AND usuarios.id_municipio = municipios.id_municipio
                    AND municipios.id_depart = departamentos.id_depart 
                    AND usuarios.nit = empresas.nit AND usuarios.id_rh = rh.id_rh AND usuarios.id_rol = roles.id_rol
                    AND usuarios.id_estado = estados.id_estado AND documento LIKE ? ORDER BY nombre ASC");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['tipo']; ?></td>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['apellido']; ?></td>
                        <td><?php echo $fila['telefono']; ?></td>
                        <td><?php echo $fila['correo']; ?></td>
                        <td><?php echo $fila['depart']; ?></td>
                        <td><?php echo $fila['direccion']; ?></td>
                        <td><?php echo $fila['empresa']; ?></td>
                        <td><?php echo $fila['rh']; ?></td>
                        <td><?php echo $fila['rol']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><a href="update_usu.php?documento=<?php echo $fila['documento']; ?>" class="btn__update">Editar</a></td>
                        <td><a href="delete_usu.php?documento=<?php echo $fila['documento']; ?>" class="btn__delete">Eliminar</a></td>
                    </tr>
                <?php 
                    }
                } else {
                    // Mostrar todos los registros si no se ha realizado una búsqueda
                    $consulta = $con->prepare("SELECT * FROM usuarios, t_documento, municipios, departamentos, empresas,
                    rh, roles, estados
                    WHERE usuarios.id_doc = t_documento.id_doc AND usuarios.id_municipio = municipios.id_municipio
                    AND municipios.id_depart = departamentos.id_depart 
                    AND usuarios.nit = empresas.nit AND usuarios.id_rh = rh.id_rh AND usuarios.id_rol = roles.id_rol
                    AND usuarios.id_estado = estados.id_estado ORDER BY nombre ASC");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                    <td><?php echo $fila['tipo']; ?></td>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['apellido']; ?></td>
                        <td><?php echo $fila['telefono']; ?></td>
                        <td><?php echo $fila['correo']; ?></td>
                        <td><?php echo $fila['depart']; ?></td>
                        <td><?php echo $fila['direccion']; ?></td>
                        <td><?php echo $fila['empresa']; ?></td>
                        <td><?php echo $fila['rh']; ?></td>
                        <td><?php echo $fila['rol']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><a href="update_usu.php?documento=<?php echo $fila['documento']; ?>" class="btn__update">Editar</a></td>
                        <td><a href="delete_usu.php?documento=<?php echo $fila['documento']; ?>" class="btn__delete">Eliminar</a></td>
                    </tr>
                <?php 
                    }
                }
                ?>
            </table>
        </div>
    </body>
</html>