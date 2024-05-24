<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_lab'])){
        $id_lab=(int) $_GET['id_lab'];
        $delete=$con->prepare('DELETE FROM laboratorio WHERE id_lab=:id_lab');
        $delete->execute(array(
            ':id_lab'=>$id_lab
        ));
        echo '<script> alert("REGISTRO ELIMINADO EXITOSAMENTE");</script>';
        echo '<script>window.location="index_lab.php"</script>';
    }      
?>