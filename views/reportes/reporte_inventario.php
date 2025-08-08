<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Incluir archivos de configuración y encabezado
include '../../models/Database.php'; // Incluir archivo de conexión a la base de datos
include '../../partials/header.php'; // Incluir encabezado

// Crear una nueva instancia de la clase Database y obtener la conexión
$database = new Database();
$conn = $database->getConnection();

// Comprobar conexión a la base de datos
if (!$conn) {
    die("<div class='alert alert-danger text-center'>Error de conexión a la base de datos.</div>");
} else {
    echo "<div class='alert alert-success text-center'>Conexión exitosa a la base de datos.</div>";
}

// Consultar el inventario de la base de datos con un filtro opcional
$query = "SELECT i.id, p.nombre AS producto, i.cantidad, i.tipo_movimiento, i.fecha, u.nombre AS usuario
          FROM inventarios i
          INNER JOIN productos p ON i.producto_id = p.id
          INNER JOIN usuarios u ON i.usuario_id = u.id";

// Verificar si se aplicaron filtros de fecha
if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    echo "<div class='alert alert-info text-center'>Filtrando entre $start_date y $end_date</div>"; // Mensaje de depuración
    $query .= " WHERE i.fecha BETWEEN :start_date AND :end_date";
}

// Preparar la consulta
$stmt = $conn->prepare($query);

// Si se aplicaron filtros, vinculamos los parámetros
if (isset($start_date) && isset($end_date)) {
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
}

// Ejecutar la consulta
$stmt->execute();

// Depuración: Verificar si hay resultados
if ($stmt->rowCount() == 0) {
    echo "<div class='alert alert-warning text-center'>No se encontraron movimientos de inventario.</div>";
}

// Consulta para obtener el stock total de cada producto
$stock_query = "SELECT p.nombre, SUM(CASE WHEN i.tipo_movimiento = 'Entrada' THEN i.cantidad ELSE -i.cantidad END) AS stock
                FROM inventarios i
                INNER JOIN productos p ON i.producto_id = p.id
                GROUP BY p.id";
$stock_stmt = $conn->prepare($stock_query);
$stock_stmt->execute();
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #28a745; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-boxes" style="color: white;"></i> 
            Reporte de Inventario
        </h2>
    </div>

    <!-- Botón de Retorno a la Página de Reportes -->
    <div class="d-flex justify-content-start mb-4">
        <a href="reportes.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Reportes
        </a>
    </div>

    <!-- Formulario de Filtro de Fechas -->
    <form method="GET" action="reporte_inventario.php" class="row mb-4">
        <div class="col-md-4">
            <input type="date" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>" placeholder="Fecha de Inicio">
        </div>
        <div class="col-md-4">
            <input type="date" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>" placeholder="Fecha de Fin">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success w-100">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </div>
    </form>

    <!-- Tabla de Historial de Movimientos de Inventario -->
    <div class="table-responsive mb-5">
        <h4 class="text-center mb-3">Historial de Movimientos</h4>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID Movimiento</th>
                    <th>Producto</th>
                    <th>Tipo de Movimiento</th>
                    <th>Cantidad</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stmt && $stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['producto']}</td>
                                <td>{$row['tipo_movimiento']}</td>
                                <td>{$row['cantidad']}</td>
                                <td>{$row['usuario']}</td>
                                <td>{$row['fecha']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No se encontraron movimientos de inventario para el rango de fechas seleccionado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Stock Total por Producto -->
    <div class="table-responsive">
        <h4 class="text-center mb-3">Stock Total de Productos</h4>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Producto</th>
                    <th>Stock Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stock_stmt && $stock_stmt->rowCount() > 0) {
                    while ($stock_row = $stock_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$stock_row['nombre']}</td>
                                <td>{$stock_row['stock']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2' class='text-center'>No se encontró información de stock.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../../partials/footer.php'; // Incluir pie de página
?>
