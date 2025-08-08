<?php 
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/auth/login.php");
    exit();
}

// Incluir la conexión a la base de datos y el modelo Venta
require_once '../../models/Database.php';
require_once '../../models/Venta.php';

$database = new Database();
$db = $database->getConnection();
$venta = new Venta($db);
$resultado = $venta->leerVentas();

// Incluir el encabezado
include '../../partials/header.php';
?>

<div class="container mt-5">
    <!-- Encabezado Personalizado con Ícono de Ventas -->
    <div class="text-center mb-4">
        <h2 class="d-inline" style="background-color: #dc3545; color: white; padding: 10px; border-radius: 5px;">
            <i class="fas fa-shopping-cart" style="color: white;"></i>
            Lista de Ventas Registradas
        </h2>
    </div>

    <!-- Mostrar mensaje si existe -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class='alert alert-success text-center'><?php echo htmlspecialchars($_GET['mensaje']); ?></div>
    <?php endif; ?>

    <!-- Botones de Acción (Mostrar solo para Administradores) -->
    <div class="d-flex justify-content-between mb-4">
        <a href="../../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>

        <!-- Mostrar botón de "Registrar Nueva Venta" solo si el usuario es Administrador -->
        <?php if ($_SESSION['role'] == 1): ?>
            <a href="crear.php" class="btn btn-danger">
                <i class="fas fa-plus-circle"></i> Registrar Nueva Venta
            </a>
        <?php endif; ?>
    </div>

    <!-- Tabla de Ventas -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 150px;">DNI del Cliente</th>
                    <th style="width: 350px;">Producto</th>
                    <th style="width: 80px;">Cantidad</th>
                    <th style="width: 120px;">Precio Unitario (S/)</th>
                    <th style="width: 150px;">Monto de Ganancia (S/)</th>
                    <th style="width: 150px;">Subtotal a Pagar (Sin IGV) (S/)</th>
                    <th style="width: 150px;">Monto del IGV (18%) (S/)</th>
                    <th style="width: 200px;">Precio Total con IGV (S/)</th>
                    <th style="width: 180px;">Usuario</th>
                    <th style="width: 250px;">Fecha</th>
                    <?php if ($_SESSION['role'] == 1): ?>
                        <th style="width: 320px;">Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $fila['id']; ?></td>
                        <td><?php echo htmlspecialchars($fila['cliente_dni']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($fila['producto'])); ?></td>
                        <td><?php echo $fila['cantidad']; ?></td>
                        <td>S/ <?php echo number_format($fila['precio_producto'], 2); ?></td>
                        <td>S/ <?php echo number_format($fila['ganancia'], 2); ?></td>

                        <?php
                        $subtotal = ($fila['precio_producto'] + $fila['ganancia']) * $fila['cantidad'];
                        $monto_igv = $subtotal * 0.18;
                        $total_con_igv = $subtotal + $monto_igv;
                        ?>

                        <td>S/ <?php echo number_format($subtotal, 2); ?></td>
                        <td>S/ <?php echo number_format($monto_igv, 2); ?></td>
                        <td>S/ <?php echo number_format($total_con_igv, 2); ?></td>
                        <td><?php echo htmlspecialchars($fila['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($fila['fecha']); ?></td>

                        <!-- Mostrar las acciones solo para Administradores -->
                        <?php if ($_SESSION['role'] == 1): ?>
                            <td>
                                <a href="editar.php?id=<?php echo $fila['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="../../controllers/ventaController.php?eliminar=<?php echo $fila['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta venta?');">Eliminar</a>
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#verVentaModal<?php echo $fila['id']; ?>">Ver</button>
                            </td>
                        <?php else: ?>
                            <td>
                                <!-- Solo mostrar el botón de Ver para usuarios sin permisos de administración -->
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#verVentaModal<?php echo $fila['id']; ?>">Ver</button>
                            </td>
                        <?php endif; ?>
                    </tr>

                    <!-- Modal para la Vista de la Venta con botón de Imprimir -->
                    <div class="modal fade" id="verVentaModal<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="verVentaLabel<?php echo $fila['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="verVentaLabel<?php echo $fila['id']; ?>">Detalles de la Venta</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ID de Venta:</strong> <?php echo $fila['id']; ?></p>
                                    <p><strong>DNI del Cliente:</strong> <?php echo htmlspecialchars($fila['cliente_dni']); ?></p>
                                    <p><strong>Producto:</strong> <?php echo nl2br(htmlspecialchars($fila['producto'])); ?></p>
                                    <p><strong>Cantidad:</strong> <?php echo $fila['cantidad']; ?></p>
                                    <p><strong>Precio Unitario (S/):</strong> S/ <?php echo number_format($fila['precio_producto'] + $fila['ganancia'], 2); ?></p>
                                    <p><strong>Subtotal a Pagar (Sin IGV) (S/):</strong> S/ <?php echo number_format($subtotal, 2); ?></p>
                                    <p><strong>Monto del IGV (18%):</strong> S/ <?php echo number_format($monto_igv, 2); ?></p>
                                    <p><strong>Precio Total con IGV (S/):</strong> S/ <?php echo number_format($total_con_igv, 2); ?></p>
                                    <p><strong>Usuario:</strong> <?php echo htmlspecialchars($fila['usuario']); ?></p>
                                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($fila['fecha']); ?></p>
                                </div>
                                <div class="modal-footer">
                                    <!-- Botón de Imprimir dentro del modal -->
                                    <a href="imprimir_venta.php?id=<?php echo $fila['id']; ?>" class="btn btn-info">
                                        <i class="fas fa-print"></i> Imprimir
                                    </a>
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
