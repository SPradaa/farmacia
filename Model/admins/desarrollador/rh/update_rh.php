<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_rh'])){
        $id_rh=(int) $_GET['id_rh'];

        $buscar_id=$con->prepare('SELECT * FROM rh WHERE id_rh=:id_rh');
        $buscar_id->execute(array(
            ':id_rh'=>$id_rh
        ));
        $resultado=$buscar_id->fetch();
    }else{
        header('Location: index_rh.php');
    }

    if(isset($_POST['guardar'])){

        $rh=$_POST['rh'];
        $id_rh=(int) $_GET['id_rh'];

        if(!empty($id_rh) && !empty($rh)){
            {
            $consulta_update = $con->prepare('UPDATE rh SET rh=:rh WHERE id_rh =:id_rh;');
            $consulta_update->execute(array(
                ':rh' =>$rh,
                ':id_rh' =>$id_rh
            ));
            echo '<script> alert("REGISTRO ACTUALIZADO EXITOSAMENTE");</script>';
            echo '<script>window.location="index_rh.php"</script>';
        }
    }else{
        echo "<scrip> alert ('Los campos estan vacios');</scrip>";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar informacion</title>
    <link rel="stylesheet" href="../../css/editar.css">
</head>
<body>
    <div class="contenedor">
        <h2>Editar Informacion </h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="id_rh" value="<?php if($resultado) echo
                    $resultado['id_rh']; ?>" class="input__text">
                <input type="text" name="rh" value="<?php if($resultado) echo 
                    $resultado['rh']; ?>" class="input__text">
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