<?php

date_default_timezone_set('America/Bogota'); // Ajusta la zona horaria según tu ubicación

session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

// Verificar si el id_cita se proporciona en la URL
if(isset($_GET['id_cita'])) {
    $id_cita = $_GET['id_cita'];
} else {
    // Manejar el caso en el que no se proporciona un id_cita
    echo "ID de cita no proporcionado.";
    exit;
}

// Obtener el documento del paciente
if (isset($_SESSION['documento'])) {
    $documento = $_SESSION['documento'];
} elseif (isset($_GET['documento'])) {
    $documento = $_GET['documento'];
} else {
    // Manejar el caso en el que no se proporciona el documento ni en la sesión ni en los parámetros GET
    echo "Documento del paciente no encontrado.";
    exit;
}

// Verificar si el documento del médico está presente en la URL
if (isset($_GET['docu_medico'])) {
    $docu_medico = $_GET['docu_medico'];

    // Consultar datos del médico
    $sql_medico = "SELECT docu_medico FROM medicos WHERE docu_medico = :docu_medico";
    $stmt_medico = $con->prepare($sql_medico);
    $stmt_medico->bindParam(':docu_medico', $docu_medico);
    $stmt_medico->execute();
    $medico = $stmt_medico->fetch(PDO::FETCH_ASSOC);
}

$documento = $_GET['documento'];
$docu_medico = $_GET['docu_medico'];


// Consulta para obtener la información del paciente asociada al id_cita
$sql = "SELECT c.documento, u.nombre 
        FROM citas c 
        JOIN usuarios u ON c.documento = u.documento 
        WHERE c.id_cita = :id_cita";
$stmt = $con->prepare($sql);
$stmt->bindParam(':id_cita', $id_cita);
$stmt->execute();
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $documento = $row['documento'];
    $nombre = $row['nombre'];
} else {
    // Manejar el caso en el que no se encuentra la cita
    echo "Cita no encontrada.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Clínica</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f0f8ff;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 50%;
        margin: 100px auto;
        padding: 40px;
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    select, textarea, input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    input[readonly] {
        background-color: #e9e9e9;
    }

    button.submit-btn {
        width: 100%;
        padding: 10px;
        background-color: #046bcc;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    button.submit-btn:hover {
        background-color: #0056b3;
    }

    .btn-regresar {
    background-color: #046bcc;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    position: absolute;
    top: 20px;
    left: 20px;
    text-decoration: none;
    display: inline-block;
}
</style>
<body>
        <div class="container">
        <a href="index_automedicam.php" class="btn-regresar">Regresar</a>
        <h1>Historia Clínica</h1>
        <form action="guardar_historia_clinica.php" method="post">
            <div class="form-group">
                <input type="hidden" id="id_cita" name="id_cita" class="form-control" value="<?php echo $_GET['id_cita']; ?>">
            </div>
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <h3 id="fecha"><?php echo date('Y-m-d'); ?></h3>
                <input type="hidden" id="fecha" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="documento">Documento del Paciente</label>
                <h3 id="documento"><?php echo $documento; ?></h3>
                <input type="hidden" id="documento" name="documento" value="<?php echo $documento; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <h3 id="nombre"><?php echo $nombre; ?></h3>
                <input type="hidden" id="nombre" name="nombre" value="<?php echo $nombre; ?>" readonly>
            </div>

            <div class="form-group">
                <input type="hidden" id="docu_medico" name="docu_medico" value="<?php echo $docu_medico; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="diagnostico">Diagnóstico:</label>
                <textarea id="diagnostico" name="diagnostico" rows="4"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn">Guardar Historia Clínica</button>
            </div>
        </form>
    </div>
    </form>
    </div>
</body>
</html>
