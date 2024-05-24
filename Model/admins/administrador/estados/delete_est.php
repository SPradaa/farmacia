<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_estado'])){
        $id_estado=(int) $_GET['id_estado'];
        $delete=$con->prepare('DELETE FROM estados WHERE id_estado=:id_estado');
        $delete->execute(array(
            ':id_estado'=>$id_estado
        ));
        echo '<script> alert("REGISTRO ELIMINADO EXITOSAMENTE");</script>';
        echo '<script>window.location="index_est.php"</script>';
    }       
?>