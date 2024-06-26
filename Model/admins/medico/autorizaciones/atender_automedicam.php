<?php
// session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

require_once("../../../../controller/seg.php");
validarSesion();


// Suponiendo que el documento del paciente está guardado en la sesión
if (isset($_SESSION['documento'])) {
    $documento = $_SESSION['documento'];
} else {
    // Manejar el caso en el que no exista el documento en la sesión
    $documento = "Documento no encontrado";
}

$paciente = $_GET['documento'];

// Suponiendo que tienes una tabla de pacientes en tu base de datos
// y que puedes obtener el nombre del paciente a partir de su documento
$sql = "SELECT nombre FROM usuarios WHERE documento = :documento";
$stmt = $con->prepare($sql);
$stmt->bindParam(':documento', $paciente);
$stmt->execute();
$nombre = "Nombre no encontrado"; // Valor por defecto si no se encuentra el nombre
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $nombre = $row['nombre'];
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
        background-color: #007bff;
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
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }

    .btn-regresar:hover {
        background-color: #0056b3;
    }

    .left-align {
        float: left;
        margin: 20px 0 0 20px; 
    }
</style>
<body>
    <div class="container">
        <h1>Historia Clínica</h1>
        <form action="guardar_historia_clinica.php" method="post">
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="text" id="fecha" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="documento">Documento del Paciente</label>
                <h3 id="documento"><?php echo $paciente; ?></h3>
                <input type="hidden" id="documento" name="documento" value="<?php echo $paciente; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <h3 id="nombre"><?php echo $nombre; ?></h3>
                <input type="hidden" id="nombre" name="nombre" value="<?php echo $nombre; ?>" readonly>
            </div>

            <div class="form-group">
                <input type="hidden" id="docu_medico" name="docu_medico" value="<?php echo $documento; ?>" readonly>
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
    <div class="left-align">
        <form action="../../medico/autorizaciones/index_automedicam.php">
            <input type="submit" value="Regresar" class="btn-regresar"/>
        </form>
    </div>
</body>
</html>
