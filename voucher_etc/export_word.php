<?php
require '../conexion_base_datos/db_connection.php';
require '../proyecto_composer/vendor/autoload.php';  // Asegúrate de tener la ruta correcta de PHPWord

use PhpOffice\PhpWord\PhpWord;

$id = $_GET['id'];
$query = "SELECT * FROM computadores WHERE id_computador = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Crear el documento Word
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Título
$section->addText("Inventario de Computadores");
$section->addText("ID Computador: " . $row['id_computador']);
$section->addText("Nombre: " . $row['nombre']);
$section->addText("Procesador: " . $row['procesador']);
$section->addText("RAM: " . $row['ram'] . " GB");
$section->addText("Almacenamiento: " . $row['almacenamiento'] . " GB");
$section->addText("Sistema Operativo: " . $row['sistema_operativo']);
$section->addText("Estado: " . $row['estado']);
$section->addText("Ubicación: " . $row['ubicacion']);
$section->addText("Acciones: " . $row['acciones']);

// Enviar el archivo Word para descarga
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="Boucher.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

$phpWord->save("php://output", "Word2007");
exit;
?>
