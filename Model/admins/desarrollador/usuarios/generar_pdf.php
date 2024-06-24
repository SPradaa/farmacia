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
$pdf->SetTitle('Reporte de Usuarios');
$pdf->SetSubject('Reporte generado automáticamente');
$pdf->SetKeywords('TCPDF, PDF, reporte, usuarios');

// Ajustar el tamaño de la página y orientación
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

// Añadir una página
$pdf->AddPage();

// Título del reporte
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Reporte de Usuarios', 0, 1, 'C');

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
                    <th>Tipo de Documento</th>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                    <th>Dirección</th>
                    <th>Tipo de Sangre</th>
                    <th>Tipo de Usuario</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';

$consulta = $con->prepare("SELECT usuarios.*, t_documento.tipo, municipios.municipio, rh.rh, roles.rol, estados.estado
                          FROM usuarios
                          JOIN t_documento ON usuarios.id_doc = t_documento.id_doc
                          JOIN municipios ON usuarios.id_municipio = municipios.id_municipio
                          JOIN rh ON usuarios.id_rh = rh.id_rh
                          JOIN roles ON usuarios.id_rol = roles.id_rol
                          JOIN estados ON usuarios.id_estado = estados.id_estado
                          ORDER BY nombre ASC");
$consulta->execute();

while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>
                <td>' . $fila['tipo'] . '</td>
                <td>' . $fila['documento'] . '</td>
                <td>' . $fila['nombre'] . '</td>
                <td>' . $fila['apellido'] . '</td>
                <td>' . $fila['correo'] . '</td>
                <td>' . $fila['telefono'] . '</td>
                <td>' . $fila['municipio'] . '</td>
                <td>' . $fila['direccion'] . '</td>
                <td>' . $fila['rh'] . '</td>
                <td>' . $fila['rol'] . '</td>
                <td>' . $fila['estado'] . '</td>
              </tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y generar el PDF
$pdf->lastPage();
$pdf->Output('reporte_usuarios.pdf', 'D');
?>
