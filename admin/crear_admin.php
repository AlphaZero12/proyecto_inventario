<?php
include('../conexion_base_datos/db_connection.php');

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT); // Encriptar contraseña
    $fecha_creacion = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual

    // Validación de los campos
    if (empty($nombre) || empty($apellido) || empty($email) || empty($contrasena)) {
        echo "<p>Por favor, complete todos los campos.</p>";
    } else {
        // Asegurarse de que la consulta SQL es válida
        $query = "INSERT INTO administradores (nombre, apellido, email, contrasena, fecha_creacion) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        // Verificar si la consulta se preparó correctamente
        if ($stmt === false) {
            echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
        } else {
            $stmt->bind_param("sssss", $nombre, $apellido, $email, $contrasena, $fecha_creacion);

            if ($stmt->execute()) {
                echo "<p>Administrador creado exitosamente.</p>";
                // Redirigir al login después de crear el administrador
                header("Location: ../login/login.php");
                exit(); // Asegurarse de que el script termine aquí
            } else {
                echo "<p>Error al crear administrador: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Administrador</title>
    <link rel="stylesheet" href="../css/crea_admin_vista.css">
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Administrador</h1>
        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
            
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            
            <button type="submit">Crear Administrador</button>
        </form>
    </div>
</body>
</html>
