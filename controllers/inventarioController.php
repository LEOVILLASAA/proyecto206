<?php
require_once '../models/Database.php';
require_once '../models/Inventario.php';

$database = new Database();
$db = $database->getConnection();
$inventario = new Inventario($db);

// Registrar un movimiento de inventario (entrada o salida)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrarMovimiento'])) {
    $inventario->producto_id = $_POST['producto_id'];
    $inventario->cantidad = $_POST['cantidad'];
    $inventario->tipo_movimiento = $_POST['tipo_movimiento'];
    $inventario->usuario_id = $_POST['usuario_id'];

    if ($inventario->registrarMovimiento()) {
        header("Location: ../views/inventarios/lista.php?mensaje=Movimiento registrado exitosamente");
    } else {
        echo "Error al registrar el movimiento.";
    }
}
?>
