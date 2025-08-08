<?php
// Mostrar mensaje de error si hay uno en la URL
if (isset($_GET['error'])) {
    if ($_GET['error'] == '1') {
        echo "<div class='alert alert-danger text-center'>Usuario o contraseña incorrectos.</div>";
    } elseif ($_GET['error'] == '2') {
        echo "<div class='alert alert-warning text-center'>Acceso restringido: Solo los administradores pueden ingresar.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Estilos personalizados para un diseño moderno */
        body {
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.5s ease-in-out;
        }

        .login-container:hover {
            transform: translateY(-5px);
        }

        .login-container h2 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #2193b0;
        }

        .login-container .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .login-container .form-group input {
            height: 45px;
            border: none;
            border-bottom: 2px solid #ddd;
            transition: border-color 0.3s;
        }

        .login-container .form-group input:focus {
            border-bottom: 2px solid #2193b0;
            box-shadow: none;
        }

        .login-container .form-group i {
            position: absolute;
            top: 12px;
            left: 10px;
            color: #2193b0;
        }

        .login-container .btn-primary {
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            border: none;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.5s;
        }

        .login-container .btn-primary:hover {
            background: linear-gradient(135deg, #2193b0, #6dd5ed);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Iniciar Sesión</h2>

        <!-- Formulario de inicio de sesión -->
        <form action="../../controllers/authController.php" method="POST" class="mt-4">
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" class="form-control pl-5" placeholder="Correo Electrónico" required>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" class="form-control pl-5" placeholder="Contraseña" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-block">Iniciar Sesión</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Animación de la caja de login
        $(document).ready(function () {
            $('.login-container').hide().fadeIn(1500);
        });
    </script>
</body>
</html>
