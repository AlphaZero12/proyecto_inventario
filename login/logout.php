<?php
session_start(); // Iniciar sesión
session_destroy(); // Destruir la sesión
header("Location: login/login.php"); // Redirigir al formulario de inicio de sesión
exit();
?>
