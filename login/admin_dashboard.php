<?php
session_start();
include('../conexion_base_datos/db_connection.php');

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['id_administrador'])) {
    header("Location: /login/login.php"); // Ruta ajustada a login.php en la carpeta raíz
    exit();
}

// Cerrar sesión si se ha presionado el botón
if (isset($_POST['logout'])) {
    // Destruir todas las variables de sesión
    session_unset();
    // Destruir la sesión
    session_destroy();
    // Redirigir al login
    header("Location: /login/login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador - Inventario de Computadores</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Panel de Administrador - Inventario de Computadores</h1>
            <!-- Formulario para cerrar sesión -->
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
                <th>Acciones</th>
                <th>Opciones</th>
            </tr>
            <?php
            // Consulta para recuperar datos iniciales
            $query = "SELECT id_computador, nombre, procesador, ram, almacenamiento, sistema_operativo, estado, fecha_adquisicion, fecha_salida, ubicacion, acciones FROM computadores";

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
                <a class='edit' href='../edit_computer.php?id=".$row['id_computador']."'>
                    <img src='../img/entorno.png' alt='Editar' width='20' height='20'>
                </a> | 
                <a class='delete' href='../delete_computer.php?id=".$row['id_computador']."'>
                    <img src='../img/borrar.png' alt='Eliminar' width='20' height='20'>
                </a> | 
                <a class='voucher' href='../voucher_etc/voucher.php?id=".$row['id_computador']."'>Voucher</a>
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
