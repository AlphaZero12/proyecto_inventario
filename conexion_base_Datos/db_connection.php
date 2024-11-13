<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'inventario_computadores';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
