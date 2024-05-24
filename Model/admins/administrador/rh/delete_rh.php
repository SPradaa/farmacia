<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_rh'])){
        $id_rh=(int) $_GET['id_rh'];
        $delete=$con->prepare('DELETE FROM rh WHERE id_rh=:id_rh');
        $delete->execute(array(
            ':id_rh'=>$id_rh
        ));
        echo '<script> alert("REGISTRO ELIMINADO EXITOSAMENTE");</script>';
        echo '<script>window.location="index_rh.php"</script>';
    }      
?>