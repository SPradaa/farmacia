<?php
session_start();
require_once("../../../db/connection.php");
require_once('../../../vendor/tecnickcom/tcpdf/tcpdf.php');

$conexion = new Database();
$con = $conexion->conectar();

// Asegúrate de que el usuario haya iniciado sesión
if (!isset($_SESSION['documento'])) {
    die("Usuario no autenticado.");
}

// Verificar si el parámetro 'documento' está presente en la URL
if (!isset($_GET['documento'])) {
    die("Documento no especificado.");
}

$documento = $_GET['documento'];

// Crear una instancia de TCPDF
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar el documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Autorización Médica');
$pdf->SetSubject('Autorización Médica');
$pdf->SetKeywords('TCPDF, PDF, autorización, médica');

// Ajustar el tamaño de la página y orientación
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

// Añadir una página
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Autorización Médica', 0, 1, 'C');

// Obtener datos de la autorización médica
$consulta = $con->prepare("SELECT autorizaciones.*, 
                                  usuarios.documento AS doc_paciente, usuarios.nombre AS nombre_paciente, usuarios.apellido AS apellido_paciente, usuarios.telefono AS telefono_paciente, usuarios.direccion AS direccion_paciente,
                                  medicos.docu_medico, medicos.nombre_comple AS nombre_medico, medicamentos.nombre AS nombre_medicamento
                           FROM autorizaciones 
                           JOIN usuarios ON autorizaciones.documento = usuarios.documento 
                           JOIN medicos ON autorizaciones.docu_medico = medicos.docu_medico
                           JOIN medicamentos ON autorizaciones.id_medicamento = medicamentos.id_medicamento
                           WHERE autorizaciones.documento = :documento");
$consulta->bindParam(':documento', $documento, PDO::PARAM_STR);
$consulta->execute();
$fila = $consulta->fetch(PDO::FETCH_ASSOC);

if ($fila) {
    $html = '<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    .seccion {
        margin-bottom: 30px;
    }
    .seccion h3 {
        background-color: #2dcac1;
        color: #fff;
        padding: 5px;
        margin-top: 0;
        margin-bottom: 10px;
    }
    </style>';

    $html .= '<div class="seccion">
    <h3>Datos del Paciente</h3>
    <table>
        <tr><th>Documento</th><td>' . htmlspecialchars($fila['doc_paciente']) . '</td></tr>
        <tr><th>Nombre</th><td>' . htmlspecialchars($fila['nombre_paciente'] . ' ' . $fila['apellido_paciente']) . '</td></tr>
        <tr><th>Teléfono</th><td>' . htmlspecialchars($fila['telefono_paciente']) . '</td></tr>
        <tr><th>Dirección</th><td>' . htmlspecialchars($fila['direccion_paciente']) . '</td></tr>
    </table>
    </div>';

    $html .= '<div class="seccion">
    <h3>Datos de la Autorización</h3>
    <table>
        <tr><th>Fecha</th><td>' . htmlspecialchars($fila['fecha']) . '</td></tr>
        <tr><th>Medicamento</th><td>' . htmlspecialchars($fila['nombre_medicamento']) . '</td></tr>
        <tr><th>Presentación</th><td>' . htmlspecialchars($fila['presentacion']) . '</td></tr>
        <tr><th>Cantidad</th><td>' . htmlspecialchars($fila['cantidad']) . '</td></tr>
    </table>
    </div>';

    $html .= '<div class="seccion">
    <h3>Datos del Médico</h3>
    <table>
        <tr><th>Documento</th><td>' . htmlspecialchars($fila['docu_medico']) . '</td></tr>
        <tr><th>Nombre</th><td>' . htmlspecialchars($fila['nombre_medico']) . '</td></tr>
    </table>
    </div>';

    // Si se solicita la versión PDF, generamos el PDF y lo descargamos
    $pdf->writeHTML($html, true, false, true, false, '');
} else {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'No se encontraron datos para el documento especificado.', 0, 1, 'C');
}

// Cerrar y generar el PDF
$pdf->lastPage();
$pdf->Output('autorizacion_medica.pdf', 'D');
?>
``
