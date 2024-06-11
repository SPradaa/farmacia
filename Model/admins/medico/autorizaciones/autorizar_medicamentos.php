<?php
session_start();
require_once("../../../../db/connection.php");
$conexion = new Database();
$con = $conexion->conectar();

// Verificar si el documento del médico está presente en la URL
if (isset($_GET['docu_medico'])) {
    $documento_medico = $_GET['docu_medico'];

    // Consultar datos del médico
    $sql_medico = "SELECT docu_medico FROM medicos WHERE docu_medico = :docu_medico";
    $stmt_medico = $con->prepare($sql_medico);
    $stmt_medico->bindParam(':docu_medico', $documento_medico);
    $stmt_medico->execute();
    $medico = $stmt_medico->fetch(PDO::FETCH_ASSOC);
}

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
    $sql_autorizaciones = "SELECT cod_auto, fecha, docu_medico, id_medicamento, presentacion, cantidad, fecha_venc FROM autorizaciones WHERE documento = :documento";
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
$medicamentos_sql = "SELECT id_medicamento, nombre, cantidad FROM medicamentos";
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
        <form action="autorizar.php" method="post" onsubmit="return validarCantidad()">
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
                <label for="cod_autorizacion">Código de la Autorización:</label>
                <input type="text" id="cod_autorizacion" name="cod_autorizacion" required maxlength="3" onkeypress="validateNumberInput(event)" onblur="checkCodeExists()">
            </div>
            <div class="form-group">
                <label for="id_medicamento">Medicamento:</label>
                <select id="id_medicamento" name="id_medicamento" required>
                    <option value="">Seleccione un medicamento</option>
                    <?php foreach ($medicamentos as $medicamento): ?>
                        <option value="<?php echo $medicamento['id_medicamento']; ?>" data-cantidad="<?php echo $medicamento['cantidad']; ?>"><?php echo $medicamento['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="presentacion">Presentación:</label>
                <input type="text" id="presentacion" name="presentacion" required>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="text" id="cantidad" name="cantidad" required pattern="\d*" title="Por favor ingrese solo números">
            </div>
            <div class="form-group">
                <label for="fecha_venc">Fecha de Vencimiento:</label>
                <input type="date" id="fecha_venc" name="fecha_venc" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Autorizar</button>
            </div>
        </form>
    </div>
    <script>
        // Lista de medicamentos con sus presentaciones permitidas
        const presentacionesPermitidas = {
            '1': ['tableta'],
            '2': ['tableta'],
            '3': ['tableta', 'jarabe']
            // Agrega más medicamentos y presentaciones según sea necesario
        };

        // Función para validar la presentación basada en el medicamento seleccionado
        function validarPresentacion() {
            const medicamento = document.getElementById('id_medicamento').value;
            const presentacion = document.getElementById('presentacion').value.toLowerCase();

            if (medicamento && presentacion) {
                const presentaciones = presentacionesPermitidas[medicamento] || [];
                if (!presentaciones.includes(presentacion)) {
                    alert('Presentación no válida para el medicamento seleccionado.');
                    document.getElementById('presentacion').value = '';
                }
            }
        }

        // Agregar el evento change al campo select del medicamento
        document.getElementById('id_medicamento').addEventListener('change', function() {
            document.getElementById('presentacion').value = '';
        });

        // Agregar el evento blur al campo de presentación para validar la presentación
        document.getElementById('presentacion').addEventListener('blur', validarPresentacion);

        function validateNumberInput(event) {
            const input = event.target;
            const value = input.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos
            input.value = value;
        }

        // Función para verificar si el código ya existe en la base de datos
        function checkCodeExists() {
            const codigo_autorizacion = document.getElementById('codigo_autorizacion').value;

            // Realizar una solicitud AJAX para verificar el código
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'verificar_codigo.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = xhr.responseText;
                        if (response === 'existe') {
                            alert('El código de autorización ya existe. Por favor, ingrese otro código.');
                            document.getElementById('codigo_autorizacion').value = '';
                        }
                    }
                }
            };
            xhr.send('codigo_autorizacion=' + encodeURIComponent(codigo_autorizacion));
        }
        function validarCantidad() {
            const cantidad = document.getElementById('cantidad').value;
            const selectMedicamento = document.getElementById('id_medicamento');
            const cantidadDisponible = selectMedicamento.selectedOptions[0].getAttribute('data-cantidad');

            if (isNaN(cantidad) || parseInt(cantidad) <= 0) {
                alert('Por favor, ingrese un valor numérico mayor a cero en el campo Cantidad.');
                return false;
            }

            if (parseInt(cantidad) > parseInt(cantidadDisponible)) {
                alert('La cantidad ingresada no puede ser mayor a la cantidad disponible (' + cantidadDisponible + ').');
                return false;
            }

            return true;
        }

        document.getElementById('cantidad').addEventListener('input', function() {
            const cantidad = this.value;
            const selectMedicamento = document.getElementById('id_medicamento');
            const cantidadDisponible = selectMedicamento.selectedOptions[0].getAttribute('data-cantidad');

            if (parseInt(cantidad) > parseInt(cantidadDisponible)) {
                alert('La cantidad ingresada no puede ser mayor a la cantidad disponible (' + cantidadDisponible + ').');
                this.value = '';
            }
        });
    </script>
</body>
</html>
