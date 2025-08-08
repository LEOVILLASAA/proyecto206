<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../../config/conexion.php'; // Archivo de conexión a la base de datos
include '../../partials/header.php'; // Incluir encabezado

// Consultar las ventas de la base de datos con un filtro opcional
$query = "SELECT v.id, c.nombre AS cliente, v.fecha, p.nombre AS producto, v.cantidad, v.total, u.nombre AS usuario
          FROM ventas v
          INNER JOIN productos p ON v.producto_id = p.id
          INNER JOIN clientes c ON v.cliente_id = c.id
          INNER JOIN usuarios u ON v.usuario_id = u.id";

// Verificar si se aplicaron filtros de fecha
if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $query .= " WHERE v.fecha BETWEEN '$start_date' AND '$end_date'";
}

$query .= " ORDER BY v.fecha DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Título -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #dc3545; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-file-invoice-dollar" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado rojo -->
            Reporte de Ventas
        </h2>
    </div>

    <!-- Botón de Retorno a la Página de Reportes -->
    <div class="d-flex justify-content-start mb-4">
        <a href="reportes.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Reportes
        </a>
    </div>

    <!-- Formulario de Filtro de Fechas -->
    <form method="GET" action="reporte_ventas.php" class="row mb-4">
        <div class="col-md-4">
            <input type="date" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>" placeholder="Fecha de Inicio">
        </div>
        <div class="col-md-4">
            <input type="date" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>" placeholder="Fecha de Fin">
        </div>
        <div class="col-md-4">
            <!-- Botón de Filtrar con el mismo color que el título -->
            <button type="submit" class="btn btn-danger w-100">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </div>
    </form>

    <!-- Tabla de Reporte de Ventas -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['cliente']}</td>
                                <td>{$row['usuario']}</td>
                                <td>{$row['fecha']}</td>
                                <td>{$row['producto']}</td>
                                <td>{$row['cantidad']}</td>
                                <td>$" . number_format($row['total'], 2) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No se encontraron ventas para el rango de fechas seleccionado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../../partials/footer.php'; // Incluir pie de página
?>
