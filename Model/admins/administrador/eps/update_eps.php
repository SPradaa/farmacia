<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_eps'])){
        $id_eps=(int) $_GET['id_eps'];

        $buscar_id=$con->prepare('SELECT * FROM eps WHERE id_eps=:id_eps');
        $buscar_id->execute(array(
            ':id_eps'=>$id_eps
        ));
        $resultado=$buscar_id->fetch();
    }else{
        header('Location: index_eps.php');
    }

    if(isset($_POST['guardar'])){

        $eps=$_POST['eps'];
        $id_eps=(int) $_GET['id_eps'];

        if(!empty($id_eps) && !empty($eps)){
            {
            $consulta_update = $con->prepare('UPDATE eps SET eps=:eps WHERE id_eps =:id_eps;');
            $consulta_update->execute(array(
                ':eps' =>$eps,
                ':id_eps' =>$id_eps
            ));
            echo '<script> alert("ACTUALIZACIÓN EXITOSA");</script>';
            echo '<script>window.location="index_eps.php"</script>';
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
                <input type="text" name="id_eps" value="<?php if($resultado) echo
                    $resultado['id_eps']; ?>" class="input__text">
                <input type="text" name="eps" value="<?php if($resultado) echo 
                    $resultado['eps']; ?>" class="input__text">
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