<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../views/auth/login.php");
    exit();
}

require_once '../../models/Database.php';
require_once '../../models/Proveedor.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
$proveedor = new Proveedor($db);

// Verificar si el ID del proveedor está presente en la URL
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $proveedor->id = $_GET['id'];

    // Obtener el proveedor específico basado en el ID
    $resultado = $proveedor->obtenerProveedorPorId(); // Cambiado el nombre del método
    if (!$resultado) {
        echo "<div class='alert alert-danger'>Error: No se encontró el proveedor.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>ID de proveedor no válido.</div>";
    exit();
}

// Procesar el formulario de actualización de proveedor
if (isset($_POST['actualizarProveedor'])) {
    $proveedor->nombre = $_POST['nombre'];
    $proveedor->contacto = $_POST['contacto'];
    $proveedor->telefono = $_POST['telefono'];
    $proveedor->email = $_POST['email'];
    $proveedor->direccion = $_POST['direccion'];

    // Actualizar el proveedor
    if ($proveedor->actualizarProveedor()) {
        header("Location: lista.php?mensaje=Proveedor actualizado correctamente");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: No se pudo actualizar el proveedor.</div>";
    }
}

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Proveedor -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #17a2b8; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-truck" style="color: white;"></i>
            Editar Proveedor
        </h2>
    </div>

    <!-- Botón de Retorno -->
    <div class="d-flex justify-content-start mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Proveedores
        </a>
    </div>

    <!-- Formulario de Edición de Proveedor -->
    <form action="editar.php?id=<?php echo $proveedor->id; ?>" method="POST" class="card p-4 shadow-sm">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($resultado['id']); ?>">

        <div class="form-group">
            <label for="nombre">Nombre del Proveedor:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($resultado['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label for="contacto">Nombre del Contacto:</label>
            <input type="text" name="contacto" id="contacto" class="form-control" value="<?php echo htmlspecialchars($resultado['contacto']); ?>" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo htmlspecialchars($resultado['telefono']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($resultado['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <textarea name="direccion" id="direccion" class="form-control" required><?php echo htmlspecialchars($resultado['direccion']); ?></textarea>
        </div>

        <!-- Botones de Acción -->
        <button type="submit" name="actualizarProveedor" class="btn btn-info" style="background-color: #17a2b8; border-color: #17a2b8;">
            <i class="fas fa-save"></i> Actualizar Proveedor
        </button>
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-times-circle"></i> Cancelar
        </a>
    </form>
</div>

<!-- Incluir el pie de página -->
<?php include '../../partials/footer.php'; ?>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
