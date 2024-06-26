<?php
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();
// session_start();
?>
<?php
require_once("../../../../controller/seg.php");
validarSesion();
?>

<?php 
$sentencia_select=$con->prepare("SELECT * FROM empresas ORDER BY empresa ASC");
$sentencia_select->execute();
$resultado=$sentencia_select->fetchAll();

if(isset($_GET['btn_buscar'])) {
    $buscar = $_GET['buscar'];
    $consulta = $con->prepare("SELECT * FROM empresas WHERE empresa LIKE :buscar");
    $buscar = "%$buscar%";
    $consulta->bindParam(':buscar', $buscar, PDO::PARAM_STR);
    $consulta->execute();
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empresas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="../../../../assets/img/log.png" rel="icon">
    <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
</head>
<style>
@media (max-width: 768px) {
.contenedor{
    width: 150%;
    margin-left: 4px;
}}
</style>
<body>

<div class="contenedor">
    <h2>EMPRESAS</h2>
    <div class="row mt-3">
        <div class="col-md-6">
        <?php if(isset($_GET['btn_buscar'])): ?>
            <form action="index_emp.php" method="get">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        <?php else: ?>
            <form action="../index.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        <?php endif; ?>
        </div>
        <div class="barra_buscador">
            <form action="" class="formulario" method="GET">
                <input type="text" name="buscar" placeholder="Buscar Empresa" class="input_text">
                <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                <a href="create_emp.php" class="btn btn_nuevo">Crear Empresa</a>
            </form>
        </div>
        <div class="table-container"></div>
        <table>
            <tr class="head">
                <td>Nit</td>
                <td>Nombre de la Empresa</td>
                <td>Licencia</td>
                <td>Inicia</td>
                <td>Expira</td>
                <td>Código único</td>
                <td>Estado</td>
                <td colspan="2">Acción</td>
            </tr>
            <?php 
            if(isset($_GET['btn_buscar'])) {
                $buscar = $_GET['buscar'];
                $consulta = $con->prepare("SELECT * FROM empresas, estados WHERE empresas.id_estado = estados.id_estado AND empresa LIKE ?");
                $consulta->execute(array("%$buscar%"));
                while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <td><?php echo $fila['nit']; ?></td>
                    <td><?php echo $fila['empresa']; ?></td>
                    <td><?php echo $fila['licencia']; ?></td>
                    <td><?php echo $fila['inicio']; ?></td>
                    <td><?php echo $fila['fin']; ?></td>
                    <td><?php echo $fila['codigo_unico']; ?></td>
                    <td><?php echo $fila['estado']; ?></td>
                    <td><a href="update_emp.php?nit=<?php echo $fila['nit']; ?>" class="btn__update">Editar</a></td>
                    <td><a href="delete_emp.php?nit=<?php echo $fila['nit']; ?>" class="btn__delete">Eliminar</a></td>
                </tr>
            <?php 
                }
            } else {
                // Mostrar todos los registros si no se ha realizado una búsqueda
                $consulta = $con->prepare("SELECT * FROM empresas, estados WHERE empresas.id_estado = estados.id_estado ORDER BY empresa");
                $consulta->execute();
                while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <td><?php echo $fila['nit']; ?></td>
                    <td><?php echo $fila['empresa']; ?></td>
                    <td><?php echo $fila['licencia']; ?></td>
                    <td><?php echo $fila['inicio']; ?></td>
                    <td><?php echo $fila['fin']; ?></td>
                    <td><?php echo $fila['codigo_unico']; ?></td>
                    <td><?php echo $fila['estado']; ?></td>
                    <td><a href="update_emp.php?nit=<?php echo $fila['nit']; ?>" class="btn__update">Editar</a></td>
                    <td><a href="delete_emp.php?nit=<?php echo $fila['nit']; ?>" class="btn__delete">Eliminar</a></td>
                </tr>
            <?php 
                }
            }
            ?>
        </table>
     </div>
    </div>
</div>
</body>
</html>
