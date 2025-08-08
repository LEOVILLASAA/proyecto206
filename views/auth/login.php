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
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="login-background">
        <div class="login-container">
            <div class="login-card">
                <h2 class="text-center">Inicio de Sesión</h2>
                
                <!-- Mostrar mensajes de error si existen -->
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == '1') {
                        echo "<div class='alert alert-danger text-center'>Usuario o contraseña incorrectos.</div>";
                    } elseif ($_GET['error'] == '2') {
                        echo "<div class='alert alert-warning text-center'>Acceso restringido: Solo los administradores pueden ingresar.</div>";
                    }
                }
                ?>

                <!-- Formulario de Inicio de Sesión -->
                <form id="loginForm" action="../../controllers/authController.php" method="POST">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Ingresa tu correo" required>
                        <small id="emailError" class="form-text text-danger d-none">Por favor, ingresa un correo válido.</small>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Ingresa tu contraseña" required>
                        <small id="passwordError" class="form-text text-danger d-none">Por favor, ingresa una contraseña válida.</small>
                    </div>

                    <!-- Aplicar la nueva clase para el botón de inicio de sesión -->
                    <button type="submit" class="btn btn-login btn-block">Ingresar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="<?php echo $base_url; ?>assets/js/scripts.js"></script>
</body>
</html>
