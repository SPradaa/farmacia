<?php
session_start();
require_once("../../../../db/connection.php"); 
$conexion = new Database();
$con = $conexion->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['autorizar'])) {
  $documento_medico = $_POST['documento_paciente'];

  // Actualizar la cita en la base de datos
  $sql = $con->prepare("UPDATE citas SET autorizado = 1 WHERE documento_medico = ?");
  $sql->execute([$documento_medico]);

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
<body>
    <!-- Aquí empieza tu formulario HTML -->
    <div class="col-md-6">
        <form action="../modulomedico.php" method="POST"> <!-- Agrega method="POST" y action="../modulomedico.php" -->
            <!-- Agrega un botón de tipo submit para enviar el formulario -->
            <!-- <input type="submit" value="Regresar" class="btn btn-secondary"/> -->
        </form>
    </div>
    <div class="container">
        <h1>Autorización</h1>
        <!-- Inicia el formulario -->
        <form method="POST">
            <div class="form-group">
                <label for="documento_paciente">Documento del Médico:</label>
                <input type="text" id="documento_paciente" name="documento_paciente" required>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn" name="autorizar">Autorizar</button> <!-- Añade name="autorizar" -->
            </div>
        </form> <!-- Cierra el formulario -->
    </div>
</body>
</html>
