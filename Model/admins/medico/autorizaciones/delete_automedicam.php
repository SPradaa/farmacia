<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_auto'])){
        $id_lab=(int) $_GET['id_lab'];
        $delete=$con->prepare('DELETE FROM autorizaciones WHERE id_auto=:id_auto');
        $delete->execute(array(
            ':id_auto'=>$id_auto
        ));
        echo '<script> alert("REGISTRO ELIMINADO EXITOSAMENTE");</script>';
        echo '<script>window.location="index_automedicam.php"</script>';
    }      
?>