<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir la conexión a la base de datos y el modelo Venta
require_once '../../models/Database.php';
require_once '../../models/Venta.php';

$database = new Database();
$db = $database->getConnection();
$venta = new Venta($db);

// Verificar si se recibió el ID de la venta a mostrar
if (isset($_GET['id'])) {
    $venta->id = $_GET['id'];
    $resultado = $venta->leerVentaPorID(); // Obtener los detalles de la venta
    $fila = $resultado->fetch(PDO::FETCH_ASSOC);

    // Si no se encuentra la venta, redirigir a la lista con un mensaje de error
    if (!$fila) {
        header("Location: lista.php?error=La venta no fue encontrada.");
        exit();
    }
} else {
    header("Location: lista.php?error=ID de venta no proporcionado.");
    exit();
}

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Detalle -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #dc3545; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-info-circle" style="color: white;"></i>
            Detalle de la Venta
        </h2>
    </div>

    <!-- Botón de Retorno -->
    <div class="d-flex justify-content-start mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Ventas
        </a>
    </div>

    <!-- Tabla con el Detalle de la Venta -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>ID de la Venta</th>
                <td><?php echo $fila['id']; ?></td>
            </tr>
            <tr>
                <th>Producto</th>
                <td><?php echo htmlspecialchars($fila['producto']); ?></td>
            </tr>
            <tr>
                <th>Cantidad</th>
                <td><?php echo htmlspecialchars($fila['cantidad']); ?></td>
            </tr>
            <tr>
                <th>Precio Unitario (S/)</th>
                <td>S/ <?php echo number_format($fila['precio'], 2); ?></td>
            </tr>
            <tr>
                <th>Monto Total (S/)</th>
                <td>S/ <?php echo number_format($fila['cantidad'] * $fila['precio'], 2); ?></td>
            </tr>
            <tr>
                <th>Monto del IGV (S/)</th>
                <td>S/ 
                    <?php 
                    $montoTotal = $fila['cantidad'] * $fila['precio'];
                    $montoIGV = ($montoTotal * 1.18) - $montoTotal;
                    echo number_format($montoIGV, 2); 
                    ?>
                </td>
            </tr>
            <tr>
                <th>Precio Total con IGV (S/)</th>
                <td>S/ <?php echo number_format($montoTotal * 1.18, 2); ?></td>
            </tr>
            <tr>
                <th>Usuario</th>
                <td><?php echo htmlspecialchars($fila['usuario']); ?></td>
            </tr>
            <tr>
                <th>Fecha de Registro</th>
                <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
            </tr>
        </table>
    </div>
</div>

<!-- Incluir el pie de página -->
<?php include '../../partials/footer.php'; ?>
