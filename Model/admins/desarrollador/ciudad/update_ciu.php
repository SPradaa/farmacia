<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_ciudad'])){
        $id_ciudad=(int) $_GET['id_ciudad'];

        $buscar_id=$con->prepare('SELECT * FROM ciudad WHERE id_ciudad=:id_ciudad');
        $buscar_id->execute(array(
            ':id_ciudad'=>$id_ciudad
        ));
        $resultado=$buscar_id->fetch();
    }else{
        header('Location: index_ciu.php');
    }

    if(isset($_POST['guardar'])){

        $ciudad=$_POST['ciudad'];
        $id_ciudad=(int) $_GET['id_ciudad'];

        if(!empty($id_ciudad) && !empty($ciudad)){
            {
            $consulta_update = $con->prepare('UPDATE ciudad SET ciudad=:ciudad WHERE id_ciudad =:id_ciudad;');
            $consulta_update->execute(array(
                ':ciudad' =>$ciudad,
                ':id_ciudad' =>$id_ciudad
            ));
            echo '<script> alert("REGISTRO ACTUALIZADO EXITOSAMENTE");</script>';
            echo '<script>window.location="index_ciu.php"</script>';
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
                <input type="text" name="id_ciudad" value="<?php if($resultado) echo
                    $resultado['id_ciudad']; ?>" class="input__text">
                <input type="text" name="ciudad" value="<?php if($resultado) echo 
                    $resultado['ciudad']; ?>" class="input__text">
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