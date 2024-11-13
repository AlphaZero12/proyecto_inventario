<?php
session_start(); // Asegúrate de que la sesión esté iniciada
include('conexion_base_datos/db_connection.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login/login.php");
    exit();
}

// Recuperar el término de búsqueda
if (isset($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']);
    // Consulta para recuperar datos
    $query = "SELECT id_computador, nombre, procesador, ram, almacenamiento, sistema_operativo, estado, fecha_adquisicion, fecha_salida, ubicacion, acciones FROM computadores WHERE nombre LIKE '%$searchTerm%'";

    // Ejecutar consulta y verificar resultados
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id_computador']."</td>";
            echo "<td>".$row['nombre']."</td>";
            echo "<td>".$row['procesador']."</td>";
            echo "<td>".$row['ram']."</td>";
            echo "<td>".$row['almacenamiento']."</td>";
            echo "<td>".$row['sistema_operativo']."</td>";
            echo "<td>".$row['estado']."</td>";
            echo "<td>".$row['fecha_adquisicion']."</td>";
            echo "<td>".$row['fecha_salida']."</td>";
            echo "<td>".$row['ubicacion']."</td>";
            echo "<td>".$row['acciones']."</td>";
            echo "<td>
                    <a class='edit' href='edit_computer.php?id=".$row['id_computador']."'>Editar</a> | 
                    <a class='delete' href='delete_computer.php?id=".$row['id_computador']."'>Eliminar</a> | 
                    <a class='boucher' href='./boucher_etc/boucher.php?id=".$row['id_computador']."'>Boucher</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='12'>No se encontraron registros.</td></tr>";
    }

    // Cerrar la conexión
    $conn->close();
}
?>
