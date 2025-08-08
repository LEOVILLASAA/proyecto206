<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir el encabezado
include '../../partials/header.php';

// Conectar a la base de datos y cargar los proveedores y productos
require_once '../../models/Database.php';
require_once '../../models/Proveedor.php';
require_once '../../models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$proveedor = new Proveedor($db);
$producto = new Producto($db);
$proveedores = $proveedor->leerProveedores();
$productos = $producto->leerProductos();
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Compras en Color #0979b0 -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #0979b0; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-shopping-bag" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Registrar Nueva Compra
        </h2>
    </div>

    <!-- Botones de Acción -->
    <div class="d-flex justify-content-between mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Compras
        </a>
    </div>

    <!-- Formulario de Registro de Compras -->
    <form action="../../controllers/compraController.php?action=crear" method="POST" class="card p-4 shadow-sm">
        <div class="form-group">
            <label for="proveedor_id">Proveedor:</label>
            <select name="proveedor_id" id="proveedor_id" class="form-control" required>
                <option value="">Seleccionar Proveedor</option>
                <?php while ($fila = $proveedores->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>"><?php echo htmlspecialchars($fila['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="producto_id">Producto:</label>
            <select name="producto_id" id="producto_id" class="form-control" required>
                <option value="">Seleccionar Producto</option>
                <?php while ($fila = $productos->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>"><?php echo htmlspecialchars($fila['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad de Compra:</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" placeholder="Ingrese la cantidad" required>
        </div>

        <div class="form-group">
            <label for="costo_unitario">Costo Unitario (S/):</label>
            <input type="number" step="0.01" name="costo" id="costo_unitario" class="form-control" placeholder="Ingrese el costo unitario" required>
        </div>

        <div class="form-group">
            <label for="costo_total">Costo Total (S/):</label>
            <input type="number" step="0.01" id="costo_total" class="form-control" placeholder="Costo total" readonly>
        </div>

        <!-- Botones de Acción -->
        <button type="submit" class="btn btn-primary" style="background-color: #0979b0; border-color: #0979b0;">
            <i class="fas fa-save"></i> Registrar Compra
        </button>
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-times-circle"></i> Cancelar
        </a>
    </form>
</div>

<!-- Incluir el pie de página -->
<?php include '../../partials/footer.php'; ?>

<!-- Scripts de Bootstrap y FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script para calcular el costo total -->
<script>
    document.getElementById('cantidad').addEventListener('input', calcularCostoTotal);
    document.getElementById('costo_unitario').addEventListener('input', calcularCostoTotal);

    function calcularCostoTotal() {
        const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
        const costoUnitario = parseFloat(document.getElementById('costo_unitario').value) || 0;
        const costoTotal = cantidad * costoUnitario;
        document.getElementById('costo_total').value = costoTotal.toFixed(2);
    }
</script>
</body>
</html>
