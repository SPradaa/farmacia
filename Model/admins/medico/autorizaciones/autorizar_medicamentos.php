<?php
session_start();
require_once("../../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

// Consultar los datos necesarios de la base de datos
$documento = '12345678'; // Asumiendo que este documento es dinámico y se obtiene de alguna forma

try {
    // Consultar citas
    $stmt = $con->prepare("SELECT fecha, hora FROM citas WHERE documento = $documento");
    $stmt->execute();
    $citas = $stmt->fetch();

    echo $fila['fecha'];

    // Consultar usuario
    $stmt = $con->prepare("SELECT nombre, apellido FROM usuarios WHERE documento = :documento");
    $stmt->execute(['documento' => $documento]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Consultar medicos
    $stmt = $con->query("SELECT docu_medico, nombre_comple FROM medicos");
    $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consultar empresas
    $stmt = $con->query("SELECT nit, empresa FROM empresas");
    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consultar medicamentos
    $stmt = $con->query("SELECT id_medicamento, nombre FROM medicamentos");
    $medicamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

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
        <form action="../../../admins/desarrollador/autorizaciones/detalle_automedicam.php">
            <input type="submit" value="Regresar" class="btn btn-secondary"/>
        </form>
    </div>
    <div class="container">
        <h1>Autorización de Medicamentos</h1>
        <form action="#" method="post">
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="text" id="fecha" name="fecha" class="form-control" value="<?php echo isset($citas['fecha']) ? $citas['fecha'] : ''; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="nombre_paciente">Nombre del Paciente:</label>
            <input type="text" id="nombre_paciente" name="nombre_paciente" class="form-control" value="<?php echo isset($usuario['nombre']) ? $usuario['nombre'] : ''; ?>" readonly>
        </div>
    <div class="form-group">
    <label for="apellido_paciente">Apellido del Paciente:</label>
    <input type="text" id="apellido_paciente" name="apellido_paciente" class="form-control" value="<?php echo isset($usuario['apellido']) ? $usuario['apellido'] : ''; ?>" readonly>
</div>

            <div class="form-group">
                <label for="solicitado_por">Solicitado por (Médico):</label>
                <select id="solicitado_por" name="solicitado_por" required>
                    <?php foreach ($medicos as $medico): ?>
                        <option value="<?php echo $medico['docu_medico']; ?>"><?php echo $medico['nombre_comple']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="expedida_a">Expedida a (Empresa):</label>
                <select id="expedida_a" name="expedida_a" required>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo $empresa['nit']; ?>"><?php echo $empresa['empresa']; ?></option>
                    <?php endforeach; ?>
                </select>
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
