<?php 
include('conexion_base_datos/db_connection.php');

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Obtener los detalles del computador
    $query = "SELECT * FROM computadores WHERE id_computador = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Computador no encontrado.";
        exit;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Computador</title>
    <link rel="stylesheet" href="../css/editar_vista.css">
</head>
<body>
    <h1>Editar Computador</h1>
    <form action="update_computer.php" method="POST">
        <input type="hidden" name="id_computador" value="<?php echo $row['id_computador']; ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" required>

        <label for="procesador">Procesador:</label>
        <input type="text" name="procesador" value="<?php echo $row['procesador']; ?>" required>

        <label for="ram">RAM (GB):</label>
        <input type="number" name="ram" value="<?php echo $row['ram']; ?>" required>

        <label for="almacenamiento">Almacenamiento (GB):</label>
        <input type="number" name="almacenamiento" value="<?php echo $row['almacenamiento']; ?>" required>

        <label for="sistema_operativo">Sistema Operativo:</label>
        <input type="text" name="sistema_operativo" value="<?php echo $row['sistema_operativo']; ?>" required>

        <label for="estado">Estado:</label>
        <input type="text" name="estado" value="<?php echo $row['estado']; ?>" required>

        <label for="fecha_adquisicion">Fecha de Adquisición:</label>
        <input type="date" name="fecha_adquisicion" value="<?php echo $row['fecha_adquisicion']; ?>" required>

        <label for="fecha_salida">Fecha de Salida:</label>
        <input type="date" name="fecha_salida" value="<?php echo $row['fecha_salida']; ?>">

        <label for="ubicacion">Ubicación:</label>
        <input type="text" name="ubicacion" value="<?php echo $row['ubicacion']; ?>" required>

        <label for="acciones">Acciones:</label>
        <input type="text" name="acciones" value="<?php echo $row['acciones']; ?>">

        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
