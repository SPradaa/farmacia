<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_doc'])){
        $id_doc=(int) $_GET['id_doc'];

        $buscar_id=$con->prepare('SELECT * FROM t_documento WHERE id_doc=:id_doc');
        $buscar_id->execute(array(
            ':id_doc'=>$id_doc
        ));
        $resultado=$buscar_id->fetch();
    }else{
        header('Location: index_docu.php');
    }

    if(isset($_POST['guardar'])){

        $tipo=$_POST['tipo'];
        $id_doc=(int) $_GET['id_doc'];

        if(!empty($id_doc) && !empty($tipo)){
            {
            $consulta_update = $con->prepare('UPDATE t_documento SET tipo=:tipo WHERE id_doc =:id_doc;');
            $consulta_update->execute(array(
                ':tipo' =>$tipo,
                ':id_doc' =>$id_doc
            ));
            echo '<script> alert("REGISTRO ACTUALIZADO EXITOSAMENTE");</script>';
            echo '<script>window.location="index_docu.php"</script>';
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
                <input type="text" name="id_doc" value="<?php if($resultado) echo
                    $resultado['id_doc']; ?>" class="input__text">
                <input type="text" name="tipo" value="<?php if($resultado) echo 
                    $resultado['tipo']; ?>" class="input__text">
            </div>
            <div class="btn__group">
                <a href="index_docu.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>