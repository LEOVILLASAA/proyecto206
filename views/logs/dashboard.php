<?php
require_once '../models/Database.php';
require_once '../models/Producto.php';
require_once '../models/Venta.php';

$database = new Database();
$db = $database->getConnection();

// Obtener el número de productos
$producto = new Producto($db);
$total_productos = $producto->leerProductos()->rowCount();

// Obtener el número de ventas
$venta = new Venta($db);
$total_ventas = $venta->leerVentas()->rowCount();

// Obtener datos de los productos más vendidos
$query = "SELECT p.nombre, SUM(v.cantidad) as total_vendido 
          FROM ventas v 
          LEFT JOIN productos p ON v.producto_id = p.id 
          GROUP BY p.nombre 
          ORDER BY total_vendido DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$productos_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Dashboard</h1>
    <div class="stats">
        <div class="stat">
            <h2>Total de Productos</h2>
            <p><?php echo $total_productos; ?></p>
        </div>
        <div class="stat">
            <h2>Total de Ventas</h2>
            <p><?php echo $total_ventas; ?></p>
        </div>
    </div>

    <h2>Productos Más Vendidos</h2>
    <canvas id="productosVendidosChart"></canvas>

    <script>
        const labels = <?php echo json_encode(array_column($productos_vendidos, 'nombre')); ?>;
        const data = {
            labels: labels,
            datasets: [{
                label: 'Productos más vendidos',
                data: <?php echo json_encode(array_column($productos_vendidos, 'total_vendido')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };
        var myChart = new Chart(
            document.getElementById('productosVendidosChart'),
            config
        );
    </script>
</body>
</html>
