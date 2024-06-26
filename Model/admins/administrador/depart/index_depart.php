<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    
?>
<?php
require_once("../../../../controller/seg.php");
validarSesion();
?>
<?php 
    
    $sentencia_select=$con->prepare("SELECT * FROM departamentos ORDER BY depart ASC");
    
    $sentencia_select->execute();
    $resultado=$sentencia_select->fetchAll();
    
    
    if(isset($_GET['btn_buscar'])) {
        $buscar = $_GET['buscar'];
    
        // Preparar la consulta SQL
        $consulta = $con->prepare("SELECT * FROM departamentos WHERE depart LIKE :buscar ORDER BY depart ASC");
    
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
        <title>Departamentos</title>
        <link rel="stylesheet" href="../../desarrollador/css/tip_usu.css">
        <link href="../../../../assets/img/log.png" rel="icon">
        <link href="../../../../assets/img/log.png" rel="apple-touch-icon">
    </head>
    <body>
        <div class="contenedor">
            <h2>DEPARTAMENTOS DE COLOMBIA</h2>
            <div class="row mt-3">
        <div class="col-md-6">
        <?php if(isset($_GET['btn_buscar'])): ?>
            <form action="index_depart.php" method="get">
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
                    <input type="text" name="buscar" placeholder="Buscar Departamento" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>Departamento</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM departamentos WHERE depart LIKE ? ORDER BY depart ASC");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['depart']; ?></td>
                        
                    </tr>
                <?php 
                    }
                } else {
                    // Mostrar todos los registros si no se ha realizado una búsqueda
                    $consulta = $con->prepare("SELECT * FROM departamentos ORDER BY depart ASC");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['depart']; ?></td>
                        
                    </tr>
                <?php 
                    }
                }
                ?>
            </table>
        </div>
    </body>
    </html>

