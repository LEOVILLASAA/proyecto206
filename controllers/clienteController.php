<?php
// Incluir el archivo de conexión y el modelo Cliente
include '../models/Database.php';
include '../models/Cliente.php';

// Crear la instancia de la base de datos y el modelo
$database = new Database();
$conn = $database->getConnection();
$clienteModel = new Cliente($conn);

// Obtener la acción desde la URL
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dni = $_POST['dni'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            // Validar si el DNI ya existe antes de crear el cliente
            if ($clienteModel->existeDNI($dni)) {
                // Redirigir a la vista de creación con un mensaje de error
                header("Location: ../views/clientes/crear.php?mensaje=El cliente con DNI $dni ya está registrado.");
                exit();
            }

            // Crear un nuevo cliente en la base de datos
            if ($clienteModel->crearCliente($dni, $nombre, $email, $telefono, $direccion)) {
                header('Location: ../views/clientes/lista.php?mensaje=Cliente creado con éxito');
                exit();
            } else {
                header('Location: ../views/clientes/crear.php?mensaje=Error al crear el cliente');
                exit();
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $dni = $_POST['dni'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            if ($clienteModel->actualizarCliente($id, $dni, $nombre, $email, $telefono, $direccion)) {
                header('Location: ../views/clientes/lista.php?mensaje=Cliente actualizado con éxito');
                exit();
            } else {
                header('Location: ../views/clientes/lista.php?mensaje=Error al actualizar el cliente');
                exit();
            }
        }
        break;

    case 'eliminar':
        $id = isset($_GET['id']) ? $_GET['id'] : die('Error: No se proporcionó ID.');

        if ($clienteModel->eliminarCliente($id)) {
            header('Location: ../views/clientes/lista.php?mensaje=Cliente eliminado con éxito');
            exit();
        } else {
            header('Location: ../views/clientes/lista.php?mensaje=Error al eliminar el cliente');
            exit();
        }
        break;

    default:
        $clientes = $clienteModel->listarClientes();
        include '../views/clientes/lista.php';
        break;
}
?>
