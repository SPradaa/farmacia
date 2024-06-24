<?php
session_start();
require_once("../../../../db/connection.php");
require '../../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conexion = new Database();
$con = $conexion->conectar();

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de la tabla
$headers = [
    'Tipo de Documento', 'Documento', 'Nombre', 'Apellido', 'Correo', 
    'Teléfono', 'Ciudad', 'Dirección', 'Tipo de Sangre', 
    'Tipo de Usuario', 'Estado'
];

// Añadir los encabezados a la primera fila
$columnIndex = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($columnIndex . '1', $header);
    $columnIndex++;
}

// Consulta a la base de datos
$consulta = $con->prepare("SELECT usuarios.*, t_documento.tipo, municipios.municipio, rh.rh, roles.rol, estados.estado
                          FROM usuarios
                          JOIN t_documento ON usuarios.id_doc = t_documento.id_doc
                          JOIN municipios ON usuarios.id_municipio = municipios.id_municipio
                          JOIN rh ON usuarios.id_rh = rh.id_rh
                          JOIN roles ON usuarios.id_rol = roles.id_rol
                          JOIN estados ON usuarios.id_estado = estados.id_estado
                          ORDER BY nombre ASC");
$consulta->execute();

// Añadir datos de la base de datos
$rowIndex = 2; // Comenzar en la segunda fila
while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $sheet->setCellValue('A' . $rowIndex, $fila['tipo']);
    $sheet->setCellValue('B' . $rowIndex, $fila['documento']);
    $sheet->setCellValue('C' . $rowIndex, $fila['nombre']);
    $sheet->setCellValue('D' . $rowIndex, $fila['apellido']);
    $sheet->setCellValue('E' . $rowIndex, $fila['correo']);
    $sheet->setCellValue('F' . $rowIndex, $fila['telefono']);
    $sheet->setCellValue('G' . $rowIndex, $fila['municipio']);
    $sheet->setCellValue('H' . $rowIndex, $fila['direccion']);
    $sheet->setCellValue('I' . $rowIndex, $fila['rh']);
    $sheet->setCellValue('J' . $rowIndex, $fila['rol']);
    $sheet->setCellValue('K' . $rowIndex, $fila['estado']);
    $rowIndex++;
}

// Establecer encabezados para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');

// Crear el archivo Excel y enviarlo al navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
