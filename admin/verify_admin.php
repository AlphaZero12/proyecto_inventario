<?php
session_start();
include('../conexion_base_datos/db_connection.php');

// Obtener los datos enviados por AJAX
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Error en los datos']);
    exit;
}

$email = $data['username'];  // Usa 'username' si estás enviando el correo electrónico
$password = $data['password']; // La contraseña en texto plano enviada por AJAX

// Sanitizar los datos
$email = $conn->real_escape_string($email);
$password = $conn->real_escape_string($password);

// Verificación en la base de datos
$query = "SELECT * FROM administradores WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verificar la contraseña
    if (password_verify($password, $row['contrasena'])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico no encontrado']);
}

// Cerrar la conexión
$conn->close();
?>
