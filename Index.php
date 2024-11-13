<?php
session_start(); // Asegúrate de que la sesión esté iniciada
include('conexion_base_datos/db_connection.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login/login.php");
    exit();
}

// Manejo de cierre de sesión
if (isset($_POST['logout'])) {
    session_destroy(); // Destruir la sesión
    header("Location: login/login.php"); // Redirigir al inicio de sesión
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Computadores</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Inventario de Computadores</h1>
            <form class="logout-form" method="POST" action="">
                <input type="submit" name="logout" value="Cerrar Sesión">
            </form>
        </div>

        <!-- Formulario de búsqueda -->
        <form class="search-form" method="GET" action="">
            <input type="text" id="search" name="search" placeholder="Buscar computador" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <input type="submit" value="Buscar">
        </form>

        <a class="add-button" href="añadir_computador.php">Añadir nuevo computador</a>

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
                            <a class='edit' href='edit_computer.php?id=".$row['id_computador']."'>Editar</a> | 
                            <a class='delete' href='delete_computer.php?id=".$row['id_computador']."'>Eliminar</a> | 
                            <a class='voucher' href='./voucher_etc/voucher.php?id=".$row['id_computador']."'>Voucher</a>
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

    <!-- Script para búsqueda en tiempo real -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Función para realizar la búsqueda en tiempo real
        $('#search').on('keyup', function() {
            var searchTerm = $(this).val();
            if (searchTerm != '') {
                // Realizamos la petición AJAX
                $.ajax({
                    url: 'search_computers.php', // Archivo PHP que maneja la búsqueda
                    method: 'GET',
                    data: { search: searchTerm },
                    success: function(response) {
                        $('#results-table').html(response); // Actualizar la tabla con los resultados
                    }
                });
            } else {
                // Si no hay término de búsqueda, vaciar la tabla
                $('#results-table').html('<tr><td colspan="12">No se encontraron registros.</td></tr>');
            }
        });
    </script>
</body>
</html>
