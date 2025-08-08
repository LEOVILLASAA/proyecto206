<?php include '../../partials/header.php'; ?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Proveedor -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #17a2b8; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-truck" style="color: white;"></i>
            Crear Nuevo Proveedor
        </h2>
    </div>

    <!-- Botón de Retorno -->
    <div class="d-flex justify-content-start mb-4">
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista de Proveedores
        </a>
    </div>

    <!-- Formulario de Creación de Proveedor -->
    <form action="../../controllers/proveedorController.php" method="POST" class="card p-4 shadow-sm">
        <div class="form-group">
            <label for="nombre">Nombre del Proveedor:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del Proveedor" required>
        </div>

        <div class="form-group">
            <label for="contacto">Nombre del Contacto:</label>
            <input type="text" name="contacto" id="contacto" class="form-control" placeholder="Nombre del Contacto" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Correo Electrónico" required>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <textarea name="direccion" id="direccion" class="form-control" placeholder="Dirección" required></textarea>
        </div>

        <!-- Botones de Acción -->
        <button type="submit" name="crearProveedor" class="btn btn-info" style="background-color: #17a2b8; border-color: #17a2b8;">
            <i class="fas fa-save"></i> Crear Proveedor
        </button>
        <a href="lista.php" class="btn btn-secondary">
            <i class="fas fa-times-circle"></i> Cancelar
        </a>
    </form>
</div>

<!-- Incluir el pie de página -->
<?php include '../../partials/footer.php'; ?>
