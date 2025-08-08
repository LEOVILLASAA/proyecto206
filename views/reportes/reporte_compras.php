<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../../config/conexion.php'; // Archivo de conexión a la base de datos
include '../../partials/header.php'; // Incluir encabezado

// Consultar las compras de la base de datos con un filtro opcional
$query = "SELECT c.id, p.nombre AS proveedor, prod.nombre AS producto, c.cantidad, c.costo, c.fecha
          FROM compras c
          INNER JOIN proveedores p ON c.proveedor_id = p.id
          INNER JOIN productos prod ON c.producto_id = prod.id";

// Verificar si se aplicaron filtros de fecha
if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $query .= " WHERE c.fecha BETWEEN '$start_date' AND '$end_date'";
}

$query .= " ORDER BY c.fecha DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #007bff; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-file-invoice" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado azul -->
            Reporte de Compras
        </h2>
    </div>

    <!-- Botón de Retorno a la Página de Reportes -->
    <div class="d-flex justify-content-start mb-4">
        <a href="reportes.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Reportes
        </a>
    </div>

    <!-- Formulario de Filtro de Fechas -->
    <form method="GET" action="reporte_compras.php" class="row mb-4">
        <div class="col-md-4">
            <input type="date" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>" placeholder="Fecha de Inicio">
        </div>
        <div class="col-md-4">
            <input type="date" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>" placeholder="Fecha de Fin">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <!-- Tabla de Reporte de Compras -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID Compra</th>
                    <th>Proveedor</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo Unitario</th>
                    <th>Costo Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $costo_total = $row['cantidad'] * $row['costo'];
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['proveedor']}</td>
                                <td>{$row['producto']}</td>
                                <td>{$row['cantidad']}</td>
                                <td>$" . number_format($row['costo'], 2) . "</td>
                                <td>$" . number_format($costo_total, 2) . "</td>
                                <td>{$row['fecha']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No se encontraron compras para el rango de fechas seleccionado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../../partials/footer.php'; // Incluir pie de página
?>
