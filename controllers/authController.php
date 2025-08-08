<?php
// Habilitar la visualización de errores para el desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Incluir los archivos necesarios
require_once '../models/Database.php';
require_once '../models/Usuario.php';

// Verificar si se envió el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Conectar a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Crear una instancia del modelo de Usuario
    $usuario = new Usuario($db);

    // Verificar si el usuario existe en la base de datos por su email
    $usuarioEncontrado = $usuario->getUserByEmail($email); // Llamar el método pasando $email como argumento

    // Si se encuentra el usuario, verificar la contraseña
    if ($usuarioEncontrado && password_verify($password, $usuarioEncontrado['password'])) {
        // Establecer variables de sesión
        $_SESSION['user_id'] = $usuarioEncontrado['id'];
        $_SESSION['user_name'] = $usuarioEncontrado['nombre'];
        $_SESSION['role'] = $usuarioEncontrado['rol_id'];

        // Redirigir al dashboard
        header("Location: ../index.php");
        exit();
    } else {
        // Si no se encuentra el usuario o la contraseña es incorrecta
        header("Location: ../views/auth/login.php?error=1");
        exit();
    }
} else {
    // Si se accede directamente a este script, redirigir al formulario de inicio de sesión
    header("Location: ../views/auth/login.php");
    exit();
}
?>
