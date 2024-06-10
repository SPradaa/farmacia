<?php
session_start();
require_once("../../../../db/connection.php");
require '../../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$conexion = new Database();
$con = $conexion->conectar();

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de la tabla
$headers = [
    'Nombre', 'Tipo de Medicamento', 'Cantidad Unidad', 'Medida Cantidad', 'Laboratorio', 
    'Fecha de Vencimiento', 'Código de Barras', 'Estado'
];

// Añadir los encabezados a la primera fila
$columnIndex = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($columnIndex . '1', $header);
    $columnIndex++;
}

// Consulta a la base de datos
$consulta = $con->prepare("SELECT medicamentos.*, tipo_medicamento.clasificacion, laboratorio.laboratorio, estados.estado
                          FROM medicamentos
                          JOIN tipo_medicamento ON medicamentos.id_cla = tipo_medicamento.id_cla
                          JOIN laboratorio ON medicamentos.id_lab = laboratorio.id_lab
                          JOIN estados ON medicamentos.id_estado = estados.id_estado
                          ORDER BY nombre ASC");
$consulta->execute();

// Añadir datos de la base de datos
$rowIndex = 2; // Comenzar en la segunda fila
while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $sheet->setCellValue('A' . $rowIndex, $fila['nombre']);
    $sheet->setCellValue('B' . $rowIndex, $fila['clasificacion']);
    $sheet->setCellValue('C' . $rowIndex, $fila['cantidad']);
    $sheet->setCellValue('D' . $rowIndex, $fila['medida_cant']);
    $sheet->setCellValue('E' . $rowIndex, $fila['laboratorio']);
    $sheet->setCellValue('F' . $rowIndex, $fila['f_vencimiento']);
    $sheet->setCellValue('G' . $rowIndex, $fila['codigo_barras']);
    $sheet->setCellValue('H' . $rowIndex, $fila['estado']);

    // Añadir la imagen del código de barras
    $barcodeImagePath = '../../desarrollador/medicamentos/images/' . $fila['codigo_barras'] . '.png';
    if (file_exists($barcodeImagePath)) {
        $drawing = new Drawing();
        $drawing->setName('Código de Barras');
        $drawing->setDescription('Código de Barras');
        $drawing->setPath($barcodeImagePath); // Path to barcode image
        $drawing->setHeight(100); // Ajusta el tamaño de la imagen según sea necesario
        $drawing->setWidth(250); // Ajusta el ancho de la imagen según sea necesario
        $drawing->setCoordinates('G' . $rowIndex);
        $drawing->setOffsetX(10); // Offset horizontal
        $drawing->setOffsetY(10); // Offset vertical
        $drawing->setWorksheet($sheet);
        
        // Ajustar la altura de la fila para que se ajuste a la imagen
        $sheet->getRowDimension($rowIndex)->setRowHeight(40);
    } else {
        // Opcional: añade un marcador de posición o deja la celda vacía si la imagen no existe
        $sheet->setCellValue('G' . $rowIndex, 'Imagen no disponible');
    }

    $rowIndex++;
}

// Ajustar manualmente el ancho de la columna G para que se ajuste a las imágenes
$sheet->getColumnDimension('G')->setWidth(40);

// Ajustar automáticamente el ancho de las demás columnas para que se ajusten al contenido
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
$sheet->getColumnDimension('H')->setAutoSize(true);

// Establecer encabezados para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');

// Crear el archivo Excel y enviarlo al navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
