<?php
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente = $_POST['documento'];
    $documento = $_POST['docu_medico'];
    $descripcion = $_POST['descripcion'];
    $diagnostico = $_POST['diagnostico'];

    // Validar datos si es necesario

    $sql = "INSERT INTO histo_clinica (documento, docu_medico, descripcion, diagnostico) VALUES (:documento, :docu_medico, :descripcion, :diagnostico)";
    $stmt = $con->prepare($sql);
    
    if ($stmt) {
        $stmt->bindParam(':documento', $paciente);
        $stmt->bindParam(':docu_medico', $documento);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':diagnostico', $diagnostico);
        
        if ($stmt->execute()) {
            // Actualizar el estado de la cita a "Atendido"
            $sql_update = "UPDATE citas SET id_estado = 11 WHERE documento = :documento";
            $stmt_update = $con->prepare($sql_update);
            $stmt_update->bindParam(':documento', $paciente);
            $stmt_update->execute();

            // Mostrar la alerta después de la redirección
            echo '<script>setTimeout(function() { alert("HISTORIA CLINICA GUARDADA EXITOSAMENTE"); }, 500);</script>';
            echo '<script>window.location="autorizar_medicamentos.php"</script>';
        } else {
            echo "Error al guardar la historia clínica: " . $stmt->errorInfo()[2];
        }

    } else {
        echo "Error en la preparación de la consulta: " . $con->errorInfo()[2];
    }
} else {
    echo "Método de solicitud no válido.";
}

// Después de guardar la historia clínica, redirigir a autorizar_medicamentos.php con los parámetros del documento del paciente y del médico
$documento_paciente = $_POST['documento'];
$documento_medico = $_POST['docu_medico']; // Obtener el documento del médico
header("Location: autorizar_medicamentos.php?documento=$documento_paciente&docu_medico=$documento_medico");
exit();
?>