<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Incluir el encabezado y la conexión a la base de datos
include '../../config/conexion.php';
include '../../partials/header.php';

// Configurar la zona horaria
date_default_timezone_set('America/Lima');

// Consultar las ventas mensuales agrupadas por mes
$ventasMensuales = [];
for ($i = 1; $i <= 12; $i++) {
    $mes = str_pad($i, 2, '0', STR_PAD_LEFT);
    $queryMes = "SELECT SUM(total) as total FROM ventas WHERE DATE_FORMAT(fecha, '%m') = '$mes' AND DATE_FORMAT(fecha, '%Y') = YEAR(CURDATE())";
    $resultadoMes = mysqli_query($conn, $queryMes);
    $filaMes = mysqli_fetch_assoc($resultadoMes);
    $ventasMensuales[] = $filaMes['total'] ?? 0;
}

// Consultar las ventas realizadas hoy
$queryHoy = "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha) = CURDATE()";
$resultadoHoy = mysqli_query($conn, $queryHoy);
$ventasHoy = mysqli_fetch_assoc($resultadoHoy)['total'] ?? 0;

?>

<div class="container mt-5">
    <!-- Encabezado Personalizado -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #343a40; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-chart-line" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Módulo de Reportes y Estadísticas
        </h2>
    </div>

    <!-- Botón de Retorno al Dashboard -->
    <div class="d-flex justify-content-start mb-4">
        <a href="../../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>

    <p class="lead text-center mb-5">Visualiza y genera reportes detallados de ventas, compras e inventario.</p>

    <!-- Contenedor de Tarjetas de Reportes -->
    <div class="row text-center">

        <!-- Reporte de Ventas -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title">Reporte de Ventas</h5>
                    <p class="card-text">Genera reportes de ventas por fechas, clientes y productos vendidos.</p>
                    <a href="reporte_ventas.php" class="btn btn-outline-danger">Ver Reporte</a>
                </div>
            </div>
        </div>

        <!-- Reporte de Compras -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-file-invoice fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Reporte de Compras</h5>
                    <p class="card-text">Genera reportes de compras realizadas a proveedores y productos adquiridos.</p>
                    <a href="reporte_compras.php" class="btn btn-outline-primary">Ver Reporte</a>
                </div>
            </div>
        </div>

        <!-- Reporte de Inventario -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-boxes fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Reporte de Inventario</h5>
                    <p class="card-text">Visualiza el estado del inventario de productos y genera reportes de stock.</p>
                    <a href="reporte_inventario.php" class="btn btn-outline-success">Ver Reporte</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Contenedor de Gráficas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas Generales</h5>
                    <canvas id="chartVentas" width="400" height="200"></canvas>
                    <p class="card-text">Visualiza las estadísticas de ventas en un gráfico dinámico. Ventas de Hoy: S/ <?php echo number_format($ventasHoy, 2); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para Gráficos con Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('chartVentas').getContext('2d');
    var chartVentas = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Ventas Mensuales (S/)',
                data: <?php echo json_encode($ventasMensuales); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 0, 0, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(40, 167, 69, 1)',
                    'rgba(102, 51, 153, 1)',
                    'rgba(255, 87, 34, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#333', // Color del texto de las etiquetas del eje Y
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    ticks: {
                        color: '#333', // Color del texto de las etiquetas del eje X
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
</script>

<?php include '../../partials/footer.php'; ?>
