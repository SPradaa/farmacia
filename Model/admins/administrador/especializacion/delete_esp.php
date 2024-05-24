<?php
session_start();
    require_once("../../../../db/connection.php"); 
    $conexion = new Database();
    $con = $conexion->conectar();
?>

<?php

    if(isset($_GET['id_esp'])){
        $id_esp=(int) $_GET['id_esp'];
        $delete=$con->prepare('DELETE FROM especializacion WHERE id_esp=:id_esp');
        $delete->execute(array(
            ':id_esp'=>$id_esp
        ));
        echo '<script> alert("REGISTRO ELIMINADO EXITOSAMENTE");</script>';
        echo '<script>window.location="index_esp.php"</script>';
    }    
?>