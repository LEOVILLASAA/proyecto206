<?php
require_once '../../models/Database.php';
require_once '../../models/Log.php';

$database = new Database();
$db = $database->getConnection();
$log = new Log($db);
$resultado = $log->leerLogs();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Auditoría</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <h1>Registro de Auditoría</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Acción</th>
            <th>Tabla</th>
            <th>ID del Registro</th>
            <th>Usuario</th>
            <th>Fecha</th>
        </tr>
        <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $fila['id']; ?></td>
                <td><?php echo $fila['accion']; ?></td>
                <td><?php echo $fila['tabla']; ?></td>
                <td><?php echo $fila['registro_id']; ?></td>
                <td><?php echo $fila['usuario']; ?></td>
                <td><?php echo $fila['fecha']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
