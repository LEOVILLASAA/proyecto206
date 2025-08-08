<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Usuario -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #007bff; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-users" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Gestión de Usuarios
        </h2>
    </div>

    <!-- Botones de Acción -->
    <div class="d-flex justify-content-between mb-4">
        <a href="../../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
        <!-- Botón de Crear Nuevo Usuario con la clase personalizada -->
        <a href="crear.php" class="btn btn-primary btn-custom-blue">
            <i class="fas fa-user-plus"></i> Crear Nuevo Usuario
        </a>
    </div>

    <!-- Mensaje de éxito/error al agregar/editar/eliminar usuarios -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class='alert <?php echo (strpos($_GET['mensaje'], 'Error') !== false) ? 'alert-danger' : 'alert-success'; ?> text-center'>
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>

    <!-- Obtener la lista de usuarios desde la base de datos -->
    <?php
    require_once '../../models/Database.php';
    require_once '../../models/Usuario.php';

    // Conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();
    $usuario = new Usuario($db);

    // Obtener la lista de usuarios
    $resultado = $usuario->leerUsuarios();

    // Verificar si se encontraron usuarios
    if ($resultado->rowCount() > 0) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-striped table-bordered table-hover'>";
        echo "<thead class='thead-dark'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Nombre</th>";
        echo "<th>Email</th>";
        echo "<th>Rol</th>";
        echo "<th>Acciones</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Mostrar los usuarios
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$nombre}</td>";
            echo "<td>{$email}</td>";
            echo "<td>{$rol}</td>";
            echo "<td>";
            echo "<a href='editar.php?id={$id}' class='btn btn-warning m-1'><i class='fas fa-edit'></i> Editar</a>";
            echo "<a href='../../controllers/usuarioController.php?eliminar={$id}' class='btn btn-danger m-1' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\");'><i class='fas fa-trash'></i> Eliminar</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-info text-center'>No se encontraron usuarios.</div>";
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
