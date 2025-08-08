<?php
require_once 'Database.php';

class Inventario {
    private $conn;
    private $table_name = "inventarios";

    public $id;
    public $producto_id;
    public $cantidad;
    public $tipo_movimiento; // 'entrada' o 'salida'
    public $usuario_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para leer todos los movimientos de inventario
    public function leerMovimientos() {
        $query = "SELECT i.id, i.cantidad, i.tipo_movimiento, i.fecha, 
                         p.nombre as producto, u.nombre as usuario 
                  FROM " . $this->table_name . " i 
                  LEFT JOIN productos p ON i.producto_id = p.id 
                  LEFT JOIN usuarios u ON i.usuario_id = u.id 
                  ORDER BY i.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Función para registrar un movimiento de inventario (entrada/salida)
    public function registrarMovimiento() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (producto_id, cantidad, tipo_movimiento, usuario_id) 
                  VALUES (:producto_id, :cantidad, :tipo_movimiento, :usuario_id)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar entradas
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->tipo_movimiento = htmlspecialchars(strip_tags($this->tipo_movimiento));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));

        // Asignar parámetros
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":tipo_movimiento", $this->tipo_movimiento);
        $stmt->bindParam(":usuario_id", $this->usuario_id);

        return $stmt->execute();
    }
}
?>
