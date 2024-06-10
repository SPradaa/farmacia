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
$pdf->SetTitle('Historial Clínico');
$pdf->SetSubject('Historial Clínico');
$pdf->SetKeywords('TCPDF, PDF, historial, clínico');

// Ajustar el tamaño de la página y orientación
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

// Añadir una página
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Historial Clínico', 0, 1, 'C');

// Obtener datos del historial clínico
$consulta = $con->prepare("SELECT histo_clinica.*, 
                                  usuarios.documento AS doc_usuario, usuarios.nombre AS nombre_usuario, usuarios.apellido AS apellido_usuario, usuarios.telefono AS telefono_usuario, usuarios.correo AS correo_usuario, usuarios.direccion AS direccion_usuario,
                                  medicos.nombre_comple AS nombre_medico, medicos.telefono AS telefono_medico, medicos.correo AS correo_medico, 
                                  t_documento.tipo AS tipo_doc,
                                  especializacion.especializacion
                           FROM histo_clinica 
                           JOIN usuarios ON histo_clinica.documento = usuarios.documento 
                           JOIN medicos ON histo_clinica.docu_medico = medicos.docu_medico
                           JOIN t_documento ON usuarios.id_doc = t_documento.id_doc 
                           JOIN especializacion ON medicos.id_esp = especializacion.id_esp
                           WHERE histo_clinica.documento = :documento");
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
        <tr><th>Tipo de Documento</th><td>' . htmlspecialchars($fila['tipo_doc']) . '</td></tr>
        <tr><th>Documento</th><td>' . htmlspecialchars($fila['doc_usuario']) . '</td></tr>
        <tr><th>Nombre</th><td>' . htmlspecialchars($fila['nombre_usuario']) . '</td></tr>
        <tr><th>Apellido</th><td>' . htmlspecialchars($fila['apellido_usuario']) . '</td></tr>
        <tr><th>Teléfono</th><td>' . htmlspecialchars($fila['telefono_usuario']) . '</td></tr>
        <tr><th>Correo</th><td>' . htmlspecialchars($fila['correo_usuario']) . '</td></tr>
        <tr><th>Dirección</th><td>' . htmlspecialchars($fila['direccion_usuario']) . '</td></tr>
    </table>
    </div>';

    $html .= '<div class="seccion">
    <h3>Datos de la Historia Clínica</h3>
    <table>
        <tr><th>Descripción</th><td>' . htmlspecialchars($fila['descripcion']) . '</td></tr>
        <tr><th>Diagnóstico</th><td>' . htmlspecialchars($fila['diagnostico']) . '</td></tr>
    </table>
    </div>';

    $html .= '<div class="seccion">
    <h3>Datos del Médico</h3>
    <table>
        <tr><th>Nombre</th><td>' . htmlspecialchars($fila['nombre_medico']) . '</td></tr>
        <tr><th>Especialización</th><td>' . htmlspecialchars($fila['especializacion']) . '</td></tr>
        <tr><th>Teléfono</th><td>' . htmlspecialchars($fila['telefono_medico']) . '</td></tr>
        <tr><th>Correo</th><td>' . htmlspecialchars($fila['correo_medico']) . '</td></tr>
    </table>
    </div>';

    $pdf->writeHTML($html, true, false, true, false, '');
} else {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'No se encontraron datos para el documento especificado.', 0, 1, 'C');
}

// Cerrar y generar el PDF
$pdf->lastPage();
$pdf->Output('historial_clinico.pdf', 'D');
?>
``
