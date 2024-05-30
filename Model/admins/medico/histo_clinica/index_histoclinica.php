<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();
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

    button.submit-btn {
        width: 102%;
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
        margin-right: 20px; 
    }
</style>
<body>
    <div class="container">
        <h1>Historia Clínica</h1>
        <form action="guardar_historia_clinica.php" method="post">
            <div class="form-group">
                <label for="documento_paciente">Documento del Paciente:</label>
                <input type="text" id="documento_paciente" name="documento_paciente" required>
            </div>
            <div class="form-group">
                <label for="documento_medico">Documento del Medico:</label>
                <input type="text" id="documento_medico" name="documento_medico" required>
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
        <form action="../../desarrollador/modulomedico.php">
            <input type="submit" value="Regresar" class="btn-regresar"/>
        </form>
    </div>
</body>
</html>

