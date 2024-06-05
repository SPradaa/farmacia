<?php
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documento = $_POST['documento'];
    $docu_medico = $_POST['docu_medico'];
    $descripcion = $_POST['descripcion'];
    $diagnostico = $_POST['diagnostico'];

    // Validar datos si es necesario

    $sql = "INSERT INTO histo_clinica (documento, docu_medico, descripcion, diagnostico) VALUES (:documento, :docu_medico, :descripcion, :diagnostico)";
    $stmt = $con->prepare($sql);
    
    if ($stmt) {
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':docu_medico', $docu_medico);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':diagnostico', $diagnostico);
        
        if ($stmt->execute()) {
            echo '<script> alert("HISTORIA CLINICA GUARDADA EXITOSAMENTE");</script>';
            echo '<script>window.location="autorizaciones.php"</script>';
        } else {
            echo "Error al guardar la historia clínica: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Error en la preparación de la consulta: " . $con->errorInfo()[2];
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
