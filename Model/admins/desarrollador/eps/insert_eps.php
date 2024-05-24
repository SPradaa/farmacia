<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_POST['guardar'])){
        $id_eps=$_POST['eps'];
        $eps=$_POST['eps'];
        


        if(!empty($id_eps) && !empty($eps)){

                $consulta_insert=$con->prepare('INSERT INTO eps(id_eps,eps) VALUES (:id_eps,:eps)');
                $consulta_insert->execute(array(':id_eps' =>$id_eps,':eps' =>$eps));
                echo '<script> alert("REGISTRO EXITOSO");</script>';
                echo '<script>window.location="index_eps.php"</script>';

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
    <title>Nueva EPS</title>
    <link rel="stylesheet" href="../../css/crearu.css">
</head>
<body>
    <div class="contenedor">
        <h2>Crear EPS</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="eps" placeholder="Eps" class="
                input__text">
            </div>

            <div class="btn__group">
                <a href="index_eps.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>