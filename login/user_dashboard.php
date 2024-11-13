<?php
session_start();
include('../conexion_base_datos/db_connection.php');

// Verificar si el usuario ha iniciado sesión y si es un usuario normal
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'user') {
    header("Location: login.php");
    exit();
}

// Si se cerró sesión
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario - Inventario de Computadores</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Panel de Usuario - Inventario de Computadores</h1>
            <form class="logout-form" method="POST" action="">
                <input type="submit" name="logout" value="Cerrar Sesión">
            </form>
        </div>

        <!-- Formulario de búsqueda -->
        <form class="search-form" method="GET" action="">
            <input type="text" id="search" name="search" placeholder="Buscar computador" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <input type="submit" value="Buscar">
        </form>

        <a class="add-button" href="../añadir_computador.php">Añadir nuevo computador</a>

        <table id="results-table">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Procesador</th>
                <th>RAM</th>
                <th>Almacenamiento</th>
                <th>Sistema Operativo</th>
                <th>Estado</th>
                <th>Fecha de Adquisición</th>
                <th>Fecha de Salida</th>
                <th>Ubicación</th>
                <th>Acciones</th> <!-- Nueva columna para acciones -->
                <th>Opciones</th>
            </tr>
            <?php
            // Consulta para recuperar datos iniciales
            $query = "SELECT id_computador, nombre, procesador, ram, almacenamiento, sistema_operativo, estado, fecha_adquisicion, fecha_salida, ubicacion , acciones FROM computadores";

            // Condición de búsqueda
            if (isset($_GET['search'])) {
                $searchTerm = $conn->real_escape_string($_GET['search']);
                $query .= " WHERE id_computador LIKE '%$searchTerm%'"; 
            }

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
                            <a class='voucher' href='../voucher_etc/voucher.php?id=".$row['id_computador']."'>Ver Voucher</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12'>No se encontraron registros.</td></tr>";
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
