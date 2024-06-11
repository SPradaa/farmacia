<?php
require_once("../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

session_start(); // Inicia la sesión para acceder a las variables de sesión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento = $_POST['documento'];
    $farmaceuta_nit = $_SESSION['nit']; // Obtener la EPS del usuario que ha iniciado sesión

    $sql = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
    $sql->bindParam(':documento', $documento);
    $sql->execute();
    $fila = $sql->fetch();

    if ($fila) {
        if ($fila['nit'] == $farmaceuta_nit) {
            echo "existe";
        } else {
            // Obtener el nombre de la EPS del farmaceuta
            $sql_eps_farmaceuta = $con->prepare("SELECT empresa FROM empresas WHERE nit = :nit");
            $sql_eps_farmaceuta->bindParam(':nit', $farmaceuta_nit);
            $sql_eps_farmaceuta->execute();
            $eps_farmaceuta = $sql_eps_farmaceuta->fetch();

            echo "no_afiliado:" . $eps_farmaceuta['empresa'];
        }
    } else {
        echo "no_existe";
    }
}
?>
