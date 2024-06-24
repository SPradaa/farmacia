<?php
session_start();
require_once("../../../db/connection.php");
require_once('../../../vendor/autoload.php'); // Incluir el autoload de Composer para PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conexion = new Database();
$con = $conexion->conectar();

// Asegúrate de que el usuario haya iniciado sesión
if (!isset($_SESSION['documento'])) {
    die("Usuario no autenticado.");
}

$user_document = $_SESSION['documento'];

// Modificar la consulta para seleccionar todas las citas independientemente del estado
$sentencia_select = $con->prepare("
    SELECT citas.id_cita, usuarios.nombre, usuarios.apellido, citas.fecha, citas.hora, medicos.nombre_comple, 
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

// Crear una nueva instancia de PhpSpreadsheet
$spreadsheet = new Spreadsheet();

// Seleccionar la hoja activa
$sheet = $spreadsheet->getActiveSheet();

// Definir los encabezados de las columnas
$sheet->setCellValue('A1', 'Nombre')
      ->setCellValue('B1', 'Apellido')
      ->setCellValue('C1', 'Fecha')
      ->setCellValue('D1', 'Hora')
      ->setCellValue('E1', 'Médico')
      ->setCellValue('F1', 'Especialización')
      ->setCellValue('G1', 'Estado');

// Fila inicial para los datos
$row = 2;

// Llenar la hoja con los datos de las citas
foreach ($resultado as $fila) {
    $sheet->setCellValue('A' . $row, $fila['nombre'])
          ->setCellValue('B' . $row, $fila['apellido'])
          ->setCellValue('C' . $row, $fila['fecha'])
          ->setCellValue('D' . $row, $fila['hora'])
          ->setCellValue('E' . $row, $fila['nombre_comple'])
          ->setCellValue('F' . $row, $fila['especializacion'])
          ->setCellValue('G' . $row, $fila['estado']);
    $row++;
}

// Encabezado de respuesta para descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_citas.xlsx"');
header('Cache-Control: max-age=0');

// Crear un Writer de Excel y enviar el archivo al navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
