<?php
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

if (isset($_GET['documento'])) {
    $documento = $_GET['documento'];

    $sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
    $sql->bindParam(':documento', $documento);
    $sql->execute();
    $usuario = $sql->fetch();

    if ($usuario) {
        // Aquí puedes mostrar los detalles de las autorizaciones o lo que necesites
        echo "<h1>Detalles de autorizaciones para " . htmlspecialchars($usuario['nombre']) . " " . htmlspecialchars($usuario['apellido']) . "</h1>";
    } else {
        echo "<p>El documento no se encontró.</p>";
    }
}
?>
