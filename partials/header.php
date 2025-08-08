<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Iniciar la sesión solo si aún no se ha iniciado
}

// Definir la URL base del proyecto
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/curso/";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Inventario</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">




</head>
<body>
    <!-- Encabezado Moderno -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $base_url; ?>index.php">Sistema de Gestión de Inventario</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <span class="nav-link">Bienvenido, <?php echo $_SESSION['user_name']; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>controllers/logout.php" onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?');">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Iniciar el contenedor principal -->
    <main class="container mt-4">
