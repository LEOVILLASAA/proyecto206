<?php
require_once '../../models/Database.php';
require_once '../../models/Venta.php';
require_once '../../models/Producto.php';
require_once '../../models/Usuario.php';
require_once '../../models/Cliente.php';

$database = new Database();
$db = $database->getConnection();

// Obtener lista de productos, usuarios y clientes
$producto = new Producto($db);
$productos = $producto->leerProductos();

$usuario = new Usuario($db);
$usuarios = $usuario->leerUsuarios();

$cliente = new Cliente($db);
$clientes = $cliente->leerClientes();

// Obtener el ID de la venta a editar
$id_venta = isset($_GET['id']) ? intval($_GET['id']) : 0;
$venta = new Venta($db);
$venta->id = $id_venta;
$stmt = $venta->leerVentaPorID();
$venta_actual = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venta_actual) {
    header("Location: lista.php?error=Venta no encontrada.");
    exit();
}

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #dc3545; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-edit" style="color: white;"></i> Editar Venta
        </h2>
    </div>

    <!-- Mostrar mensaje de error si existe -->
    <?php if (isset($_GET['error'])): ?>
        <div class='alert alert-danger text-center'><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form id="venta-form" action="../../controllers/ventaController.php" method="POST" class="card p-4 shadow-sm">
        <!-- Identificador de la venta (Oculto) -->
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($venta_actual['id']); ?>">

        <!-- Selección de Cliente -->
        <div class="form-group">
            <label for="cliente_id">Seleccionar Cliente:</label>
            <select name="cliente_id" id="cliente_id" class="form-control" required>
                <option value="">Seleccionar Cliente</option>
                <?php while ($fila = $clientes->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>" <?php if ($fila['id'] == $venta_actual['cliente_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($fila['dni']) . " - " . htmlspecialchars($fila['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Selección de Producto -->
        <div class="form-group">
            <label for="producto_id">Producto:</label>
            <select name="producto_id" id="producto_id" class="form-control" required>
                <option value="">Seleccionar Producto</option>
                <?php while ($fila = $productos->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>" data-precio="<?php echo $fila['precio']; ?>" <?php if ($fila['id'] == $venta_actual['producto_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($fila['nombre']); ?> (S/ <?php echo $fila['precio']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Campos del formulario para Cantidad, Monto de Ganancia y Cálculos -->
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" value="<?php echo htmlspecialchars($venta_actual['cantidad']); ?>" required>
        </div>

        <div class="form-group">
            <label for="ganancia">Monto de Ganancia (S/):</label>
            <input type="number" step="0.01" name="ganancia" id="ganancia" class="form-control" value="<?php echo htmlspecialchars($venta_actual['ganancia']); ?>" required>
        </div>

        <div class="form-group">
            <label for="subtotal">Subtotal a Pagar (Sin IGV) (S/):</label>
            <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label for="monto_igv">Monto del IGV (18%) (S/):</label>
            <input type="number" step="0.01" name="monto_igv" id="monto_igv" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label for="precio_total">Precio Total con IGV (S/):</label>
            <input type="number" step="0.01" name="precio_total" id="precio_total" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label for="usuario_id">Usuario que realiza la venta:</label>
            <select name="usuario_id" id="usuario_id" class="form-control" required>
                <option value="">Seleccionar Usuario</option>
                <?php while ($fila = $usuarios->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>" <?php if ($fila['id'] == $venta_actual['usuario_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" name="actualizarVenta" class="btn btn-danger">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-times-circle"></i> Cancelar
        </a>
    </form>
</div>

<?php include '../../partials/footer.php'; ?>

<!-- Script para realizar cálculos automáticos y búsqueda de clientes -->
<script>
document.getElementById('producto_id').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const precioCosto = parseFloat(selectedOption.getAttribute('data-precio'));
    calcularTotales(precioCosto);
});

document.getElementById('cantidad').addEventListener('input', function () {
    calcularTotales();
});

document.getElementById('ganancia').addEventListener('input', function () {
    calcularTotales();
});

// Función para calcular los totales
function calcularTotales(precioCosto = null) {
    const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
    const ganancia = parseFloat(document.getElementById('ganancia').value) || 0;
    precioCosto = precioCosto !== null ? precioCosto : parseFloat(document.getElementById('producto_id').selectedOptions[0].getAttribute('data-precio')) || 0;

    const precioVentaUnitario = precioCosto + ganancia;
    const subtotal = precioVentaUnitario * cantidad;
    const montoIGV = subtotal * 0.18;
    const precioTotalConIGV = subtotal + montoIGV;

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('monto_igv').value = montoIGV.toFixed(2);
    document.getElementById('precio_total').value = precioTotalConIGV.toFixed(2);
}

// Inicializar los valores de los campos
calcularTotales();
</script>
