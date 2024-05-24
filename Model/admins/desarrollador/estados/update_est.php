<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_estado'])){
        $id_estado=(int) $_GET['id_estado'];

        $buscar_id=$con->prepare('SELECT * FROM estados WHERE id_estado=:id_estado');
        $buscar_id->execute(array(
            ':id_estado'=>$id_estado
        ));
        $resultado=$buscar_id->fetch();
    }else{
        header('Location: index_est.php');
    }

    if(isset($_POST['guardar'])){

        $estado=$_POST['estado'];
        $id_estado=(int) $_GET['id_estado'];

        if(!empty($id_estado) && !empty($estado)){
            {
            $consulta_update = $con->prepare('UPDATE estados SET estado=:estado WHERE id_estado =:id_estado;');
            $consulta_update->execute(array(
                ':estado' =>$estado,
                ':id_estado' =>$id_estado
            ));
            echo '<script> alert("REGISTRO ACTUALIZADO EXITOSAMENTE");</script>';
            echo '<script>window.location="index_est.php"</script>';
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
                <input type="text" name="id_estado" value="<?php if($resultado) echo
                    $resultado['id_estado']; ?>" class="input__text">
                <input type="text" name="estado" value="<?php if($resultado) echo 
                    $resultado['estado']; ?>" class="input__text">
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