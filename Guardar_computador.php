<?php
include('conexion_base_datos/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar los datos del formulario
    $nombre = $_POST['nombre'];
    $procesador = $_POST['procesador'];
    $ram = $_POST['ram'];
    $almacenamiento = $_POST['almacenamiento'];
    $sistema_operativo = $_POST['sistema_operativo'];
    $estado = $_POST['estado'];
    $fecha_adquisicion = $_POST['fecha_adquisicion'];
    $ubicacion = $_POST['ubicacion'];
    $acciones = $_POST['acciones']; // Nueva columna para acciones

    // Preparar la consulta SQL con la columna acciones
    $sql = "INSERT INTO computadores (nombre, procesador, ram, almacenamiento, sistema_operativo, estado, fecha_adquisicion, ubicacion, acciones) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissssss", $nombre, $procesador, $ram, $almacenamiento, $sistema_operativo, $estado, $fecha_adquisicion, $ubicacion, $acciones);

    if ($stmt->execute()) {
        echo "Computador agregado exitosamente.";
    } else {
        echo "Error al agregar el computador: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
// Redirigir a la página de confirmación o lista de computadores después de guardar
header('Location: ../login/admin_dashboard.php'); 
exit();
?>
