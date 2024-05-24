<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_esp'])){
        $id_esp=(int) $_GET['id_esp'];

        $buscar_id=$con->prepare('SELECT * FROM especializacion WHERE id_esp=:id_esp');
        $buscar_id->execute(array(
            ':id_esp'=>$id_esp
        ));
        $resultado=$buscar_id->fetch();
    }else{
        header('Location: index_esp.php');
    }

    if(isset($_POST['guardar'])){

        $especializacion=$_POST['especializacion'];
        $id_esp=(int) $_GET['id_esp'];

        if(!empty($id_esp) && !empty($especializacion)){
            {
            $consulta_update = $con->prepare('UPDATE especializacion SET especializacion=:especializacion WHERE id_esp =:id_esp;');
            $consulta_update->execute(array(
                ':especializacion' =>$especializacion,
                ':id_esp' =>$id_esp
            ));
            echo '<script> alert("ACTUALIZACION EXITOSA");</script>';
            echo '<script>window.location="index_esp.php"</script>';
            
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
                <input type="text" name="id_esp" value="<?php if($resultado) echo
                    $resultado['id_esp']; ?>" class="input__text">
                <input type="text" name="especializacion" value="<?php if($resultado) echo 
                    $resultado['especializacion']; ?>" class="input__text">
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