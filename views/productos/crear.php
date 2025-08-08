<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir la conexión a la base de datos y el modelo Producto y Categoría
require_once '../../models/Database.php';
require_once '../../models/Producto.php';
require_once '../../models/Categoria.php';

// Crear la instancia de la base de datos y los modelos
$database = new Database();
$db = $database->getConnection();

$categoria = new Categoria($db);
$categorias = $categoria->leerCategorias();

$producto = new Producto($db);

// Procesar el formulario de creación de producto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y aplicar sanitización
    $producto->nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $producto->descripcion = htmlspecialchars(strip_tags($_POST['descripcion']));
    $producto->precio = htmlspecialchars(strip_tags($_POST['precio']));
    $producto->stock = htmlspecialchars(strip_tags($_POST['stock']));
    $producto->categoria_id = htmlspecialchars(strip_tags($_POST['categoria_id']));

    // Verificar si se subió una imagen
    if (!empty($_FILES["imagen"]["name"])) {
        $target_dir = "../../assets/images/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Solo permitir ciertos formatos de imagen
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $allowed_types)) {
            // Verificar si se pudo subir el archivo
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $producto->imagen = htmlspecialchars(strip_tags(basename($_FILES["imagen"]["name"])));
            } else {
                $mensajeError = "Error al subir la imagen. Verifica los permisos de la carpeta.";
            }
        } else {
            $mensajeError = "Formato de imagen no válido. Solo se permiten JPG, JPEG, PNG y GIF.";
        }
    } else {
        $producto->imagen = "default.png"; // Imagen por defecto si no se sube ninguna
    }

    // Crear un nuevo producto
    if (!isset($mensajeError) && $producto->crearProducto()) {
        header("Location: lista.php?mensaje=Producto creado correctamente.");
        exit();
    } else {
        $mensajeError = isset($mensajeError) ? $mensajeError : "Error: No se pudo crear el producto. Intenta nuevamente.";
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
            Crear Nuevo Producto
        </h2>
    </div>

    <!-- Botones de Navegación -->
    <div class="d-flex justify-content-start mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Productos
        </a>
    </div>

    <!-- Mostrar el mensaje de error si existe -->
    <?php if (isset($mensajeError)): ?>
        <div class='alert alert-danger text-center'><?php echo $mensajeError; ?></div>
    <?php endif; ?>

    <!-- Formulario de Creación de Producto -->
    <form action="crear.php" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingresa el nombre del producto" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción del producto" required></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" placeholder="Precio del producto" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" class="form-control" placeholder="Cantidad en stock" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" id="categoria_id" class="form-control" required>
                <option value="">Seleccionar categoría</option>
                <?php while ($fila = $categorias->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $fila['id']; ?>">
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del Producto:</label>
            <input type="file" name="imagen" id="imagen" class="form-control-file">
        </div>

        <!-- Botones de Acción -->
        <button type="submit" class="btn btn-warning" style="background-color: #ffc107; border-color: #ffc107;">
            <i class="fas fa-save"></i> Crear Producto
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
