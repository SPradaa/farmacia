<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_detalle'])){
        $id_detalle=(int) $_GET['id_detalle'];

        $buscar_id=$con->prepare('SELECT * FROM det_autorizacion WHERE id_detalle=:id_detalle');
        $buscar_id->execute(array(
            ':id_detalle'=>$id_detalle
        ));
        $resultado=$buscar_id->fetch();
    }else{
        header('Location: index_automedicam.php');
    }

    if(isset($_POST['guardar'])){

        $det_autorizacion=$_POST['det_autorizacion'];
        $id_detalle=(int) $_GET['id_detalle'];

        if(!empty($id_detalle) && !empty($det_autorizacion)){
            {
            $consulta_update = $con->prepare('UPDATE det_autorizacion SET det_autorizacion=:det_autorizacion WHERE id_detalle =:id_detalle;');
            $consulta_update->execute(array(
                ':det_autorizacion' =>$det_autorizacion,
                ':id_detalle' =>$id_detalle
            ));
            echo '<script> alert("REGISTRO ACTUALIZADO EXITOSAMENTE");</script>';
            echo '<script>window.location="index_automedicam.php"</script>';
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
                <input type="text" name="id_auto" value="<?php if($resultado) echo
                    $resultado['id_auto']; ?>" class="input__text">
                <input type="text" name="autorizaciones" value="<?php if(isset($resultado) && 
                isset($resultado['autorizaciones'])) echo $resultado['autorizaciones']; ?>" class="input__text">

            </div>
            <div class="btn__group">
                <a href="detalle_automedicam.php" class="btn btn__danger">Cancelar</a>
                <input type="submit" name="guardar" value="Guardar" class="btn
                btn__primary">
            </div>
        </form>
    </div>
</body>
</html>