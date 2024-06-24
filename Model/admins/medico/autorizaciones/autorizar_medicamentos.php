<?php

date_default_timezone_set('America/Bogota'); // Ajusta la zona horaria según tu ubicación

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
<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['term'])) {
  

    try {
        $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $term = "%" . $_GET['term'] . "%";

        $medicamentos_sql = "SELECT id_medicamento, nombre FROM medicamentos WHERE id_medicamento LIKE :term OR nombre LIKE :term";
        $medicamentos_stmt = $con->prepare($medicamentos_sql);
        $medicamentos_stmt->bindParam(':term', $term);
        $medicamentos_stmt->execute();
        $medicamentos = $medicamentos_stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($medicamentos);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $con = null;
    exit();
}
?>
<?php
if ((isset($_POST['validar']) && $_POST['MM_insert'] == "formreg")) {
    $cod_auto = $_POST['cod_autorizacion'];
    $id_cita = $_POST['id_cita'];
    $id_medicamento_json = $_POST['medicamentos']; // JSON con los IDs de los medicamentos
    $fecha_venc = $_POST['fecha_venc'];
    $documento_paciente = $_POST['documento'];
    $documento_medico = $_POST['docu_medico'];
    $fecha = $_POST['fecha'];    

    // Verificar si el JSON recibido es una cadena
    if (is_array($id_medicamento_json)) {
        // Convertir el array de medicamentos a una cadena JSON
        $id_medicamento_json = json_encode($id_medicamento_json);
    }

    // Agregar el estado autorizado (13)
    $id_estado = 13;

    $sql = "INSERT INTO autorizaciones (cod_auto, id_cita, documento, docu_medico, medicamento, fecha_venc, fecha, id_estado) 
            VALUES (:cod_auto, :id_cita, :documento, :docu_medico, :id_medicamento_json, :fecha_venc, :fecha, :id_estado)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':cod_auto', $cod_auto);
    $stmt->bindParam(':id_cita', $id_cita);
    $stmt->bindParam(':documento', $documento_paciente);
    $stmt->bindParam(':docu_medico', $documento_medico);
    $stmt->bindParam(':id_medicamento_json', $id_medicamento_json); // Usamos la variable con la cadena JSON
    $stmt->bindParam(':fecha_venc', $fecha_venc);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':id_estado', $id_estado);

    if ($stmt->execute()) {
        echo "<script>alert('Autorización guardada exitosamente.'); window.location.href='index_automedicam.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->errorInfo()[2];
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorización Médica</title>
    <link rel="stylesheet" href="css/autorizar_medicamentos.css">
</head>
<body>
    <div class="col-md-6">
      
            <input type="hidden" name="id_cita" value="<?php echo $_GET['id_cita']; ?>">
            <input type="hidden" name="documento" value="<?php echo $_GET['documento']; ?>">
            <input type="hidden" name="docu_medico" value="<?php echo $_GET['docu_medico']; ?>">
            <button type="submit" class="btn-regresar">Regresar</button>
        </form>
    </div>
    <div class="container">
        <h1>Autorización de Medicamentos</h1>
        <form method="post" name="form1" id="form1" autocomplete="off"> 
    
            <div class="form-group">
                <input type="hidden" id="id_cita" name="id_cita" class="form-control" value="<?php echo $_GET['id_cita']; ?>">
            </div>
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <h4 id="fecha"><?php echo date('Y-m-d'); ?></h4>
                <input type="hidden" id="fecha" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="documento">Documento del Paciente:</label>
                <h4 id="fecha"><?php echo $documento_paciente; ?></h4>
                <input type="hidden" id="documento" name="documento" value="<?php echo $documento_paciente; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="docu_medico">Documento del Médico:</label>
                <h4 id="fecha"><?php echo $documento_medico; ?></h4>
                <input type="hidden" id="docu_medico" name="docu_medico" value="<?php echo $documento_medico; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="id_medicamento">Medicamento:</label>
                <input type="hidden" id="id_medicamento" name="id_medicamento" class="form-control">
                <div id="myModal" class="modal">
                    <div class="form-group">
                        <div class="mb-3">
                            <input type="text" id="search" class="form-control" placeholder="Buscar por nombre...">
                            <div id="searchResults" class="list-group"></div>
                        </div>
                        <div class="scrollable-div">
                            <table class="table table-bordered" style="display: none;">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Documento</th>
                                        <th>Nombre</th>
                                        <th>Presentacion</th>
                                    </tr>
                                </thead>
                                <tbody id="userTable">
                                    <?php
                                    // Código PHP para cargar las filas
                                    $empresa = $_SESSION['nit'];
                                    $consulta = "SELECT * FROM medicamentos";  
                                    $resultado = $con->query($consulta);

                                    while ($fila = $resultado->fetch()) {
                                        echo "<tr>";
                                        echo "<td>" . $fila["id_medicamento"] . "</td>";
                                        echo "<td>" . $fila["nombre"] . "</td>";
                                        echo "<td>" . $fila["presentacion"] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group mt-4">
                            <label for="presentacion">Lista de medicamentos seleccionados</label>
                            <table id="selectedMedicamentos" class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="header">ID</th>
                                        <th class="header">Nombre</th>
                                        <th class="header">Presentacion</th>
                                        <th class="header">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se agregarán dinámicamente las filas de medicamentos seleccionados -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="cod_autorizacion">Código de la Autorización:</label>
                <input type="text" id="cod_autorizacion" name="cod_autorizacion" required maxlength="3" onkeypress="validateNumberInput(event)" onblur="checkCodeExists()">
            </div>
            <div class="form-group">
    <!-- <label for="cod_autorizacion">Cadena de texto</label> -->
    <input type="hidden" id="cadenaMedicamentos" name="medicamentos">
</div>

            <div class="form-group">
                <label for="fecha_venc">Fecha de Vencimiento:</label>
                <h4 id="fecha_venc"><?php echo date('Y-m-d', strtotime('+20 days')); ?></h4>
                <input type="hidden" id="fecha_venc" name="fecha_venc" class="form-control" value="<?php echo date('Y-m-d', strtotime('+20 days')); ?>" readonly>
            </div>
            <div class="form-group">
                <input type="submit" name="validar" value="Autorizar">
                <input type="hidden" name="MM_insert" value="formreg">
            </div>
        </form>
    </div>
    <script src="js/autorizar_medicamentos.js"></script>
</body>
</html>
