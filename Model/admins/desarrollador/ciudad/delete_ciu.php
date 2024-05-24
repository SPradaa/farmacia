<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_ciudad'])){
        $id_ciudad=(int) $_GET['id_ciudad'];
        $delete=$con->prepare('DELETE FROM ciudad WHERE id_ciudad=:id_ciudad');
        $delete->execute(array(
            ':id_ciudad'=>$id_ciudad
        ));
        echo '<script> alert("REGISTRO ELIMINADO EXITOSAMENTE");</script>';
        echo '<script>window.location="index_ciu.php"</script>';
    }       
?>