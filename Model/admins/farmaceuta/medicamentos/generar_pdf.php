<?php
session_start();
require_once("../../../../db/connection.php");
require_once('../../../../vendor/tecnickcom/tcpdf/tcpdf.php');

$conexion = new Database();
$con = $conexion->conectar();

// Crear una instancia de TCPDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar el documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Reporte de Medicamentos');
$pdf->SetSubject('Reporte generado automáticamente');
$pdf->SetKeywords('TCPDF, PDF, reporte, usuarios');

// Ajustar el tamaño de la página y orientación
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

// Añadir una página
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Reporte de Medicamentos', 0, 1, 'C');

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
                    <th>Tipo de Medicamento</th>
                    <th>Cantidad Unidad</th>
                    <th>Medida Cantidad</th>
                    <th>Laboratorio</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Código de Barras</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';

$consulta = $con->prepare("SELECT medicamentos.*, tipo_medicamento.clasificacion, laboratorio.laboratorio, estados.estado
                          FROM medicamentos
                          JOIN tipo_medicamento ON medicamentos.id_cla = tipo_medicamento.id_cla
                          JOIN laboratorio ON medicamentos.id_lab = laboratorio.id_lab
                          JOIN estados ON medicamentos.id_estado = estados.id_estado
                          ORDER BY nombre ASC");
$consulta->execute();

while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>
                <td>' . $fila['nombre'] . '</td>
                <td>' . $fila['clasificacion'] . '</td>
                <td>' . $fila['cantidad'] . '</td>
                <td>' . $fila['medida_cant'] . '</td>
                <td>' . $fila['laboratorio'] . '</td>
                <td>' . $fila['f_vencimiento'] . '</td>
                <td>
                    <img src="../../desarrollador/medicamentos/images/' . $fila['codigo_barras'] . '.png" alt="Código de barras">
                    <br>' . $fila['codigo_barras'] . '
                </td>
                <td>' . $fila['estado'] . '</td>
              </tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y generar el PDF
$pdf->lastPage();
$pdf->Output('reporte_medicamentos.pdf', 'D');
?>
