<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir la conexión a la base de datos y el modelo Usuario
require_once '../../models/Database.php';
require_once '../../models/Usuario.php';

// Crear instancia de la base de datos y el modelo Usuario
$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

// Obtener el ID del usuario a editar desde la URL
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $usuario->id = $_GET['id'];

    // Obtener los detalles del usuario
    $usuarioData = $usuario->getUserById($usuario->id);
    if ($usuarioData) {
        $usuario->nombre = $usuarioData['nombre'];
        $usuario->email = $usuarioData['email'];
        $usuario->password = $usuarioData['password'];
        $usuario->rol_id = $usuarioData['rol_id'];
    } else {
        echo "<div class='alert alert-danger'>Error: No se encontró el usuario.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>ID de usuario no válido.</div>";
    exit();
}

// Procesar el formulario de actualización de usuario
if (isset($_POST['update'])) {
    $usuario->nombre = $_POST['nombre'];
    $usuario->email = $_POST['email'];
    $usuario->password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $usuarioData['password'];
    $usuario->rol_id = $_POST['rol_id'];

    if ($usuario->actualizarUsuario()) {
        header("Location: lista.php?mensaje=Usuario actualizado correctamente");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: No se pudo actualizar el usuario.</div>";
    }
}

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Usuario -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #007bff; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-users" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Editar Usuario
        </h2>
    </div>

    <!-- Botón de Retorno -->
    <div class="d-flex justify-content-start mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Usuarios
        </a>
    </div>

    <!-- Formulario de Edición de Usuario -->
    <form action="editar.php?id=<?php echo $usuario->id; ?>" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario->nombre); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($usuario->email); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Nueva Contraseña (Opcional):</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="rol_id">Rol:</label>
            <select name="rol_id" id="rol_id" class="form-control" required>
                <option value="1" <?php if ($usuario->rol_id == 1) echo 'selected'; ?>>Administrador</option>
                <option value="2" <?php if ($usuario->rol_id == 2) echo 'selected'; ?>>Usuario</option>
            </select>
        </div>
        <!-- Botones de Acción -->
        <div class="form-group d-flex justify-content-between">
            <button type="submit" name="update" class="btn btn-primary btn-custom-blue">
                <i class="fas fa-sync-alt"></i> Actualizar Usuario
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
