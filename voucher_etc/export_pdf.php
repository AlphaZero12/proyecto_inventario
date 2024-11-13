<?php
require '../conexion_base_datos/db_connection.php';
require '../proyecto_composer/vendor/autoload.php';  // Asegúrate de tener la ruta correcta de TCPDF

$id = $_GET['id'];
$query = "SELECT * FROM computadores WHERE id_computador = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Crear el PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Título
$pdf->Cell(0, 10, 'Inventario de Computadores', 0, 1, 'C');
$pdf->Ln(10); // Salto de línea

// Información del computador
$pdf->Cell(0, 10, "ID Computador: " . $row['id_computador']);
$pdf->Ln(6);
$pdf->Cell(0, 10, "Nombre: " . $row['nombre']);
$pdf->Ln(6);
$pdf->Cell(0, 10, "Procesador: " . $row['procesador']);
$pdf->Ln(6);
$pdf->Cell(0, 10, "RAM: " . $row['ram'] . " GB");
$pdf->Ln(6);
$pdf->Cell(0, 10, "Almacenamiento: " . $row['almacenamiento'] . " GB");
$pdf->Ln(6);
$pdf->Cell(0, 10, "Sistema Operativo: " . $row['sistema_operativo']);
$pdf->Ln(6);
$pdf->Cell(0, 10, "Estado: " . $row['estado']);
$pdf->Ln(6);
$pdf->Cell(0, 10, "Ubicación: " . $row['ubicacion']);
$pdf->Ln(6);
$pdf->Cell(0, 10, "Acciones: " . $row['acciones']);

// Generar el archivo PDF y descargarlo
$pdf->Output('Boucher.pdf', 'D');
exit;
?>
