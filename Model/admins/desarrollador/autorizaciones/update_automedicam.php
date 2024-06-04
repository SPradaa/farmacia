<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_lab'])){
        $id_lab=(int) $_GET['id_lab'];

        $buscar_id=$con->prepare('SELECT * FROM laboratorio WHERE id_lab=:id_lab');
        $buscar_id->execute(array(
            ':id_lab'=>$id_lab
        ));
        $resultado=$buscar_id->fetch();
    }else{
        header('Location: index_lab.php');
    }

    if(isset($_POST['guardar'])){

        $laboratorio=$_POST['laboratorio'];
        $id_lab=(int) $_GET['id_lab'];

        if(!empty($id_lab) && !empty($laboratorio)){
            {
            $consulta_update = $con->prepare('UPDATE laboratorio SET laboratorio=:laboratorio WHERE id_lab =:id_lab;');
            $consulta_update->execute(array(
                ':laboratorio' =>$laboratorio,
                ':id_lab' =>$id_lab
            ));
            echo '<script> alert("REGISTRO ACTUALIZADO EXITOSAMENTE");</script>';
            echo '<script>window.location="index_lab.php"</script>';
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
                <input type="text" name="id_lab" value="<?php if($resultado) echo
                    $resultado['id_lab']; ?>" class="input__text">
                <input type="text" name="laboratorio" value="<?php if($resultado) echo 
                    $resultado['laboratorio']; ?>" class="input__text">
            </div>
            <div class="btn__group">
                <a href="index_lab.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>