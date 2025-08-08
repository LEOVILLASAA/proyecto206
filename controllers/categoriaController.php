<?php
require_once '../models/Database.php';
require_once '../models/Categoria.php';
require_once 'logController.php';  // Incluir el controlador de logs

$database = new Database();
$db = $database->getConnection();
$categoria = new Categoria($db);

// Crear una nueva categoría
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crearCategoria'])) {
    $categoria->nombre = $_POST['nombre'];
    $categoria->descripcion = $_POST['descripcion'];

    if ($categoria->crearCategoria()) {
        registrarAccion(1, "Crear", "Categorías", $db->lastInsertId());  // Registrar log
        header("Location: ../views/categorias/lista.php?mensaje=Categoría creada exitosamente");
    } else {
        echo "Error al crear la categoría.";
    }
}

// Actualizar una categoría existente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizarCategoria'])) {
    $categoria->id = $_POST['id'];
    $categoria->nombre = $_POST['nombre'];
    $categoria->descripcion = $_POST['descripcion'];

    if ($categoria->actualizarCategoria()) {
        registrarAccion(1, "Actualizar", "Categorías", $categoria->id);  // Registrar log
        header("Location: ../views/categorias/lista.php?mensaje=Categoría actualizada exitosamente");
    } else {
        echo "Error al actualizar la categoría.";
    }
}

// Eliminar una categoría
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['eliminar'])) {
    $categoria->id = $_GET['eliminar'];

    if ($categoria->eliminarCategoria()) {
        registrarAccion(1, "Eliminar", "Categorías", $categoria->id);  // Registrar log
        header("Location: ../views/categorias/lista.php?mensaje=Categoría eliminada exitosamente");
    } else {
        echo "Error al eliminar la categoría.";
    }
}
?>
