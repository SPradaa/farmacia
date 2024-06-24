<?php
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_auto = $_POST['cod_autorizacion'];
    $id_cita = $_POST['id_cita'];
    $id_medicamento_json = $_POST['id_medicamento']; // JSON con los IDs de los medicamentos
    $fecha_venc = $_POST['fecha_venc'];
    $documento_paciente = $_POST['documento'];
    $documento_medico = $_POST['docu_medico'];
    $fecha = $_POST['fecha'];    

    // Convertir el JSON recibido a un array
    $id_medicamentos_array = json_decode($id_medicamento_json, true);

    // Verificar si se pudo decodificar el JSON correctamente
    if (!is_array($id_medicamentos_array)) {
        echo "<script>alert('Error al decodificar el JSON de medicamentos.'); window.location.href='autorizacion_form.php';</script>";
        exit();
    }

    // Convertir el array de medicamentos a una cadena separada por comas
    $id_medicamentos = implode(',', $id_medicamentos_array);

    // Agregar el estado autorizado (13)
    $id_estado = 13;

    $sql = "INSERT INTO autorizaciones (cod_auto, id_cita, id_medicamento, fecha_venc, documento, docu_medico, fecha, id_estado) 
            VALUES (:cod_auto, :id_cita, :id_medicamentos, :fecha_venc, :documento, :docu_medico, :fecha, :id_estado)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':cod_auto', $cod_auto);
    $stmt->bindParam(':id_cita', $id_cita);
    $stmt->bindParam(':id_medicamentos', $id_medicamentos);
    $stmt->bindParam(':fecha_venc', $fecha_venc);
    $stmt->bindParam(':documento', $documento_paciente);
    $stmt->bindParam(':docu_medico', $documento_medico);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':id_estado', $id_estado);

    if ($stmt->execute()) {
        echo "<script>alert('Autorización guardada exitosamente.'); window.location.href='index_automedicam.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->errorInfo()[2];
    }
}
?>
