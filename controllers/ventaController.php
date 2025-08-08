<?php
require_once '../models/Database.php';
require_once '../models/Venta.php';
require_once '../models/DetalleVenta.php'; // Incluir el modelo de DetalleVenta

$database = new Database();
$db = $database->getConnection();
$venta = new Venta($db);

// Registrar una nueva venta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crearVenta'])) {
    // Validar que el cliente esté correctamente seleccionado
    if (empty($_POST['cliente_id']) || !is_numeric($_POST['cliente_id'])) {
        header("Location: ../views/ventas/crear.php?error=Seleccione un cliente válido.");
        exit();
    }

    // Validar que el producto esté correctamente seleccionado y los demás campos
    if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id']) ||
        empty($_POST['cantidad']) || !is_numeric($_POST['cantidad']) ||
        empty($_POST['ganancia']) || !is_numeric($_POST['ganancia']) ||
        empty($_POST['subtotal']) || !is_numeric($_POST['subtotal']) ||
        empty($_POST['monto_igv']) || !is_numeric($_POST['monto_igv']) ||
        empty($_POST['precio_total']) || !is_numeric($_POST['precio_total']) ||
        empty($_POST['usuario_id']) || !is_numeric($_POST['usuario_id'])) {
        header("Location: ../views/ventas/crear.php?error=Complete todos los campos correctamente.");
        exit();
    }

    // Obtener los valores del formulario
    $cliente_id = $_POST['cliente_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['subtotal'] / $cantidad; // Obtener el precio unitario del formulario
    $ganancia = $_POST['ganancia'];
    $subtotal = $_POST['subtotal'];
    $monto_igv = $_POST['monto_igv'];
    $precio_total = $_POST['precio_total'];
    $usuario_id = $_POST['usuario_id'];

    // Asignar los valores del formulario a las propiedades de la clase Venta
    $venta->cliente_id = $cliente_id;
    $venta->producto_id = $producto_id;
    $venta->cantidad = $cantidad;
    $venta->precio = $precio; // Asignar el precio unitario calculado
    $venta->ganancia = $ganancia; // Asignar la ganancia
    $venta->monto_igv = $monto_igv; // Monto del IGV
    $venta->total = $precio_total; // Precio total con IGV
    $venta->usuario_id = $usuario_id;

    // Insertar la venta en la tabla `ventas`
    if ($venta->crearVenta()) {
        // Obtener el ID de la venta recién creada
        $venta_id = $db->lastInsertId();

        // Crear un registro en la tabla `detalle_ventas`
        $detalle_venta = new DetalleVenta($db);
        $detalle_venta->venta_id = $venta_id;
        $detalle_venta->producto_id = $producto_id;
        $detalle_venta->cantidad = $cantidad;
        $detalle_venta->precio = $precio; // Precio unitario
        $detalle_venta->ganancia = $ganancia;

        // Insertar el detalle de la venta en la tabla `detalle_ventas`
        if ($detalle_venta->crearDetalleVenta()) {
            header("Location: ../views/ventas/lista.php?mensaje=Venta registrada exitosamente");
            exit();
        } else {
            header("Location: ../views/ventas/crear.php?error=Error al registrar el detalle de la venta.");
            exit();
        }
    } else {
        header("Location: ../views/ventas/crear.php?error=Error al registrar la venta.");
        exit();
    }
}

// Actualizar una venta existente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizarVenta'])) {
    if (empty($_POST['id']) || empty($_POST['cliente_id']) || empty($_POST['producto_id']) || empty($_POST['cantidad']) || empty($_POST['ganancia']) || empty($_POST['precio_total'])) {
        header("Location: ../views/ventas/editar.php?id={$_POST['id']}&error=Complete todos los campos correctamente.");
        exit();
    }

    // Asignar valores a las propiedades de la clase Venta
    $venta->id = $_POST['id'];
    $venta->producto_id = $_POST['producto_id'];
    $venta->cliente_id = $_POST['cliente_id'];
    $venta->cantidad = $_POST['cantidad'];
    $venta->precio = $_POST['subtotal'] / $_POST['cantidad']; // Obtener el precio unitario correcto
    $venta->ganancia = $_POST['ganancia'];
    $venta->monto_igv = $_POST['monto_igv'];
    $venta->total = $_POST['precio_total'];

    // Actualizar la venta en la base de datos
    if ($venta->actualizarVenta()) {
        header("Location: ../views/ventas/lista.php?mensaje=Venta actualizada correctamente");
        exit();
    } else {
        header("Location: ../views/ventas/editar.php?id={$venta->id}&error=Error al actualizar la venta.");
        exit();
    }
}

// Eliminar una venta existente
if (isset($_GET['eliminar'])) {
    if (empty($_GET['eliminar']) || !is_numeric($_GET['eliminar'])) {
        header("Location: ../views/ventas/lista.php?error=ID de venta inválido.");
        exit();
    }

    $venta->id = $_GET['eliminar'];

    // Intentar eliminar la venta
    if ($venta->eliminarVenta()) {
        header("Location: ../views/ventas/lista.php?mensaje=Venta eliminada exitosamente");
        exit();
    } else {
        header("Location: ../views/ventas/lista.php?error=Error: No se pudo eliminar la venta.");
        exit();
    }
}
?>
