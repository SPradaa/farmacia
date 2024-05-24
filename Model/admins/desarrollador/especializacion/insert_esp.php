<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_POST['guardar'])){
        $id_esp=$_POST['especializacion'];
        $especializacion=$_POST['especializacion'];
        


        if(!empty($id_esp) && !empty($especializacion)){

                $consulta_insert=$con->prepare('INSERT INTO especializacion(id_esp,especializacion) VALUES (:id_esp,:especializacion)');
                $consulta_insert->execute(array(':id_esp' =>$id_esp,':especializacion' =>$especializacion));
                echo '<script> alert("REGISTRO EXITOSO");</script>';
                echo '<script>window.location="index_esp.php"</script>';

                }
                
                else{
                    echo "<script> alert ('Los campos estan vacios');</script>";
                }
           }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva especializacion</title>
    <link rel="stylesheet" href="../../css/crearu.css">
</head>
<body>
    <div class="contenedor">
        <h2>Crear Especializacion</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="especializacion" placeholder="Especializacion" class="
                input__text">
            </div>

            <div class="btn__group">
                <a href="index_esp.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>