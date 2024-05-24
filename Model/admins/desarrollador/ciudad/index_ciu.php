<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();
?>

<?php 
$sentencia_select = $con->prepare("SELECT * FROM ciudad ORDER BY ciudad ASC");
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll();

if (isset($_GET['btn_buscar'])) {
    $buscar = $_GET['buscar'];
    $consulta = $con->prepare("SELECT * FROM ciudad WHERE ciudad LIKE :buscar ORDER BY ciudad ASC");
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
    <title>Ciudades</title>
    <link rel="stylesheet" href="../../css/estilo.css">
</head>
<body>
    <div class="contenedor">
        <h2>CIUDADES</h2>
        <div class="row mt-3">
            <div class="col-md-6">
            <?php if(isset($_GET['btn_buscar'])): ?>
            <form action="index_ciu.php" method="get">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        <?php else: ?>
            <form action="../datosgenerales.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        <?php endif; ?>
            </div>
            <div class="barra_buscador">
                <form action="" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar Ciudad" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                    <a href="insert_ciu.php" class="btn btn_nuevo">Crear Ciudad</a>
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>Ciudad</td>
                    <td colspan="2">Acción</td>
                </tr>
                <?php 
                if (isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM ciudad WHERE ciudad LIKE ? ORDER BY ciudad ASC");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['ciudad']; ?></td>
                        <td><a href="update_ciu.php?id_ciudad=<?php echo $fila['id_ciudad']; ?>" class="btn__update">Editar</a></td>
                        <td><a href="delete_ciu.php?id_ciudad=<?php echo $fila['id_ciudad']; ?>" class="btn__delete">Eliminar</a></td>
                    </tr>
                <?php 
                    }
                } else {
                    $consulta = $con->prepare("SELECT * FROM ciudad ORDER BY ciudad ASC");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['ciudad']; ?></td>
                        <td><a href="update_ciu.php?id_ciudad=<?php echo $fila['id_ciudad']; ?>" class="btn__update">Editar</a></td>
                        <td><a href="delete_ciu.php?id_ciudad=<?php echo $fila['id_ciudad']; ?>" class="btn__delete">Eliminar</a></td>
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
