<?php
session_start();
include('../conexion_base_datos/db_connection.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    // Evitar redirigir si ya estamos en voucher.php
    if (basename($_SERVER['PHP_SELF']) !== 'voucher.php') {
        header("Location: ../voucher_etc/voucher.php");
        exit();
    }
}

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
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

// Información adicional
$fecha = date("Y-m-d H:i:s");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Voucher de la Aplicación</title>
    <link rel="stylesheet" href="../css/voucher_vs.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .voucher {
            border: 1px solid #000;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
        p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        
        /* Estilos específicos para la impresión */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .voucher {
                max-width: 100%;
                width: auto;
                padding: 10px;
                page-break-before: auto;
                page-break-after: auto;
            }

            /* Evitar el salto de página innecesario */
            .voucher-content {
                page-break-inside: avoid;
            }

            .footer {
                display: none; /* Ocultar los botones durante la impresión */
            }

            /* Ajustar el tamaño del contenido para que quepa en una página */
            @page {
                size: A4; /* Opción para tamaño A4, si es necesario */
                margin: 10mm;
            }
        }
    </style>
    <script>
        function printVoucher() {
            // Ocultar los botones de exportación y el botón de impresión antes de imprimir
            document.getElementById('printButton').style.display = 'none';
            document.getElementById('exportWordButton').style.display = 'none';
            document.getElementById('exportPdfButton').style.display = 'none';
            document.getElementById('exportExcelButton').style.display = 'none';
            
            // Iniciar la impresión
            window.print();
        }
    </script>
</head>

<body>
    <div class="voucher">
        <h1>Inventario de Computadores</h1>
        <div class="voucher-content">
            <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
            <p><strong>ID Computador:</strong> <?php echo $row['id_computador']; ?></p>
            <p><strong>Nombre:</strong> <?php echo isset($row['nombre']) ? $row['nombre'] : 'No disponible'; ?></p>
            <p><strong>Procesador:</strong> <?php echo isset($row['procesador']) ? $row['procesador'] : 'No disponible'; ?></p>
            
            <p><strong>RAM:</strong> <?php echo isset($row['ram']) ? $row['ram'] : 'No disponible'; ?> <?php echo isset($row['unidad_ram']) ? $row['unidad_ram'] : 'GB'; ?></p>
            <p><strong>Almacenamiento:</strong> <?php echo isset($row['almacenamiento']) ? $row['almacenamiento'] : 'No disponible'; ?> <?php echo isset($row['unidad_almacenamiento']) ? $row['unidad_almacenamiento'] : ''; ?></p>
            
            <p><strong>Sistema Operativo:</strong> <?php echo isset($row['sistema_operativo']) ? $row['sistema_operativo'] : 'No disponible'; ?></p>
            <p><strong>Estado:</strong> <?php echo isset($row['estado']) ? $row['estado'] : 'No disponible'; ?></p>
            <p><strong>Ubicación:</strong> <?php echo isset($row['ubicacion']) ? $row['ubicacion'] : 'No disponible'; ?></p>
            <p><strong>Acciones:</strong> <?php echo isset($row['acciones']) ? $row['acciones'] : 'No disponible'; ?></p>
        </div>
        
        <div class="footer">
            <button id="printButton" onclick="printVoucher()">Imprimir Voucher</button>
            <button id="exportWordButton" onclick="window.location.href='export_word.php?id=<?php echo $id; ?>'">Exportar a Word</button>
            <button id="exportPdfButton" onclick="window.location.href='export_pdf.php?id=<?php echo $id; ?>'">Exportar a PDF</button>
            <button id="exportExcelButton" onclick="window.location.href='export_excel.php?id=<?php echo $id; ?>'">Exportar a Excel</button>
        </div>
    </div>
</body>
</html>
