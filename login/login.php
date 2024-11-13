<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Habilitar la visualización de errores

include('../conexion_base_datos/db_connection.php'); // Ajusta la ruta de conexión

// Verificar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $contrasena = $_POST['contrasena'];

    // Preparar la consulta SQL para verificar tanto en la tabla de usuarios como en la de administradores
    $sql = "
        SELECT id_usuario, contrasena, 'usuario' AS tipo_usuario FROM usuarios WHERE email = ? 
        UNION
        SELECT id_administrador, contrasena, 'administrador' AS tipo_usuario FROM administradores WHERE email = ?
    ";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("ss", $email, $email); // Usamos dos veces el mismo parámetro para ambas tablas
    $stmt->execute();
    $stmt->store_result();

    // Verificar si el usuario existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario_administrador, $hash_contrasena, $tipo_usuario);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($contrasena, $hash_contrasena)) {
            // Iniciar sesión según el tipo de usuario (usuario normal o administrador)
            $_SESSION['email'] = $email;
            if ($tipo_usuario == 'usuario') {
                $_SESSION['id_usuario'] = $id_usuario_administrador;
                $_SESSION['rol'] = 'user'; // Asignar rol de usuario
                header("Location: user_dashboard.php"); // Redirigir a la página del usuario
            } else {
                $_SESSION['id_administrador'] = $id_usuario_administrador;
                $_SESSION['rol'] = 'admin'; // Asignar rol de administrador
                header("Location: admin_dashboard.php"); // Redirigir al panel de administración
            }
            exit(); // Asegúrate de salir después de la redirección
        } else {
            echo "<p style='color: red;'>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p style='color: red;'>El usuario no existe.</p>";
    }

    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/pantalla_de_login.css"> <!-- Si tienes un CSS específico para estilos -->
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form method="POST" action="">
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>

        <input type="submit" value="Iniciar Sesión">
    </form>

    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>.</p> <!-- Enlace para registrar un nuevo usuario -->
</body>
</html>
