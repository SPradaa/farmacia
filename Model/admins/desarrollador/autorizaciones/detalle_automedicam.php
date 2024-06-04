<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
    
?>

<?php 
    
    $sentencia_select=$con->prepare("SELECT * FROM det_autorizacion ORDER BY id_detalle ASC");
    
    $sentencia_select->execute();
    $resultado=$sentencia_select->fetchAll();
    
    
    if(isset($_GET['btn_buscar'])) {    
        $buscar = $_GET['buscar'];
    
        // Preparar la consulta SQL
        $consulta = $con->prepare("SELECT * FROM det_autorizacion WHERE id_detalle LIKE :buscar");
    
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
        <link rel="stylesheet" href="../../css/estilos1.css">
    </head>
    <body>
        <div class="contenedor">
            <h2>DETALLE AUTORIZACIÓN</h2>
            <div class="row mt-3">
        <div class="col-md-6">
            <form action="../../../admins/desarrollador/autorizaciones/index_automedicam.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        </div>
            <div class="barra_buscador">
                <form action="" class="formulario" method="GET">
                    <input type="text" name="buscar" placeholder="Buscar detalle" class="input_text">
                    <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                    <!-- <a href="insert_automedicam.php" class="btn btn_nuevo">Crear detalle</a> -->
                </form>
            </div>
            <table>
                <tr class="head">
                    <td>Detalle</td>
                    <td>Autorización</td>
                    <td>Medicamento</td>
                    <td>Cantidad</td>
                    <td>Medida Cantidad</td>
                    <td colspan="3">Acción</td>
                </tr>
                <?php 
                if(isset($_GET['btn_buscar'])) {
                    $buscar = $_GET['buscar'];
                    $consulta = $con->prepare("SELECT * FROM autorizaciones WHERE id_detalle LIKE ?");
                    $consulta->execute(array("%$buscar%"));
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['id_detalle']; ?></td>
                        <td><?php echo $fila['id_auto']; ?></td>
                        <td><?php echo $fila['id_medicamento']; ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td><?php echo $fila['medida_cant']; ?></td>
                        <td><a href="update_automedicam.php?id_detalle=<?php echo $fila['id_detalle']; ?>" class="btn__update">Editar</a></td>
                        <!-- <td><a href="delete_automedicam.php?id_detalle=<?php echo $fila['id_detalle']; ?>" class="btn__delete">Eliminar</a></td> -->
                        <td><a href="autorizar_automedicam.php?id_detalle=<?php echo $fila['id_detalle']; ?>" class="btn__autorizar">Autorizar</a></td>
                        
                    </tr>
                <?php 
                    }
                } else {
                    // Mostrar todos los registros si no se ha realizado una búsqueda
                    $consulta = $con->prepare("SELECT det_autorizacion.id_auto, det_autorizacion.id_medicamento, det_autorizacion.cantidad, det_autorizacion.medida_cant, autorizaciones.docu_medico, medicos.nombre_comple, medicos.correo, medicos.telefono, medicos.id_esp, autorizaciones.id_cita, autorizaciones.id_detalle
                    FROM autorizaciones
                    INNER JOIN medicos ON medicos.docu_medico = autorizaciones.docu_medico
                    INNER JOIN citas ON citas.id_cita = autorizaciones.id_cita
                    INNER JOIN det_autorizacion ON det_autorizacion.id_auto = autorizaciones.id_auto;");
                    $consulta->execute();
                    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $fila['id_detalle']; ?></td>
                        <td><?php echo $fila['id_auto']; ?></td>
                        <td><?php echo $fila['id_medicamento']; ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td><?php echo $fila['medida_cant']; ?></td>                        
                        <td><a href="update_automedicam.php?id_detalle=<?php echo $fila['id_detalle']; ?>" class="btn__update">Editar</a></td>
                        <!-- <td><a href="delete_automedicam.php?id_detalle=<?php echo $fila['id_detalle']; ?>" class="btn__delete">Eliminar</a></td> -->
                        <td><a href="autorizar_automedicam.php?id_detalle=<?php echo $fila['id_detalle']; ?>" class="btn__autorizar">Autorizar</a></td>
                    </tr>
                <?php 
                    }
                }
                ?>
            </table>
        </div>
    </body>
    </html>

