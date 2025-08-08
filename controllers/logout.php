<?php
session_start(); // Iniciar la sesión para poder manipularla

// Destruir todas las variables de sesión
$_SESSION = array(); // Vaciar las variables de sesión

// Eliminar la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Destruir la sesión por completo
session_destroy();

// Redirigir al formulario de inicio de sesión
header("Location: ../views/auth/login.php");
exit();
?>
