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

    // Validar los datos recibidos si es necesario
    if (empty($id_cita) || empty($fecha) || empty($documento) || empty($docu_medico) || empty($descripcion) || empty($diagnostico)) {
        echo "Por favor, complete todos los campos.";
        exit;
    }

    // Preparar la consulta SQL
    $sql = "INSERT INTO histo_clinica (id_cita, fecha, documento, docu_medico, descripcion, diagnostico) VALUES (:id_cita, :fecha, :documento, :docu_medico, :descripcion, :diagnostico)";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        // Vincular los parámetros y ejecutar la consulta
        $stmt->bindParam(':id_cita', $id_cita);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':docu_medico', $docu_medico);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->execute();

        // Comprobar si se insertaron filas
        if ($stmt->rowCount() > 0) {
            // Mostrar mensaje de éxito
            echo "Historia clínica guardada exitosamente.";
            
            // Redirigir a la siguiente página
            header("Location: autorizar_medicamentos.php?id_cita=$id_cita&documento=$documento&docu_medico=$docu_medico");
            exit();
        } else {
            echo "Error al guardar la historia clínica.";
        }
    } else {
        echo "Error en la preparación de la consulta: " . $con->errorInfo()[2];
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
