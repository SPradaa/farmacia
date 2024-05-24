<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_eps'])){
        $id_eps=(int) $_GET['id_eps'];
        $delete=$con->prepare('DELETE FROM eps WHERE id_eps=:id_eps');
        $delete->execute(array(
            ':id_eps'=>$id_eps
        ));
        echo '<script> alert("REGISTRO ELIMINADO EXITOSAMENTE");</script>';
        echo '<script>window.location="index_eps.php"</script>';
    }     
?>