<?php
session_start();
require_once("../../../db/connection.php");
require_once('../../../vendor/tecnickcom/tcpdf/tcpdf.php');

$conexion = new Database();
$con = $conexion->conectar();

// Crear una instancia de TCPDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar el documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Reporte de Citas');
$pdf->SetSubject('Reporte generado automáticamente');
$pdf->SetKeywords('TCPDF, PDF, reporte, citas');

// Ajustar el tamaño de la página y orientación
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

// Añadir una página
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Reporte de Citas', 0, 1, 'C');

// Crear la tabla con estilos
$pdf->SetFont('helvetica', '', 10);

$html = '<style>
table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}
th {
    background-color: #B0E0E6; /* Azul celeste */
    color: black;
}
</style>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Médico</th>
                    <th>Especialización</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';

$user_document = $_SESSION['documento'];

// Modificar la consulta para seleccionar todas las citas independientemente del estado
$sentencia_select = $con->prepare("
    SELECT usuarios.nombre, usuarios.apellido, citas.fecha, citas.hora, medicos.nombre_comple, 
    especializacion.especializacion, estados.estado
    FROM citas 
    JOIN usuarios ON citas.documento = usuarios.documento
    JOIN medicos ON citas.docu_medico = medicos.docu_medico 
    JOIN especializacion ON citas.id_esp = especializacion.id_esp 
    JOIN estados ON citas.id_estado = estados.id_estado
    WHERE citas.documento = :user_document
    ORDER BY citas.fecha ASC
");
$sentencia_select->bindParam(':user_document', $user_document, PDO::PARAM_STR);
$sentencia_select->execute();
$resultado = $sentencia_select->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultado as $fila) {
    $html .= '<tr>
                <td>' . $fila['nombre'] . '</td>
                <td>' . $fila['apellido'] . '</td>
                <td>' . $fila['fecha'] . '</td>
                <td>' . $fila['hora'] . '</td>
                <td>' . $fila['nombre_comple'] . '</td>
                <td>' . $fila['especializacion'] . '</td>
                <td>' . $fila['estado'] . '</td>
              </tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y generar el PDF
$pdf->lastPage();
$pdf->Output('reporte_citas.pdf', 'D');
?>
