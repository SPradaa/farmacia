<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_doc'])){
        $id_ciudad=(int) $_GET['id_doc'];
        $delete=$con->prepare('DELETE FROM t_documento WHERE id_doc=:id_doc');
        $delete->execute(array(
            ':id_doc'=>$id_ciudad
        ));
        echo '<script> alert("REGISTRO ELIMINADO EXITOSAMENTE");</script>';
        echo '<script>window.location="index_docu.php"</script>';
    }       
?>