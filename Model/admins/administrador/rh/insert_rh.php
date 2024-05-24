<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_POST['guardar'])){
        $id_rh=$_POST['rh'];
        $rh=$_POST['rh'];
        


        if(!empty($id_rh) && !empty($rh)){

                $consulta_insert=$con->prepare('INSERT INTO rh(id_rh,rh) VALUES (:id_rh,:rh)');
                $consulta_insert->execute(array(':id_rh' =>$id_rh,':rh' =>$rh));
                echo '<script> alert("REGISTRO EXITOSO");</script>';
                echo '<script>window.location="index_rh.php"</script>';

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
    <title>Nuevo RH</title>
    <link rel="stylesheet" href="../../css/crearu.css">
</head>
<body>
    <div class="contenedor">
        <h2>Crear RH</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="rh" placeholder="Rh" class="
                input__text">
            </div>

            <div class="btn__group">
                <a href="index_rh.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>