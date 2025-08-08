<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Compras en Color #0979b0 -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #0979b0; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-shopping-bag" style="color: white;"></i>
            Gestión de Compras
        </h2>
    </div>

    <!-- Botones de Acción (Mostrar solo para Administradores) -->
    <div class="d-flex justify-content-between mb-4">
        <a href="../../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>

        <!-- Mostrar botón de "Registrar Nueva Compra" solo si el usuario es Administrador -->
        <?php if ($_SESSION['role'] == 1): ?>
            <a href="crear.php" class="btn btn-primary" style="background-color: #0979b0; border-color: #0979b0;">
                <i class="fas fa-plus-circle"></i> Registrar Nueva Compra
            </a>
        <?php endif; ?>
    </div>

    <!-- Mensaje de éxito/error al agregar/editar/eliminar compras -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class='alert <?php echo (strpos($_GET['mensaje'], 'Error') !== false) ? 'alert-danger' : 'alert-success'; ?> text-center'>
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>

    <!-- Obtener la lista de compras desde la base de datos -->
    <?php
    require_once '../../models/Database.php';
    require_once '../../models/Compra.php';

    // Conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();
    $compra = new Compra($db);

    // Obtener la lista de compras
    $resultado = $compra->leerCompras();

    // Verificar si se encontraron compras
    if ($resultado->rowCount() > 0) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-striped table-bordered table-hover'>";
        echo "<thead class='thead-dark'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Proveedor</th>";
        echo "<th>Producto</th>";
        echo "<th>Cantidad</th>";
        echo "<th>Costo</th>";
        echo "<th>Fecha</th>";
        if ($_SESSION['role'] == 1) {
            echo "<th>Acciones</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Mostrar las compras
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$proveedor}</td>";
            echo "<td>{$producto}</td>";
            echo "<td>{$cantidad}</td>";
            echo "<td>S/ {$costo}</td>";
            echo "<td>{$fecha}</td>";
            
            // Mostrar las acciones solo para Administradores
            if ($_SESSION['role'] == 1) {
                echo "<td>";
                echo "<a href='editar.php?id={$id}' class='btn btn-warning m-1'><i class='fas fa-edit'></i> Editar</a>";
                echo "<a href='../../controllers/compraController.php?action=eliminar&id={$id}' class='btn btn-danger m-1' onclick='return confirm(\"¿Estás seguro de eliminar esta compra?\");'><i class='fas fa-trash'></i> Eliminar</a>";
                echo "</td>";
            }
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-info text-center'>No se encontraron compras registradas.</div>";
    }
    ?>
</div>

<!-- Incluir el pie de página -->
<?php include '../../partials/footer.php'; ?>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
