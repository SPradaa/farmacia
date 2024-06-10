<?php
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento = $_POST['documento'];

    $sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
    $sql->bindParam(':documento', $documento);
    $sql->execute();
    $fila = $sql->fetch();

    if ($fila) {
        echo "existe";
    } else {
        echo "no_existe";
    }
}
?>
