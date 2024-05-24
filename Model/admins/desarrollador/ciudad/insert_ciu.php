<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_POST['guardar'])){
        $id_ciudad=$_POST['ciudad'];
        $ciudad=$_POST['ciudad'];
        


        if(!empty($id_ciudad) && !empty($ciudad)){

                $consulta_insert=$con->prepare('INSERT INTO ciudad(id_ciudad,ciudad) VALUES (:id_ciudad,:ciudad)');
                $consulta_insert->execute(array(':id_ciudad' =>$id_ciudad,':ciudad' =>$ciudad));
                echo '<script> alert("REGISTRO EXITOSO");</script>';
                echo '<script>window.location="index_ciu.php"</script>';

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
    <title>Nueva Ciudad</title>
    <link rel="stylesheet" href="../../css/crearu.css">
</head>
<body>
    <div class="contenedor">
        <h2>Crear Ciudad</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="ciudad" placeholder="Ciudad" class="
                input__text">
            </div>

            <div class="btn__group">
                <a href="index_ciu.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>