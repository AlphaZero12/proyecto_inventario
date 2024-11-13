<?php
// Iniciar sesión
session_start();
include('./conexion_base_datos/db_connection.php');

// Verificar si el usuario es un administrador
if (!isset($_SESSION['id_administrador'])) {
    header("Location: ../login/login.php");
    exit();
}

// Verificar si se ha proporcionado el ID del computador
if (isset($_GET['id'])) {
    $id_computador = $_GET['id'];

    // Preparar la consulta para eliminar el registro
    $query = "DELETE FROM computadores WHERE id_computador = ?";
    
    // Preparar la declaración SQL
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_computador);
    
    // Ejecutar la declaración y verificar si se realizó con éxito
    if ($stmt->execute()) {
        // Redirigir de vuelta al panel de administrador con mensaje de éxito
        header("Location: ../login/admin_dashboard.php?message=Computador eliminado exitosamente");
    } else {
        echo "Error al eliminar el computador: " . $conn->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo "ID de computador no proporcionado.";
}

// Cerrar la conexión
$conn->close();
?>
