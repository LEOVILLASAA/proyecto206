<?php
session_start(); // Iniciar la sesión

// Evitar el caché del navegador para evitar mostrar contenido cuando no hay sesión
header("Cache-Control: no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: views/auth/login.php");
    exit();
}

// Incluir el encabezado
include 'partials/header.php';
?>

<div class="container mt-5 dashboard-content">
    <h1 class="text-center mb-4">Panel de Control</h1>
    <p class="lead text-center mb-5">Selecciona el módulo al que deseas acceder:</p>

    <!-- Contenedor de Tarjetas de Módulo -->
    <div class="row text-center">
        <!-- Tarjeta de Gestión de Usuarios -->
        <?php if ($_SESSION['role'] == 1): // Solo el Administrador puede ver la gestión de usuarios ?>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Gestión de Usuarios</h5>
                    <p class="card-text">Administra y gestiona los usuarios del sistema.</p>
                    <a href="views/usuarios/lista.php" class="btn btn-outline-primary">Acceder</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tarjeta de Gestión de Categorías -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-tags fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Gestión de Categorías</h5>
                    <p class="card-text">Crea, edita y gestiona las categorías de productos.</p>
                    <a href="views/categorias/lista.php" class="btn btn-outline-success">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Gestión de Productos -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-box-open fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">Gestión de Productos</h5>
                    <p class="card-text">Gestiona el inventario y la información de productos.</p>
                    <a href="views/productos/lista.php" class="btn btn-outline-warning">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Gestión de Proveedores -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-truck fa-3x mb-3 text-info"></i>
                    <h5 class="card-title">Gestión de Proveedores</h5>
                    <p class="card-text">Administra los proveedores del sistema de gestión.</p>
                    <a href="views/proveedores/lista.php" class="btn btn-outline-info">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Gestión de Clientes -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-address-book fa-3x mb-3 text-secondary"></i>
                    <h5 class="card-title">Gestión de Clientes</h5>
                    <p class="card-text">Administra la información de los clientes registrados.</p>
                    <a href="views/clientes/lista.php" class="btn btn-outline-secondary">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Gestión de Ventas -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title">Gestión de Ventas</h5>
                    <p class="card-text">Administra y controla las ventas realizadas.</p>
                    <a href="views/ventas/lista.php" class="btn btn-outline-danger">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Gestión de Compras -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-shopping-bag fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Gestión de Compras</h5>
                    <p class="card-text">Registra y administra las compras de productos.</p>
                    <a href="views/compras/lista.php" class="btn btn-outline-primary">Acceder</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Reportes y Estadísticas -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-3x mb-3 text-dark"></i>
                    <h5 class="card-title">Reportes y Estadísticas</h5>
                    <p class="card-text">Genera reportes y visualiza estadísticas del sistema.</p>
                    <a href="views/reportes/reportes.php" class="btn btn-outline-dark">Acceder</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
