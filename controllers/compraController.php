<?php
session_start(); // Asegúrate de iniciar la sesión

require_once '../models/Database.php';
require_once '../models/Compra.php';
require_once '../models/Producto.php';  // Incluir el modelo Producto para la gestión del inventario
require_once '../models/Inventario.php'; // Incluir el modelo Inventario

// Instancia de conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
$compra = new Compra($db);
$producto = new Producto($db);  // Instanciar el modelo Producto
$inventario = new Inventario($db); // Instanciar el modelo Inventario

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Control de acciones basadas en el valor de `action`
switch ($action) {
    // Registrar una nueva compra y actualizar el stock del producto
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $compra->proveedor_id = $_POST['proveedor_id'];
            $compra->producto_id = $_POST['producto_id'];
            $compra->cantidad = $_POST['cantidad'];
            $compra->costo = $_POST['costo'];

            // Registrar la compra
            if ($compra->crearCompra()) {
                // Verifica que la sesión esté activa y que el usuario esté logueado
                if (isset($_SESSION['user_id'])) {
                    // Actualizar el inventario
                    $producto->actualizarStock($compra->producto_id, $compra->cantidad); // Asegúrate de que este método exista
                    $inventario->producto_id = $compra->producto_id;
                    $inventario->cantidad = $compra->cantidad;
                    $inventario->tipo_movimiento = 'entrada'; // Movimiento de entrada
                    $inventario->usuario_id = $_SESSION['user_id']; // Usuario actual

                    // Registrar movimiento en el inventario
                    if (!$inventario->registrarMovimiento()) {
                        // Manejar el error si no se pudo registrar el movimiento
                        header("Location: ../views/compras/crear.php?mensajeError=Error al registrar el movimiento en el inventario.");
                        exit();
                    }
                } else {
                    header("Location: ../views/auth/login.php");
                    exit();
                }
                
                header("Location: ../views/compras/lista.php?mensaje=Compra registrada exitosamente");
            } else {
                header("Location: ../views/compras/crear.php?mensajeError=Error al registrar la compra.");
            }
        }
        break;

    // Leer todas las compras
    case 'leer':
        $compras = $compra->leerCompras();
        include('../views/compras/lista.php');
        break;

    // Eliminar una compra y ajustar el stock del producto
    case 'eliminar':
        if (isset($_GET['id'])) {
            $compra->id = $_GET['id'];

            // Leer la compra para obtener el producto y la cantidad
            $compra->leerCompraPorId();
            $producto->id = $compra->producto_id;

            // Eliminar la compra y revertir el stock
            if ($compra->eliminarCompra()) {
                // Decrementar el stock del producto en la cantidad eliminada
                $producto->actualizarStock(-$compra->cantidad);
                header("Location: ../views/compras/lista.php?mensaje=Compra eliminada correctamente.");
            } else {
                header("Location: ../views/compras/lista.php?mensajeError=No se pudo eliminar la compra.");
            }
        }
        break;

    // Actualizar una compra existente y reflejar el cambio en el stock
    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $compra->id = $_POST['id'];

            // Leer la compra actual para calcular la diferencia de stock
            $compra->leerCompraPorId();
            $producto->id = $compra->producto_id;
            $cantidadAnterior = $compra->cantidad;

            // Asignar nuevos valores
            $compra->proveedor_id = $_POST['proveedor_id'];
            $compra->producto_id = $_POST['producto_id'];
            $compra->cantidad = $_POST['cantidad'];
            $compra->costo = $_POST['costo'];

            // Calcular la diferencia de cantidad
            $diferenciaCantidad = $compra->cantidad - $cantidadAnterior;

            // Actualizar la compra y ajustar el stock
            if ($compra->actualizarCompra()) {
                // Actualizar el stock en base a la diferencia de cantidad
                $producto->actualizarStock($diferenciaCantidad);
                header("Location: ../views/compras/lista.php?mensaje=Compra actualizada exitosamente");
            } else {
                header("Location: ../views/compras/editar.php?id={$compra->id}&mensajeError=Error al actualizar la compra.");
            }
        } elseif (isset($_GET['id'])) {
            $compra->id = $_GET['id'];
            $compra->leerCompraPorId();
            include('../views/compras/editar.php');
        }
        break;

    // Si no hay acción definida, redirigir a la lista de compras
    default:
        header("Location: ../views/compras/lista.php");
        break;
}
?>
