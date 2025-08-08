<?php
require_once '../models/Database.php';
require_once '../models/Producto.php';
require_once 'logController.php';  // Incluir el controlador de logs

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

// Crear un nuevo producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crearProducto'])) {
    $producto->categoria_id = $_POST['categoria_id'];
    $producto->nombre = $_POST['nombre'];
    $producto->descripcion = $_POST['descripcion'];
    $producto->precio = $_POST['precio'];
    $producto->stock = $_POST['stock'];

    if ($_FILES['imagen']['name']) {
        $producto->imagen = basename($_FILES['imagen']['name']);
        $ruta_imagen = "../assets/images/" . $producto->imagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
    }

    if ($producto->crearProducto()) {
        registrarAccion(1, "Crear", "Productos", $db->lastInsertId());  // Registrar log
        header("Location: ../views/productos/lista.php?mensaje=Producto creado exitosamente");
    } else {
        echo "Error al crear el producto.";
    }
}

// Actualizar un producto existente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizarProducto'])) {
    $producto->id = $_POST['id'];
    $producto->categoria_id = $_POST['categoria_id'];
    $producto->nombre = $_POST['nombre'];
    $producto->descripcion = $_POST['descripcion'];
    $producto->precio = $_POST['precio'];
    $producto->stock = $_POST['stock'];

    if ($_FILES['imagen']['name']) {
        $producto->imagen = basename($_FILES['imagen']['name']);
        $ruta_imagen = "../assets/images/" . $producto->imagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
    }

    if ($producto->actualizarProducto()) {
        registrarAccion(1, "Actualizar", "Productos", $producto->id);  // Registrar log
        header("Location: ../views/productos/lista.php?mensaje=Producto actualizado exitosamente");
    } else {
        echo "Error al actualizar el producto.";
    }
}

// Eliminar un producto
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['eliminar'])) {
    $producto->id = $_GET['eliminar'];

    if ($producto->eliminarProducto()) {
        registrarAccion(1, "Eliminar", "Productos", $producto->id);  // Registrar log
        header("Location: ../views/productos/lista.php?mensaje=Producto eliminado exitosamente");
    } else {
        echo "Error al eliminar el producto.";
    }
}
?>
