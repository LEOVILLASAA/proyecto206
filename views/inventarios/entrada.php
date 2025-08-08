<?php
require_once '../../models/Database.php';
require_once '../../models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);
$productos = $producto->leerProductos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada de Inventario</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <h1>Registrar Entrada de Producto</h1>
    <form action="../../controllers/inventarioController.php" method="POST">
        <label for="producto_id">Producto:</label>
        <select name="producto_id" id="producto_id" required>
            <?php while ($fila = $productos->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $fila['id']; ?>"><?php echo $fila['nombre']; ?></option>
            <?php endwhile; ?>
        </select><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" required><br>

        <input type="hidden" name="tipo_movimiento" value="Entrada">
        <input type="hidden" name="usuario_id" value="1"> <!-- Cambiar este valor según el usuario actual -->
        
        <input type="submit" name="registrarMovimiento" value="Registrar Entrada">
    </form>
</body>
</html>
