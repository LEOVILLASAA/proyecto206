<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Verificar si se ha pasado un ID en la URL
if (!isset($_GET['id'])) {
    header("Location: lista.php?mensajeError=ID de compra no proporcionado.");
    exit();
}

// Obtener el ID de la compra
$id = $_GET['id'];

// Incluir archivos necesarios
require_once '../../models/Database.php';
require_once '../../models/Compra.php';
require_once '../../models/Proveedor.php';
require_once '../../models/Producto.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener la información de la compra a editar
$compra = new Compra($db);
$compra->id = $id;
$compraData = $compra->leerCompraPorId(); // Cambiado para obtener datos como array

if (!$compraData) {
    header("Location: lista.php?mensajeError=Compra no encontrada.");
    exit();
}

// Asignar datos a las propiedades del objeto
$compra->proveedor_id = $compraData['proveedor_id'];
$compra->producto_id = $compraData['producto_id'];
$compra->cantidad = $compraData['cantidad'];
$compra->costo = $compraData['costo'];

// Obtener la lista de proveedores y productos para mostrar en el formulario
$proveedor = new Proveedor($db);
$producto = new Producto($db);
$proveedores = $proveedor->leerProveedores();
$productos = $producto->leerProductos();

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #0979b0; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-edit" style="color: white;"></i>
            Editar Compra
        </h2>
    </div>

    <!-- Formulario de Edición de Compra -->
    <form action="../../controllers/compraController.php?action=actualizar" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($compra->id); ?>">

        <div class="form-group">
            <label for="proveedor_id">Proveedor:</label>
            <select name="proveedor_id" id="proveedor_id" class="form-control" required>
                <?php while ($fila = $proveedores->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>" <?php echo ($compra->proveedor_id == $fila['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="producto_id">Producto:</label>
            <select name="producto_id" id="producto_id" class="form-control" required>
                <?php while ($fila = $productos->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>" <?php echo ($compra->producto_id == $fila['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" value="<?php echo htmlspecialchars($compra->cantidad); ?>" required>
        </div>

        <div class="form-group">
            <label for="costo">Costo Total:</label>
            <input type="number" step="0.01" name="costo" id="costo" class="form-control" value="<?php echo htmlspecialchars($compra->costo); ?>" required>
        </div>

        <!-- Botones de Acción -->
        <button type="submit" class="btn btn-primary" style="background-color: #0979b0; border-color: #0979b0;">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-times-circle"></i> Cancelar
        </a>
    </form>

    <?php
    // Mostrar mensaje de éxito si se ha actualizado correctamente
    if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'Compra actualizada exitosamente') {
        echo '<div class="alert alert-success mt-3" role="alert">Compra actualizada exitosamente.</div>';
    }
    ?>
</div>

<!-- Incluir el pie de página -->
<?php include '../../partials/footer.php'; ?>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
