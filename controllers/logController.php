<?php
require_once '../models/Database.php';
require_once '../models/Log.php';

$database = new Database();
$db = $database->getConnection();
$log = new Log($db);

// Esta función se llama cuando se necesita registrar un log en el sistema
function registrarAccion($usuario_id, $accion, $tabla, $registro_id) {
    global $log;
    $log->usuario_id = $usuario_id;
    $log->accion = $accion;
    $log->tabla = $tabla;
    $log->registro_id = $registro_id;

    return $log->registrarLog();
}

// Leer todos los logs de auditoría
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['accion']) && $_GET['accion'] == 'listar') {
    $resultado = $log->leerLogs();
    include('../views/logs/lista.php');
}
?>
