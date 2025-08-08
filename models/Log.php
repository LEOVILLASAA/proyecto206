<?php
require_once 'Database.php';

class Log {
    private $conn;
    private $table_name = "logs";

    public $id;
    public $usuario_id;
    public $accion;
    public $tabla;
    public $registro_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para registrar un log de auditoría
    public function registrarLog() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (usuario_id, accion, tabla, registro_id) 
                  VALUES (:usuario_id, :accion, :tabla, :registro_id)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar entradas
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->accion = htmlspecialchars(strip_tags($this->accion));
        $this->tabla = htmlspecialchars(strip_tags($this->tabla));
        $this->registro_id = htmlspecialchars(strip_tags($this->registro_id));

        // Asignar parámetros
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":accion", $this->accion);
        $stmt->bindParam(":tabla", $this->tabla);
        $stmt->bindParam(":registro_id", $this->registro_id);

        return $stmt->execute();
    }

    // Función para leer todos los logs de auditoría
    public function leerLogs() {
        $query = "SELECT l.id, l.accion, l.tabla, l.registro_id, l.fecha, u.nombre as usuario 
                  FROM " . $this->table_name . " l 
                  LEFT JOIN usuarios u ON l.usuario_id = u.id 
                  ORDER BY l.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
