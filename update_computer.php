<?php
session_start();
include('conexion_base_datos/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar los datos del formulario
    $id_computador = $_POST['id_computador'];
    $nombre = $_POST['nombre'];
    $procesador = $_POST['procesador'];
    $ram = $_POST['ram'];
    $almacenamiento = $_POST['almacenamiento'];
    $sistema_operativo = $_POST['sistema_operativo'];
    $estado = $_POST['estado'];
    $fecha_adquisicion = $_POST['fecha_adquisicion'];
    $ubicacion = $_POST['ubicacion'];
    $acciones = $_POST['acciones'];
    $fecha_salida = $_POST['fecha_salida'];

    // Preparar la consulta SQL para actualizar
    $sql = "UPDATE computadores 
            SET nombre = ?, procesador = ?, ram = ?, almacenamiento = ?, sistema_operativo = ?, estado = ?, 
                fecha_adquisicion = ?, ubicacion = ?, acciones = ?, fecha_salida = ? 
            WHERE id_computador = ?";

    // Vincular parámetros, ajustando la cadena de tipos
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssssssi", $nombre, $procesador, $ram, $almacenamiento, $sistema_operativo, $estado, $fecha_adquisicion, $ubicacion, $acciones, $fecha_salida, $id_computador);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página correspondiente dependiendo del rol del usuario
        if (isset($_SESSION['id_administrador'])) {
            header("Location: ../login/admin_dashboard.php"); // Redirige al panel de administrador
        } else {
            header("Location: ../login/user_dashboard.php"); // Redirige al panel de usuario
        }
        exit(); // Asegurarse de que no se ejecute más código después de redirigir
    } else {
        echo "Error al actualizar el computador: " . $stmt->error;
    }

    // Cerrar la declaración y conexión
    $stmt->close();
}
$conn->close();
?>
