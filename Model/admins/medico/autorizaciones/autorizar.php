<?php
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_auto = $_POST['cod_auto'];
    $fecha = $_POST['fecha'];
    $usuario = $_POST['documento'];
    $documento = $_POST['docu_medico'];
    $nit = $_POST['nit'];
    $id_medicamento = $_POST['id_medicamento'];
    $presentacion = $_POST['presentacion'];
    $cantidad = $_POST['cantidad'];
    $fecha_hora_auto = $_POST['fecha_hora_auto'];
    $fecha_venc = $_POST['fecha_venc'];

    // Validar datos si es necesario

    $sql = "INSERT INTO autorizaciones (cod_auto, fecha, documento, docu_medico, nit, id_medicamento, presentacion, cantidad, fecha_hora_auto, fecha_venc) VALUES (:cod_auto, :fecha, :documento, :docu_medico, :nit, :id_medicamento, :presentacion, :cantidad, :fecha_hora_auto, :fecha_venc)";
    $stmt = $con->prepare($sql);
    
    if ($stmt) {
        $stmt->bindParam(':cod_auto', $cod_auto);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':documento', $usuario);
        $stmt->bindParam(':docu_medico', $documento);
        $stmt->bindParam(':nit', $nit);
        $stmt->bindParam(':id_medicamento', $id_medicamento);
        $stmt->bindParam(':presentacion', $presentacion);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':fecha_hora_auto', $fecha_hora_auto);
        $stmt->bindParam(':fecha_venc', $fecha_venc);

        
        if ($stmt->execute()) {
            echo '<script> alert("AUTORIZACION GUARDADA EXITOSAMENTE");</script>';
            echo '<script>window.location="index_automedicam.php"</script>';
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