<?php
require_once '../../models/Database.php';
require_once '../../models/Inventario.php';

$database = new Database();
$db = $database->getConnection();
$inventario = new Inventario($db);
$resultado = $inventario->leerMovimientos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos de Inventario</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <h1>Movimientos de Inventario</h1>
    <a href="entrada.php">Registrar Entrada</a> | 
    <a href="salida.php">Registrar Salida</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Tipo</th>
            <th>Usuario</th>
            <th>Fecha</th>
        </tr>
        <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $fila['id']; ?></td>
                <td><?php echo $fila['producto']; ?></td>
                <td><?php echo $fila['cantidad']; ?></td>
                <td><?php echo $fila['tipo_movimiento']; ?></td>
                <td><?php echo $fila['usuario']; ?></td>
                <td><?php echo $fila['fecha']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

