<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir los archivos de conexión y el modelo Cliente
include '../../partials/header.php'; 
include '../../models/Database.php';
include '../../models/Cliente.php';

// Crear una instancia de la base de datos y el modelo Cliente
$database = new Database();
$conexion = $database->getConnection();
$clienteModel = new Cliente($conexion);

// Obtener la lista de clientes desde la base de datos
$clientes = $clienteModel->listarClientes();
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Clientes -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #6c757d; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-address-book" style="color: white;"></i> <!-- Ícono de Gestión de Clientes -->
            Gestión de Clientes
        </h2>
    </div>

    <!-- Botones de Acción (Mostrar solo para Administradores) -->
    <div class="d-flex justify-content-between mb-4">
        <a href="../../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>

        <!-- Mostrar botón de "Agregar Cliente" solo si el usuario es Administrador -->
        <?php if ($_SESSION['role'] == 1): ?>
            <a href="crear.php" class="btn btn-secondary">
                <i class="fas fa-user-plus"></i> Agregar Cliente
            </a>
        <?php endif; ?>
    </div>

    <!-- Tabla de Clientes -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <!-- Mostrar columna de Acciones solo si el usuario es Administrador -->
                <?php if ($_SESSION['role'] == 1): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo $cliente['id']; ?></td>
                    <td><?php echo $cliente['dni']; ?></td>
                    <td><?php echo $cliente['nombre']; ?></td>
                    <td><?php echo $cliente['email']; ?></td>
                    <td><?php echo $cliente['telefono']; ?></td>
                    <td><?php echo $cliente['direccion']; ?></td>

                    <!-- Mostrar las acciones solo para Administradores -->
                    <?php if ($_SESSION['role'] == 1): ?>
                        <td>
                            <a href="editar.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning btn-sm m-1" style="background-color: #ffc107; border-color: #ffc107;">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="../../controllers/clienteController.php?accion=eliminar&id=<?php echo $cliente['id']; ?>" class="btn btn-danger btn-sm m-1" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No se encontraron clientes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../../partials/footer.php'; ?>
