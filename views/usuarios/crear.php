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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Usuario</title>
    <!-- Incluir estilos de Bootstrap y FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Usuario -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #007bff; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-user" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Crear Nuevo Usuario
        </h2>
    </div>

    <!-- Botones de Retorno y Acción -->
    <div class="d-flex justify-content-between mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Usuarios
        </a>
        <a href="../../index.php" class="btn btn-primary btn-custom-blue">
            <i class="fas fa-th-list"></i> Volver al Dashboard
        </a>
    </div>

    <!-- Formulario de Creación de Usuario -->
    <form id="crearUsuarioForm" action="../../controllers/usuarioController.php" method="POST" autocomplete="off">
        <input type="hidden" name="action" value="crear">

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" class="form-control" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="rol_id">Rol:</label>
            <select name="rol_id" id="rol_id" class="form-control" required>
                <option value="1">Administrador</option>
                <option value="2">Usuario</option>
            </select>
        </div>

        <!-- Botones de Acción -->
        <div class="form-group d-flex justify-content-between">
            <button type="submit" class="btn btn-primary btn-custom-blue">
                <i class="fas fa-check-circle"></i> Crear Usuario
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
