<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir la conexión a la base de datos y el modelo Proveedor
require_once '../../models/Database.php';
require_once '../../models/Proveedor.php';

$database = new Database();
$db = $database->getConnection();
$proveedor = new Proveedor($db);
$resultado = $proveedor->leerProveedores();

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Proveedor y Color Coincidente -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #17a2b8; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-truck" style="color: white;"></i> <!-- Ícono con color blanco dentro del encabezado -->
            Lista de Proveedores
        </h2>
    </div>

    <!-- Botones de Acción (Mostrar solo para Administradores) -->
    <div class="d-flex justify-content-between mb-4">
        <a href="../../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>

        <!-- Mostrar botón de "Crear Nuevo Proveedor" solo si el usuario es Administrador -->
        <?php if ($_SESSION['role'] == 1): ?>
            <a href="crear.php" class="btn btn-info" style="background-color: #17a2b8; border-color: #17a2b8;">
                <i class="fas fa-plus-circle"></i> Crear Nuevo Proveedor
            </a>
        <?php endif; ?>
    </div>

    <!-- Tabla de Proveedores -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <!-- Mostrar la columna de Acciones solo para Administradores -->
                    <?php if ($_SESSION['role'] == 1): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $fila['id']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['contacto']; ?></td>
                        <td><?php echo $fila['telefono']; ?></td>
                        <td><?php echo $fila['email']; ?></td>
                        <td><?php echo $fila['direccion']; ?></td>

                        <!-- Mostrar las acciones solo para Administradores -->
                        <?php if ($_SESSION['role'] == 1): ?>
                            <td>
                                <a href="editar.php?id=<?php echo $fila['id']; ?>" class="btn btn-info btn-sm m-1" style="background-color: #17a2b8; border-color: #17a2b8;">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="../../controllers/proveedorController.php?eliminar=<?php echo $fila['id']; ?>" class="btn btn-danger btn-sm m-1" onclick="return confirm('¿Estás seguro de eliminar este proveedor?');">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Incluir el pie de página -->
<?php include '../../partials/footer.php'; ?>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
