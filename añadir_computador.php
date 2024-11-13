<?php include('conexion_base_datos/db_connection.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Computador</title>
    <link rel="stylesheet" href="css/./añadir_computador.css">
</head>
<body>
    <h1>Añadir Computador</h1>
    <form action="guardar_computador.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="procesador">Procesador:</label>
        <input type="text" name="procesador" required>

        <label for="ram">RAM:</label>
        <input type="number" name="ram" required>
        

        <label for="almacenamiento">Almacenamiento:</label>
        <input type="number" name="almacenamiento" required>
        <select name="unidad_almacenamiento" required>
            <option value="GB">GB</option>
            <option value="TB">TB</option>
        </select>

        <label for="sistema_operativo">Sistema Operativo:</label>
        <input type="text" name="sistema_operativo" required> 

        <label for="estado">Estado:</label>
        <input type="text" name="estado" required>

        <label for="fecha_adquisicion">Fecha de Adquisición:</label>
        <input type="date" name="fecha_adquisicion" required>

        <label for="ubicacion">Ubicación:</label>
        <input type="text" name="ubicacion" required>

        <label for="acciones">Acciones:</label>
        <input type="text" name="acciones" placeholder="Opcional">

        <input type="submit" value="Guardar">
    </form>
</body>
</html>
