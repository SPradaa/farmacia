<?php
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_auto = $_POST['cod_autorizacion'];
    $id_medicamento = $_POST['id_medicamento'];
    $presentacion = $_POST['presentacion'];
    $cantidad = $_POST['cantidad'];
    $fecha_venc = $_POST['fecha_venc'];
    $documento_paciente = $_POST['documento'];
    $documento_medico = $_POST['docu_medico'];
    $fecha = $_POST['fecha'];

    // Validar que el código de autorización sea un número de 3 dígitos
    if (strlen($cod_auto) !== 3 || !is_numeric($cod_auto)) {
        echo "<script>alert('Por favor, ingrese un código de autorización válido de 3 dígitos.'); window.location.href='autorizacion_form.php';</script>";
        exit();
    }

    // Verificar si el código de autorización ya existe
    $checkCodeQuery = "SELECT * FROM autorizaciones WHERE cod_auto = :cod_auto";
    $stmt = $con->prepare($checkCodeQuery);
    $stmt->bindParam(':cod_auto', $cod_auto);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Redirigir al formulario anterior con los mismos datos
        $url = 'autorizacion_form.php?documento=' . urlencode($documento_paciente) . '&docu_medico=' . urlencode($documento_medico);
        echo "<script>alert('El código de autorización ya existe. Por favor, ingrese otro código.'); window.location.href='autorizar_medicamentos.php?docu_medico=$documento_medico&documento=$documento_paciente';</script>";
        exit();
    }

    // Verificar si la presentación corresponde al medicamento seleccionado
    $allowedPresentations = [
        '1' => ['tableta'],
        '2' => ['tableta'],
        '3' => ['tableta', 'jarabe']
    ];

    if (!in_array(strtolower($presentacion), $allowedPresentations[$id_medicamento])) {
        echo "<script>alert('Presentación no válida para el medicamento seleccionado.'); window.location.href='autorizacion_form.php';</script>";
        exit();
    }

    // Verificar que la cantidad no sea mayor a la cantidad disponible
    $query = "SELECT cantidad FROM medicamentos WHERE id_medicamento = :id_medicamento";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_medicamento', $id_medicamento);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $cantidad_disponible = $row['cantidad'];

    if ($cantidad > $cantidad_disponible) {
        echo "<script>alert('La cantidad ingresada no puede ser mayor a la cantidad disponible ($cantidad_disponible).'); window.location.href='autorizacion_form.php';</script>";
        exit();
    }

    // Agregar el estado autorizado (13)
    $id_estado = 13;

    $sql = "INSERT INTO autorizaciones (cod_auto, id_medicamento, presentacion, cantidad, fecha_venc, documento, docu_medico, fecha, id_estado) 
            VALUES (:cod_auto, :id_medicamento, :presentacion, :cantidad, :fecha_venc, :documento, :docu_medico, :fecha, :id_estado)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':cod_auto', $cod_auto);
    $stmt->bindParam(':id_medicamento', $id_medicamento);
    $stmt->bindParam(':presentacion', $presentacion);
    $stmt->bindParam(':cantidad', $cantidad);
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
