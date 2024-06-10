<?php
require_once("../../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];

    // Verificar si el código de autorización existe
    $checkCodeQuery = "SELECT * FROM autorizaciones WHERE cod_autorizacion = :codigo";
    $stmt = $con->prepare($checkCodeQuery);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $autorizacion = $stmt->fetch(PDO::FETCH_ASSOC);
        // Puedes procesar la autorización aquí o devolver un mensaje de éxito
        echo "<script>alert('Código de autorización válido.'); window.location.href='autorizacion_detalle.php?codigo=$codigo';</script>";
    } else {
        echo "<script>alert('Código de autorización no válido.'); window.location.href='verificar_codigo_form.php';</script>";
    }
}
?>