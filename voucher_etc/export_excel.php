<?php
require '../conexion_base_datos/db_connection.php';
require '../proyecto_composer/vendor/autoload.php';  // Asegúrate de tener la ruta correcta de PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$id = $_GET['id'];
$query = "SELECT * FROM computadores WHERE id_computador = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Crear el documento Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Título
$sheet->setCellValue('A1', 'Inventario de Computadores');
$sheet->setCellValue('A2', 'ID Computador:');
$sheet->setCellValue('B2', $row['id_computador']);
$sheet->setCellValue('A3', 'Nombre:');
$sheet->setCellValue('B3', $row['nombre']);
$sheet->setCellValue('A4', 'Procesador:');
$sheet->setCellValue('B4', $row['procesador']);
$sheet->setCellValue('A5', 'RAM:');
$sheet->setCellValue('B5', $row['ram'] . ' GB');
$sheet->setCellValue('A6', 'Almacenamiento:');
$sheet->setCellValue('B6', $row['almacenamiento'] . ' GB');
$sheet->setCellValue('A7', 'Sistema Operativo:');
$sheet->setCellValue('B7', $row['sistema_operativo']);
$sheet->setCellValue('A8', 'Estado:');
$sheet->setCellValue('B8', $row['estado']);
$sheet->setCellValue('A9', 'Ubicación:');
$sheet->setCellValue('B9', $row['ubicacion']);
$sheet->setCellValue('A10', 'Acciones:');
$sheet->setCellValue('B10', $row['acciones']);

// Enviar el archivo Excel para descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Boucher.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
?>
