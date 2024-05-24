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
        input[type="text"], input[type="date"], textarea {
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
    </style>
</head>
<body>
<div class="col-md-6">
            <form action="../../../admins/desarrollador/autorizaciones/detalle_automedicam.php">
                <input type="submit" value="Regresar" class="btn btn-secondary"/>
            </form>
        </div>
    <div class="container">
        <h1>Autorización Médica</h1>
        <form action="#" method="post">
            <div class="form-group">
                <label for="nombre_paciente">Nombre del Paciente:</label>
                <input type="text" id="nombre_paciente" name="nombre_paciente" required>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="form-group">
                <label for="documento_identidad">Documento de Identidad:</label>
                <input type="text" id="documento_identidad" name="documento_identidad" required>
            </div>
            <div class="form-group">
                <label for="medico_responsable">Médico Responsable:</label>
                <input type="text" id="medico_responsable" name="medico_responsable" required>
            </div>
            <div class="form-group">
                <label for="fecha_autorizacion">Fecha de Autorización:</label>
                <input type="date" id="fecha_autorizacion" name="fecha_autorizacion" required>
            </div>
            <div class="form-group">
                <label for="descripcion_tratamiento">Descripción del Tratamiento:</label>
                <textarea id="descripcion_tratamiento" name="descripcion_tratamiento" required></textarea>
            </div>
            <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <textarea id="observaciones" name="observaciones"></textarea>
            </div>
            <div class="form-group">
                <label for="firma_paciente">Firma del Paciente:</label>
                <input type="text" id="firma_paciente" name="firma_paciente" required>
            </div>
            <div class="form-group">
                <label for="firma_medico">Firma del Médico:</label>
                <input type="text" id="firma_medico" name="firma_medico" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Autorizar</button>
            </div>
        </form>
    </div>
</body>
</html>