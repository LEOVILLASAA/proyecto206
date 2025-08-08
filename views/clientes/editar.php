<?php
// Incluir el encabezado
include '../../partials/header.php'; 

// Incluir el archivo de conexión y el modelo Cliente
include '../../models/Database.php';
include '../../models/Cliente.php';

// Crear una instancia de la base de datos y obtener la conexión PDO
$database = new Database();
$conexion = $database->getConnection();

// Crear una instancia del modelo Cliente usando la conexión PDO
$clienteModel = new Cliente($conexion);

// Obtener el ID del cliente a editar desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : die('Error: No se proporcionó ID.');

// Obtener los datos del cliente por su ID
$cliente = $clienteModel->obtenerClientePorId($id);

// Verificar si el cliente existe
if (!$cliente) {
    die('Error: No se encontró el cliente con el ID especificado.');
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Clientes
        </a>
    </div>

    <h2 class="text-center mb-4">Editar Cliente</h2>
    <form action="../../controllers/clienteController.php?accion=editar" method="POST">
        <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
        <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" name="dni" class="form-control" value="<?php echo $cliente['dni']; ?>" required>
        </div>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $cliente['nombre']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $cliente['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?php echo $cliente['telefono']; ?>" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?php echo $cliente['direccion']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
    </form>
</div>

<?php include '../../partials/footer.php'; ?>
