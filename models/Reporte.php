<?php
require_once 'Database.php';

class Reporte {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Reporte de Ventas
    public function reporteVentas() {
        $query = "SELECT v.id, p.nombre AS producto, v.cantidad, v.precio, v.total, c.nombre AS cliente, v.fecha 
                  FROM ventas v
                  LEFT JOIN productos p ON v.producto_id = p.id
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  ORDER BY v.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reporte de Compras
    public function reporteCompras() {
        $query = "SELECT c.id, p.nombre AS producto, pr.nombre AS proveedor, c.cantidad, c.costo, c.fecha 
                  FROM compras c
                  LEFT JOIN productos p ON c.producto_id = p.id
                  LEFT JOIN proveedores pr ON c.proveedor_id = pr.id
                  ORDER BY c.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reporte de Stock de Productos
    public function reporteStockProductos() {
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, c.nombre AS categoria 
                  FROM productos p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  ORDER BY p.nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reporte de Inventario (Entradas y Salidas)
    public function reporteInventario() {
        $query = "SELECT i.id, p.nombre AS producto, i.cantidad, i.tipo_movimiento, u.nombre AS usuario, i.fecha 
                  FROM inventarios i
                  LEFT JOIN productos p ON i.producto_id = p.id
                  LEFT JOIN usuarios u ON i.usuario_id = u.id
                  ORDER BY i.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reporte General de Productos por Categoría
    public function reporteProductosPorCategoria() {
        $query = "SELECT c.nombre AS categoria, COUNT(p.id) AS total_productos 
                  FROM productos p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  GROUP BY c.nombre
                  ORDER BY c.nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reporte General de Ventas por Mes
    public function reporteVentasPorMes() {
        $query = "SELECT DATE_FORMAT(v.fecha, '%Y-%m') AS mes, SUM(v.total) AS total_ventas
                  FROM ventas v
                  GROUP BY DATE_FORMAT(v.fecha, '%Y-%m')
                  ORDER BY DATE_FORMAT(v.fecha, '%Y-%m') DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Reporte General de Compras por Mes
    public function reporteComprasPorMes() {
        $query = "SELECT DATE_FORMAT(c.fecha, '%Y-%m') AS mes, SUM(c.costo) AS total_compras
                  FROM compras c
                  GROUP BY DATE_FORMAT(c.fecha, '%Y-%m')
                  ORDER BY DATE_FORMAT(c.fecha, '%Y-%m') DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
