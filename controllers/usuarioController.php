<?php
// Habilitar la visualización de errores para depuración (eliminar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir la conexión y el modelo de Usuario
require_once '../models/Database.php';
require_once '../models/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

// Verificar el tipo de acción solicitada y manejar la creación, actualización o eliminación de usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'crear') {
        // Asignar los datos del formulario al objeto Usuario
        $usuario->nombre = $_POST['nombre'];
        $usuario->email = $_POST['email'];
        $usuario->password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar la contraseña
        $usuario->rol_id = $_POST['rol_id'];

        // Mostrar los datos que se intentan insertar para depuración
        echo "<pre>";
        echo "Datos recibidos para creación de usuario: \n";
        var_dump($usuario);
        echo "</pre>";

        // Intentar crear el usuario
        if ($usuario->crearUsuario()) {
            echo "<pre>Usuario creado correctamente con ID: " . $db->lastInsertId() . "</pre>"; // Línea de depuración (remover en producción)

            // Confirmar si realmente se insertó en la base de datos con una consulta adicional
            $queryCheck = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
            $stmtCheck = $db->prepare($queryCheck);
            $stmtCheck->bindParam(":email", $usuario->email);

            if ($stmtCheck->execute()) {
                $usuarioInsertado = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                if ($usuarioInsertado) {
                    echo "<pre>El usuario con el correo {$usuario->email} ha sido insertado correctamente en la base de datos.</pre>";
                    header("Location: ../views/usuarios/lista.php?mensaje=Usuario creado correctamente");
                } else {
                    echo "<pre>Error: El usuario no se encontró en la base de datos después de la inserción.</pre>";
                }
            } else {
                echo "<pre>Error al ejecutar la consulta de verificación en la base de datos: ";
                print_r($stmtCheck->errorInfo());
                echo "</pre>";
            }
            exit();
        } else {
            echo "<pre>Error al intentar crear el usuario. Detalles: </pre>";
            print_r($usuario->getLastError());
            header("Location: ../views/usuarios/crear.php?mensaje=Error: No se pudo crear el usuario.");
            exit();
        }
    }

    // Actualizar un usuario existente
    if ($_POST['action'] == 'actualizar') {
        $usuario->id = $_POST['id'];
        $usuario->nombre = $_POST['nombre'];
        $usuario->email = $_POST['email'];
        $usuario->rol_id = $_POST['rol_id'];

        // Si se proporciona una nueva contraseña, encriptarla
        if (!empty($_POST['password'])) {
            $usuario->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        } else {
            // Si no se cambia la contraseña, obtener la actual de la base de datos
            $usuarioExistente = $usuario->getUserById($usuario->id);
            $usuario->password = $usuarioExistente['password'];
        }

        // Intentar actualizar el usuario
        if ($usuario->actualizarUsuario()) {
            header("Location: ../views/usuarios/lista.php?mensaje=Usuario actualizado correctamente");
            exit();
        } else {
            header("Location: ../views/usuarios/editar.php?id=" . $usuario->id . "&mensaje=Error: No se pudo actualizar el usuario.");
            exit();
        }
    }
}

// Eliminar un usuario
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['eliminar'])) {
    // Asignar el ID del usuario a eliminar
    $usuario->id = $_GET['eliminar'];

    // Intentar eliminar el usuario
    if ($usuario->eliminarUsuario()) {
        header("Location: ../views/usuarios/lista.php?mensaje=Usuario eliminado correctamente");
        exit();
    } else {
        header("Location: ../views/usuarios/lista.php?mensaje=Error: No se pudo eliminar el usuario.");
        exit();
    }
}
?>
