<?php
session_start();
require_once("../../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

// Verificar si el documento del paciente está presente en la URL
if (isset($_GET['docu_medico'])) 
    $documento_medico = $_GET['docu_medico'];

    // Consultar datos del paciente
    $sql_medico = "SELECT docu_medico FROM medicos WHERE docu_medico = :docu_medico";
    $stmt_medico = $con->prepare($sql_medico);
    $stmt_medico->bindParam(':docu_medico', $documento_medico);
    $stmt_medico->execute();
    $medico = $stmt_medico->fetch(PDO::FETCH_ASSOC);

// Verificar si el documento del paciente está presente en la URL
if (isset($_GET['documento'])) {
    $documento_paciente = $_GET['documento'];

    // Consultar datos del paciente
    $sql_paciente = "SELECT nombre, apellido FROM usuarios WHERE documento = :documento";
    $stmt_paciente = $con->prepare($sql_paciente);
    $stmt_paciente->bindParam(':documento', $documento_paciente);
    $stmt_paciente->execute();
    $paciente = $stmt_paciente->fetch(PDO::FETCH_ASSOC);


    // Consultar citas del paciente
    $sql_citas = "SELECT id_cita, fecha, hora, docu_medico FROM citas WHERE documento = :documento";
    $stmt_citas = $con->prepare($sql_citas);
    $stmt_citas->bindParam(':documento', $documento_paciente);
    $stmt_citas->execute();
    $citas = $stmt_citas->fetchAll(PDO::FETCH_ASSOC);

    // Consultar historias clínicas del paciente
    $sql_historias = "SELECT id_histo, docu_medico, descripcion, diagnostico FROM histo_clinica WHERE documento = :documento";
    $stmt_historias = $con->prepare($sql_historias);
    $stmt_historias->bindParam(':documento', $documento_paciente);
    $stmt_historias->execute();
    $historias = $stmt_historias->fetchAll(PDO::FETCH_ASSOC);

    // Consultar autorizaciones del paciente
    $sql_autorizaciones = "SELECT cod_auto, fecha, docu_medico, nit, id_medicamento, presentacion, cantidad, fecha_hora_auto, fecha_venc FROM autorizaciones WHERE documento = :documento";
    $stmt_autorizaciones = $con->prepare($sql_autorizaciones);
    $stmt_autorizaciones->bindParam(':documento', $documento_paciente);
    $stmt_autorizaciones->execute();
    $autorizaciones = $stmt_autorizaciones->fetchAll(PDO::FETCH_ASSOC);

} else {
    // Manejar el caso en el que no se haya proporcionado el documento del paciente
    $documento_paciente = "Documento no encontrado";
    $paciente = ["nombre" => "Nombre no encontrado", "apellido" => "Apellido no encontrado"];
    $citas = [];
    $historias = [];
    $autorizaciones = [];
}

// Consultar datos de médicos y empresas
$medicos_sql = "SELECT docu_medico, nombre_comple FROM medicos";
$medicos_stmt = $con->prepare($medicos_sql);
$medicos_stmt->execute();
$medicos = $medicos_stmt->fetchAll(PDO::FETCH_ASSOC);


// Consultar datos de medicamentos
$medicamentos_sql = "SELECT id_medicamento, nombre FROM medicamentos";
$medicamentos_stmt = $con->prepare($medicamentos_sql);
$medicamentos_stmt->execute();
$medicamentos = $medicamentos_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorización Médica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="date"], input[type="datetime-local"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .col-md-6 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="col-md-6">
        <form action="../../../admins/desarrollador/autorizaciones/atender_automedicam.php">
            <input type="submit" value="Regresar" class="btn btn-secondary"/>
        </form>
    </div>
    <div class="container">
        <h1>Autorización de Medicamentos</h1>
        <form action="#" method="post">
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="text" id="fecha" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="documento">Documento del Paciente:</label>
                <input type="text" id="documento" name="documento" value="<?php echo $documento_paciente; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="docu_medico">Documento del Médico:</label>
                <input type="text" id="docu_medico" name="docu_medico" value="<?php echo $documento_medico; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="codigo_autorizacion">Código de la Autorización:</label>
                <input type="text" id="codigo_autorizacion" name="codigo_autorizacion" required>
            </div>
            <div class="form-group">
                <label for="medicamento">Medicamento:</label>
                <select id="medicamento" name="medicamento" required>
                    <?php foreach ($medicamentos as $medicamento): ?>
                        <option value="<?php echo $medicamento['id_medicamento']; ?>"><?php echo $medicamento['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="presentacion">Presentación:</label>
                <input type="text" id="presentacion" name="presentacion" required>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="text" id="cantidad" name="cantidad" required>
            </div>
            <div class="form-group">
                <label for="fecha_reclamo">Fecha y Hora para Reclamar:</label>
                <input type="datetime-local" id="fecha_reclamo" name="fecha_reclamo" required>
            </div>
            <div class="form-group">
                <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Autorizar</button>
            </div>
        </form>
    </div>
</body>
</html>
