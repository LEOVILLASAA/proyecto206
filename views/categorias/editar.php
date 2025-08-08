<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir la conexión a la base de datos y el modelo Categoria
require_once '../../models/Database.php';
require_once '../../models/Categoria.php';

// Crear instancia de la base de datos y el modelo Categoria
$database = new Database();
$db = $database->getConnection();
$categoria = new Categoria($db);

// Obtener el ID de la categoría a editar desde la URL
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $categoria->id = $_GET['id'];

    // Obtener los detalles de la categoría
    $categoriaData = $categoria->getCategoriaById($categoria->id);
    if ($categoriaData) {
        $categoria->nombre = $categoriaData['nombre'];
        $categoria->descripcion = $categoriaData['descripcion'];
    } else {
        echo "<div class='alert alert-danger'>Error: No se encontró la categoría.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>ID de categoría no válido.</div>";
    exit();
}

// Procesar el formulario de actualización de categoría
if (isset($_POST['update'])) {
    $categoria->nombre = $_POST['nombre'];
    $categoria->descripcion = $_POST['descripcion'];

    if ($categoria->actualizarCategoria()) {
        header("Location: lista.php?mensaje=Categoría actualizada correctamente");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: No se pudo actualizar la categoría.</div>";
    }
}

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Categoría -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #28a745; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-tags" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Editar Categoría
        </h2>
    </div>

    <!-- Botón de Retorno -->
    <div class="d-flex justify-content-start mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Categorías
        </a>
    </div>

    <!-- Formulario de Edición de Categoría -->
    <form action="editar.php?id=<?php echo $categoria->id; ?>" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($categoria->nombre); ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required><?php echo htmlspecialchars($categoria->descripcion); ?></textarea>
        </div>

        <!-- Botones de Acción -->
        <div class="form-group d-flex justify-content-between">
            <button type="submit" name="update" class="btn btn-success">
                <i class="fas fa-sync-alt"></i> Actualizar Categoría
            </button>
            <a href="lista.php" class="btn btn-secondary">
                <i class="fas fa-times-circle"></i> Cancelar
            </a>
        </div>
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
