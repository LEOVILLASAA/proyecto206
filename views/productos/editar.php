<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../views/auth/login.php");
    exit();
}

require_once '../../models/Database.php';
require_once '../../models/Producto.php';
require_once '../../models/Categoria.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

$categoria = new Categoria($db);
$categorias = $categoria->leerCategorias(); // Leer todas las categorías

$producto = new Producto($db);

// Verificar si el ID del producto está presente en la URL
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $producto->id = $_GET['id'];

    // Obtener el producto específico basado en el ID
    $producto_actual = $producto->obtenerProductoPorId(); // Cambiado el nombre del método

    // Verificar si se encontró el producto
    if (!$producto_actual) {
        echo "<div class='alert alert-danger'>Error: No se encontró el producto.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>ID de producto no válido.</div>";
    exit();
}

// Procesar el formulario de actualización de producto
if (isset($_POST['actualizarProducto'])) {
    $producto->nombre = $_POST['nombre'];
    $producto->descripcion = $_POST['descripcion'];
    $producto->precio = $_POST['precio'];
    $producto->stock = $_POST['stock'];
    $producto->categoria_id = $_POST['categoria_id'];

    // Verificar si hay una imagen nueva cargada
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen']['name'];
        $target_dir = "../../assets/images/";
        $target_file = $target_dir . basename($imagen);

        // Mover el archivo subido a la carpeta
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
            $producto->imagen = $imagen;
        }
    }

    // Actualizar el producto
    if ($producto->actualizarProducto()) {
        header("Location: lista.php?mensaje=Producto actualizado correctamente");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: No se pudo actualizar el producto.</div>";
    }
}

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Producto -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #ffc107; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-box-open" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Editar Producto
        </h2>
    </div>

    <!-- Botón de Retorno -->
    <div class="d-flex justify-content-start mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Productos
        </a>
    </div>

    <!-- Formulario de Edición de Producto -->
    <form action="editar.php?id=<?php echo $producto_actual['id']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $producto_actual['id']; ?>">

        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($producto_actual['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required><?php echo htmlspecialchars($producto_actual['descripcion']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="<?php echo $producto_actual['precio']; ?>" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" class="form-control" value="<?php echo $producto_actual['stock']; ?>" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" id="categoria_id" class="form-control" required>
                <?php while ($fila = $categorias->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>" <?php if ($fila['id'] == $producto_actual['categoria_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Subir Nueva Imagen:</label>
            <input type="file" name="imagen" id="imagen" class="form-control-file">
            <div class="mt-3">
                <img src="../../assets/images/<?php echo $producto_actual['imagen']; ?>" width="100" height="100" class="img-thumbnail">
            </div>
        </div>

        <button type="submit" name="actualizarProducto" class="btn btn-warning" style="background-color: #ffc107; border-color: #ffc107;">
            <i class="fas fa-save"></i> Actualizar Producto
        </button>
        <a href="lista.php" class="btn btn-secondary">Cancelar</a>
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
