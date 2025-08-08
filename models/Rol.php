<?php
require_once 'Database.php';

class Rol {
    private $conn;
    private $table_name = "roles";

    public $id;
    public $nombre;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function leerRoles() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function crearRol() {
        $query = "INSERT INTO " . $this->table_name . " (nombre) VALUES (:nombre)";
        $stmt = $this->conn->prepare($query);
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $stmt->bindParam(":nombre", $this->nombre);
        return $stmt->execute();
    }
}
?>
