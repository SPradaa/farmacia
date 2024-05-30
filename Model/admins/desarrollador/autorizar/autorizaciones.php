<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['autorizar'])) {
    $docu_medico = $_POST['documento_paciente'];

    // Actualizar el estado de la cita a 'atendido' en la base de datos
    $sql = $con->prepare("UPDATE citas SET id_estado = (SELECT id_estado FROM estados WHERE estado = 'activo') WHERE docu_medico = ?");
    $sql->execute([$docu_medico]);

    // Verificar si se realizó la actualización correctamente
    $rowCount = $sql->rowCount();
    if ($rowCount > 0) {
        echo '<script> alert("Cita autorizada exitosamente");</script>';
        echo '<script> window.close(); </script>';
    } else {
        echo '<script> alert("No se encontró ninguna cita con el documento del médico proporcionado");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorización de Cita</title>
    <link rel="stylesheet" href="styles.css">
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

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Autorización</h1>
        <form method="POST">
            <div class="form-group">
                <label for="documento_paciente">Documento del Médico:</label>
                <input type="text" id="documento_paciente" name="documento_paciente" required>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn" name="autorizar">Autorizar</button>
            </div>
        </form>
    </div>
</body>
</html>
