<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Habilitar la visualización de errores

include('../conexion_base_datos/db_connection.php'); // Ajusta la ruta según sea necesario

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $tipo_usuario = $_POST['tipo_usuario']; // Obtener el tipo de usuario seleccionado

    // Verificar si las contraseñas coinciden
    if ($contrasena !== $confirmar_contrasena) {
        echo "<p style='color: red;'>Las contraseñas no coinciden.</p>";
    } else {
        // Verificar si el correo ya está registrado
        $sql = "SELECT id_usuario FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<p style='color: red;'>El correo electrónico ya está registrado.</p>";
        } else {
            // Hashear la contraseña
            $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

            // Preparar la consulta SQL para insertar el nuevo usuario
            $sql_insert = "INSERT INTO usuarios (nombre, apellido, email, contrasena, is_admin) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssi", $nombre, $apellido, $email, $hash_contrasena, $tipo_usuario);

            if ($stmt_insert->execute()) {
                if ($tipo_usuario == 1) {
                    echo "<p style='color: green;'>Administrador registrado exitosamente. Puedes iniciar sesión ahora.</p>";
                } else {
                    echo "<p style='color: green;'>Usuario registrado exitosamente. Puedes iniciar sesión ahora.</p>";
                }
                // Redirigir al login después de la creación
                header("Location: login.php");
                exit(); // Asegurarse de que el script termine aquí
            } else {
                echo "<p style='color: red;'>Error al registrar el usuario: " . $stmt_insert->error . "</p>";
            }
            $stmt_insert->close();
        }

        $stmt->close();
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="../css/registro.css"> <!-- Ajusta la ruta según sea necesario -->
</head>
<body>
    <form method="POST" action="">
        <h1>Registrar Usuario</h1>
        
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required>
        
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>
        
        <label for="confirmar_contrasena">Confirmar Contraseña:</label>
        <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required>

        <!-- Opción para seleccionar si el usuario es administrador -->
        <label for="tipo_usuario">Tipo de Usuario:</label>
        <select name="tipo_usuario" id="tipo_usuario" onchange="checkAdmin()">
            <option value="0">Usuario Regular</option>
            <option value="1">Administrador</option>
        </select>

        <input type="submit" value="Registrar Usuario">
    </form>

    <p>¿Ya tienes una cuenta? <a href="../login/login.php">Inicia sesión aquí</a>.</p>

    <script>
        // Redirigir al formulario de administrador si se selecciona Administrador
        function checkAdmin() {
            var tipoUsuario = document.getElementById('tipo_usuario').value;
            if (tipoUsuario == '1') {
                // Redirigir al formulario específico de administrador
                window.location.href = '../admin/crear_admin.php';
            }
        }
    </script>
</body>
</html>
