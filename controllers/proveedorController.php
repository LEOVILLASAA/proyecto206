<?php
require_once '../models/Database.php';
require_once '../models/Proveedor.php';

$database = new Database();
$db = $database->getConnection();
$proveedor = new Proveedor($db);

// Crear un nuevo proveedor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crearProveedor'])) {
    $proveedor->nombre = $_POST['nombre'];
    $proveedor->contacto = $_POST['contacto'];
    $proveedor->telefono = $_POST['telefono'];
    $proveedor->email = $_POST['email'];
    $proveedor->direccion = $_POST['direccion'];

    if ($proveedor->crearProveedor()) {
        header("Location: ../views/proveedores/lista.php?mensaje=Proveedor creado exitosamente");
    } else {
        echo "Error al crear el proveedor.";
    }
}

// Actualizar un proveedor existente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizarProveedor'])) {
    $proveedor->id = $_POST['id'];
    $proveedor->nombre = $_POST['nombre'];
    $proveedor->contacto = $_POST['contacto'];
    $proveedor->telefono = $_POST['telefono'];
    $proveedor->email = $_POST['email'];
    $proveedor->direccion = $_POST['direccion'];

    if ($proveedor->actualizarProveedor()) {
        header("Location: ../views/proveedores/lista.php?mensaje=Proveedor actualizado exitosamente");
    } else {
        echo "Error al actualizar el proveedor.";
    }
}

// Eliminar un proveedor
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['eliminar'])) {
    $proveedor->id = $_GET['eliminar'];

    if ($proveedor->eliminarProveedor()) {
        header("Location: ../views/proveedores/lista.php?mensaje=Proveedor eliminado exitosamente");
    } else {
        echo "Error al eliminar el proveedor.";
    }
}
?>
