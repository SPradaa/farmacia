<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_POST['guardar'])){
        $id_estado=$_POST['estado'];
        $estado=$_POST['estado'];
        


        if(!empty($id_estado) && !empty($estado)){

                $consulta_insert=$con->prepare('INSERT INTO estados(id_estado,estado) VALUES (:id_estado,:estado)');
                $consulta_insert->execute(array(':id_estado' =>$id_estado,':estado' =>$estado));
                echo '<script> alert("REGISTRO EXITOSO");</script>';
                echo '<script>window.location="index_est.php"</script>';

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
    <title>Nuevo Estado</title>
    <link rel="stylesheet" href="../../css/crearu.css">
</head>
<body>
    <div class="contenedor">
        <h2>Crear Estado</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="estado" placeholder="Estado" class="
                input__text">
            </div>

            <div class="btn__group">
                <a href="index_est.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>