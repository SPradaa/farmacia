<?php
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cita = $_POST['id_cita'];
    $fecha = $_POST['fecha'];
    $documento = $_POST['documento'];
    $docu_medico = $_POST['docu_medico'];
    $descripcion = $_POST['descripcion'];
    $diagnostico = $_POST['diagnostico'];

    // Validar datos si es necesario

    $sql = "INSERT INTO histo_clinica (id_cita, fecha, documento, docu_medico, descripcion, diagnostico) VALUES (:id_cita, :fecha, :documento, :docu_medico, :descripcion, :diagnostico)";
    $stmt = $con->prepare($sql);
    
    if ($stmt) {
        $stmt->bindParam(':id_cita', $id_cita);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':docu_medico', $docu_medico);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':diagnostico', $diagnostico);
        
        if ($stmt->execute()) {
            // Actualizar el estado de la cita a "Atendido"
            $sql_update = "UPDATE citas SET id_estado = 11 WHERE id_cita = :id_cita";
            $stmt_update = $con->prepare($sql_update);
            $stmt_update->bindParam(':id_cita', $id_cita);
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
$id_cita = $_POST['id_cita'];
$documento = $_POST['documento'];
$docu_medico = $_POST['docu_medico']; // Obtener el documento del médico
header("Location: autorizar_medicamentos.php?id_cita=$id_cita&documento=$documento&docu_medico=$docu_medico");
exit();
?>