<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    
?>

<?php 
    
    $sentencia_select=$con->prepare("SELECT * FROM det_autorizacion ORDER BY id_auto ASC");
    
    $sentencia_select->execute();
    $resultado=$sentencia_select->fetchAll();
    
    
    if(isset($_GET['btn_buscar'])) {    
        $buscar = $_GET['buscar'];
    
        // Preparar la consulta SQL
        $consulta = $con->prepare("SELECT * FROM det_autorizacion WHERE id_auto LIKE :buscar");
    
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
        <title>Autorizacion de medicamentos</title>
        <link rel="stylesheet" href="../../css/estilos.css">
    </head>
    <body>
        <div class="contenedor">
            <h2>AUTORIZACIONES</h2>
            <div class="row mt-3">
        <div class="col-md-6">
            <form action="../modulomedico.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        </div>
            <div class="barra_buscador">
                <form action="" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar medicamento" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                    <a href="insert_automedicam.php" class="btn btn_nuevo">Autorizar</a>
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>id_auto</td>
                    <td>medicamento</td>
                    <td>cantidad</td>
                    <td>medida_cant</td>
                    <td>concentracion</td>
                    <td colspan="2">Acción</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM det_autorizacion WHERE id_auto LIKE ?");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['id_auto']; ?></td>
                        <td><?php echo $fila['id_medicamento']; ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td><?php echo $fila['medida_cant']; ?></td>
                        <td><?php echo $fila['concentracion']; ?></td>
                        <td><a href="update_automedicam.php?id_lab=<?php echo $fila['id_auto']; ?>" class="btn__update">Editar</a></td>
                        <td><a href="delete_automedicam.php?id_lab=<?php echo $fila['id_auto']; ?>" class="btn__delete">Eliminar</a></td>
                    </tr>
                <?php 
                    }
                } else {
                    // Mostrar todos los registros si no se ha realizado una búsqueda
                    $consulta = $con->prepare("SELECT autorizaciones.id_cita, medicos.nombre_comple, medicamentos.nombre, det_autorizacion.cantidad, det_autorizacion.medida_cant, det_autorizacion.concentracion FROM autorizaciones
                    INNER JOIN citas ON citas.id_cita = autorizaciones.id_cita INNER JOIN medicos ON medicos.id_doc = autorizaciones.id_doc");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['id_auto']; ?></td>
                        <td><?php echo $fila['id_medicamento']; ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td><?php echo $fila['medida_cant']; ?></td>
                        <td><?php echo $fila['concentracion']; ?></td>
                        <td><a href="update_automedicam.php?id_lab=<?php echo $fila['id_auto']; ?>" class="btn__update">Editar</a></td>
                        <td><a href="delete_automedicam.php?id_lab=<?php echo $fila['id_auto']; ?>" class="btn__delete">Eliminar</a></td>
                    </tr>
                <?php 
                    }
                }
                ?>
            </table>
        </div>
    </body>
    </html>

