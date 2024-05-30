<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento_paciente = $_POST['documento_paciente'];
    $documento_medico = $_POST['documento_medico'];
    $id_cita = $_POST['id_cita'];
    $descripcion = $_POST['descripcion'];
    $diagnostico = $_POST['diagnostico'];

    $query = "INSERT INTO histo_clinica (documento, docu_medico, id_cita, descripcion, diagnostico) 
              VALUES (:documento_paciente, :documento_medico, :id_cita, :descripcion, :diagnostico)";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':documento_paciente', $documento_paciente);
    $stmt->bindParam(':documento_medico', $documento_medico);
    $stmt->bindParam(':id_cita', $id_cita);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':diagnostico', $diagnostico);

    if ($stmt->execute()) {
        echo "Historia Clínica guardada exitosamente.";
    } else {
        echo "Error al guardar la Historia Clínica.";
    }
}
?>
