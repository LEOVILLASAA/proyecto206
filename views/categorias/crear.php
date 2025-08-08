<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador (role = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    // Redirigir al usuario a la página de login si no tiene permisos de administrador
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
    <title>Crear Nueva Categoría</title>
    <!-- Incluir estilos de Bootstrap y FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Categoría -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #28a745; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-tags" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Crear Nueva Categoría
        </h2>
    </div>

    <!-- Botón de Retorno -->
    <div class="d-flex justify-content-start mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Categorías
        </a>
    </div>

    <!-- Formulario de Creación de Categoría -->
    <form action="../../controllers/categoriaController.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <!-- Botones de Acción -->
        <div class="form-group">
            <button type="submit" name="crearCategoria" class="btn btn-success">
                <i class="fas fa-check-circle"></i> Crear Categoría
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
