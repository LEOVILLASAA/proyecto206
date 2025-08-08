<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reportes</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Generar Reportes</h1>
    <form action="../controllers/reporteController.php" method="POST">
        <label for="modulo">Seleccionar Módulo:</label>
        <select name="modulo" id="modulo" required>
            <option value="usuarios">Usuarios</option>
            <option value="categorias">Categorías</option>
            <option value="productos">Productos</option>
            <option value="ventas">Ventas</option>
            <option value="compras">Compras</option>
        </select><br>

        <label for="tipo_reporte">Formato del Reporte:</label>
        <select name="tipo_reporte" id="tipo_reporte" required>
            <option value="pdf">PDF</option>
            <option value="excel">Excel</option>
        </select><br>

        <input type="submit" name="generarReporte" value="Generar Reporte">
    </form>
</body>
</html>
