<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir la conexión a la base de datos y el modelo Producto
require_once '../../models/Database.php';
require_once '../../models/Producto.php';

// Crear instancia de la base de datos y el modelo Producto
$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);
$resultado = $producto->leerProductos();

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Producto y Color Coincidente -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #ffc107; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-box-open" style="color: white;"></i>
            Lista de Productos
        </h2>
    </div>

    <!-- Botones de Acción (Mostrar solo para Administradores) -->
    <div class="d-flex justify-content-between mb-4">
        <a href="../../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>

        <!-- Mostrar botón de "Crear Nuevo Producto" solo si el usuario es Administrador -->
        <?php if ($_SESSION['role'] == 1): ?>
            <a href="crear.php" class="btn btn-warning" style="background-color: #ffc107; border-color: #ffc107;">
                <i class="fas fa-plus-circle"></i> Crear Nuevo Producto
            </a>
        <?php endif; ?>
    </div>

    <!-- Tabla de Productos -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Imagen</th>
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
                        <td><?php echo $fila['descripcion']; ?></td>
                        <td>S/ <?php echo $fila['precio']; ?></td>
                        <td><?php echo $fila['stock']; ?></td>
                        <td><?php echo $fila['categoria']; ?></td>
                        <td>
                            <!-- Imagen con Tooltip y Modal -->
                            <a href="#" class="preview-link" data-toggle="modal" data-target="#modalImagen<?php echo $fila['id']; ?>">
                                <img src="/curso/assets/images/<?php echo $fila['imagen']; ?>" width="50" height="50" class="img-thumbnail" data-preview="/curso/assets/images/<?php echo $fila['imagen']; ?>">
                            </a>
                        </td>

                        <!-- Mostrar acciones solo para Administradores -->
                        <?php if ($_SESSION['role'] == 1): ?>
                            <td>
                                <a href="editar.php?id=<?php echo $fila['id']; ?>" class="btn btn-warning btn-sm m-1" style="background-color: #ffcc00; border-color: #ffcc00;">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="../../controllers/productoController.php?eliminar=<?php echo $fila['id']; ?>" class="btn btn-danger btn-sm m-1" onclick="return confirm('¿Estás seguro de eliminar este producto?');">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>

                    <!-- Modal para Visualizar Imagen -->
                    <div class="modal fade" id="modalImagen<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Vista de Producto: <?php echo $fila['nombre']; ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="/curso/assets/images/<?php echo $fila['imagen']; ?>" class="img-fluid">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Incluir el pie de página -->
<?php include '../../partials/footer.php'; ?>

<!-- Scripts de Bootstrap, jQuery y Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Estilo personalizado para la vista previa -->
<style>
    .img-preview {
        position: absolute;
        display: none;
        border: 1px solid #ddd;
        padding: 5px;
        background: white;
        z-index: 1000;
        width: 300px;
        height: 300px;
    }
    .img-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>

<!-- JavaScript para mostrar la vista previa -->
<script>
    $(document).ready(function () {
        // Crear el contenedor de la vista previa
        $('body').append('<div id="img-preview" class="img-preview"><img src="" alt="Vista Previa"></div>');

        // Mostrar la vista previa al pasar el mouse
        $('.preview-link img').hover(function (e) {
            let src = $(this).attr('data-preview');
            $('#img-preview img').attr('src', src);
            $('#img-preview').css({
                'display': 'block',
                'top': e.pageY + 10 + 'px',
                'left': e.pageX + 10 + 'px'
            });
        }, function () {
            $('#img-preview').hide();
        });

        // Mover la vista previa junto con el mouse
        $('.preview-link img').mousemove(function (e) {
            $('#img-preview').css({
                'top': e.pageY + 10 + 'px',
                'left': e.pageX + 10 + 'px'
            });
        });
    });
</script>
</body>
</html>
