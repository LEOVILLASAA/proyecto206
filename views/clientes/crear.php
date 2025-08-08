<?php include '../../partials/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Agregar Nuevo Cliente</h2>

    <!-- Mostrar mensaje de error si existe -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-danger text-center">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>

    <!-- Botón para regresar a la lista de clientes -->
    <div class="mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Clientes
        </a>
    </div>

    <!-- Formulario de creación de cliente -->
    <form action="../../controllers/clienteController.php?accion=crear" method="POST">
        <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" name="dni" id="dni" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control">
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <textarea name="direccion" id="direccion" class="form-control"></textarea>
        </div>

        <!-- Botones de Guardar y Cancelar -->
        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
        <a href="lista.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include '../../partials/footer.php'; ?>
