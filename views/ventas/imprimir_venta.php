<?php 
require_once '../../models/Database.php';
require_once '../../models/Venta.php';
require_once '../../models/Cliente.php';
require_once '../../models/Usuario.php';
require_once '../../models/Producto.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $venta = new Venta($db);
    $cliente = new Cliente($db);
    $usuario = new Usuario($db);
    $producto = new Producto($db);

    // Asignar el ID de la venta
    $venta->id = $_GET['id'];

    // Verificar si se puede obtener la venta por ID
    $resultado = $venta->leerVentaPorID();
    if ($resultado && is_object($resultado)) {
        $detalle_venta = $resultado->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Error: No se pudo obtener la venta.";
        exit();
    }

    if (!$detalle_venta) {
        echo "Error: Venta no encontrada.";
        exit();
    }

    // Obtener los datos del cliente
    $datos_cliente = $cliente->obtenerClientePorId($detalle_venta['cliente_id']);
    if (!$datos_cliente) {
        echo "Error: Cliente no encontrado.";
        exit();
    }

    // Obtener los datos del usuario
    $usuario->id = $detalle_venta['usuario_id'];
    $resultado_usuario = $usuario->leerUsuarioPorID();
    if ($resultado_usuario && is_object($resultado_usuario)) {
        $datos_usuario = $resultado_usuario->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Error: No se pudo obtener el usuario.";
        exit();
    }

    // Obtener los datos del producto
    $producto->id = $detalle_venta['producto_id'];
    $datos_producto = $producto->obtenerProductoPorId();  // Ahora esto devuelve un array asociativo
    if (!$datos_producto) {
        echo "Error: No se pudo obtener el producto.";
        exit();
    }

    // Asignación de los datos del producto
    $nombre_producto = $datos_producto['nombre'];
    $cantidad = $detalle_venta['cantidad'];
    $precio_unitario = $detalle_venta['precio'];
    $subtotal = $detalle_venta['total'];
    $monto_igv = $detalle_venta['monto_igv'];
    $total_con_igv = $subtotal + $monto_igv;
} else {
    echo "Error: ID de venta no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Detalle de Venta</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        h2 {
            color: #dc3545; /* Mantener el color rojo */
            font-weight: bold;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: #dc3545; /* Fondo rojo */
            color: white; /* Texto blanco */
        }
        .section-title {
            background-color: #dc3545; /* Título con fondo rojo */
            color: white; /* Texto blanco */
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-table {
            margin-bottom: 30px;
        }
        .btn-print {
            margin-top: 30px;
        }
        .footer-info {
            text-align: right;
            font-size: 14px;
            color: #555;
        }
        /* Estilos adicionales */
        .container h4 {
            border-bottom: 2px solid #dc3545;
            padding-bottom: 5px;
            color: #dc3545;
            margin-bottom: 15px;
        }
        .btn-print button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-print button:hover {
            background-color: #a71d2a;
        }
        table td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="section-title">
            <h2>Detalle de la Venta</h2>
        </div>

        <!-- Información del Cliente -->
        <h4>Datos del Cliente</h4>
        <table class="info-table">
            <tr>
                <th>DNI</th>
                <td><?php echo htmlspecialchars($datos_cliente['dni']); ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo htmlspecialchars($datos_cliente['nombre']); ?></td>
            </tr>
        </table>

        <!-- Información de la Venta -->
        <h4>Información de la Venta</h4>
        <table>
            <tr>
                <th>ID de Venta</th>
                <td><?php echo htmlspecialchars($detalle_venta['id']); ?></td>
            </tr>
            <tr>
                <th>Producto</th>
                <td><?php echo htmlspecialchars($nombre_producto); ?></td>
            </tr>
            <tr>
                <th>Cantidad</th>
                <td><?php echo $cantidad; ?></td>
            </tr>
            <tr>
                <th>Precio Unitario (S/)</th>
                <td>S/ <?php echo number_format($precio_unitario, 2); ?></td>
            </tr>
            <tr>
                <th>Subtotal a Pagar (Sin IGV) (S/)</th>
                <td>S/ <?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <tr>
                <th>Monto del IGV (18%)</th>
                <td>S/ <?php echo number_format($monto_igv, 2); ?></td>
            </tr>
            <tr>
                <th>Precio Total con IGV (S/)</th>
                <td>S/ <?php echo number_format($total_con_igv, 2); ?></td>
            </tr>
        </table>

        <!-- Información del Usuario -->
        <h4>Registrado por</h4>
        <table class="info-table">
            <tr>
                <th>Nombre de Usuario</th>
                <td><?php echo htmlspecialchars($datos_usuario['nombre']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($datos_usuario['email']); ?></td>
            </tr>
        </table>

        <!-- Botón de impresión -->
        <div class="btn-print">
            <button onclick="window.print();">Imprimir</button>
        </div>
    </div>
</body>
</html>
