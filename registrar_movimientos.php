<?php
include 'conexion_base_datos/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $computer_id = $_POST['computer_id'];
    $tipo = $_POST['tipo']; // 'entrada' o 'salida'
    $cantidad = $_POST['cantidad'];

    // Actualizar la cantidad en la tabla de computadoras
    if ($tipo == 'entrada') {
        $sql_update = "UPDATE computadores SET cantidad = cantidad + ? WHERE id_computador = ?";
    } else {
        $sql_update = "UPDATE computadores SET cantidad = cantidad - ? WHERE id_computador = ?";
    }

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ii", $cantidad, $computer_id);

    if ($stmt->execute()) {
        // Registrar el movimiento
        $sql_insert = "INSERT INTO registros (computer_id, tipo, cantidad) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("isi", $computer_id, $tipo, $cantidad);

        if ($stmt_insert->execute()) {
            echo "Movimiento registrado exitosamente.";
        } else {
            echo "Error al registrar el movimiento: " . $conn->error;
        }
        $stmt_insert->close();
    } else {
        echo "Error al actualizar la cantidad: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Movimiento</title>
</head>
<body>
    <h1>Registrar Movimiento de Equipos</h1>
    <form action="registrar_movimiento.php" method="POST">
        <select name="computer_id" required>
            <option value="">Selecciona un equipo</option>
            <?php
            $result = $conn->query("SELECT id_computador, nombre FROM computadores");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id_computador']}'>{$row['nombre']}</option>";
            }
            ?>
        </select>
        <select name="tipo" required>
            <option value="entrada">Entrada</option>
            <option value="salida">Salida</option>
        </select>
        <input type="number" name="cantidad" placeholder="Cantidad" required min="1">
        <button type="submit">Registrar Movimiento</button>
    </form>
</body>
</html>
