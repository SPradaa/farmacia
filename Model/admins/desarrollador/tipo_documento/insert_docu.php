<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_POST['guardar'])){
        $id_doc=$_POST['tipo'];
        $tipo=$_POST['tipo'];
        


        if(!empty($id_doc) && !empty($tipo)){

                $consulta_insert=$con->prepare('INSERT INTO t_documento(id_doc,tipo) VALUES (:id_doc,:tipo)');
                $consulta_insert->execute(array(':id_doc' =>$id_doc,':tipo' =>$tipo));
                echo '<script> alert("REGISTRO EXITOSO");</script>';
                echo '<script>window.location="index_docu.php"</script>';

                }
                
                else{
                    echo "<scrip> alert ('Los campos estan vacios');</scrip>";
                }
           }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Documento</title>
    <link rel="stylesheet" href="../../css/crearu.css">
</head>
<body>
    <div class="contenedor">
        <h2>Crear Documento de Identidad</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="tipo" placeholder="tipo" class="
                input__text">
            </div>

            <div class="btn__group">
                <a href="index_docu.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>