<?php
require_once 'Database.php';

class Proveedor {
    private $conn;
    private $table_name = "proveedores";

    public $id;
    public $nombre;
    public $contacto;
    public $telefono;
    public $email;
    public $direccion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para leer todos los proveedores
    public function leerProveedores() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para obtener un proveedor por su ID
    public function obtenerProveedorPorId() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        // Retornar un solo proveedor
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Función para crear un nuevo proveedor
    public function crearProveedor() {
        $query = "INSERT INTO " . $this->table_name . " (nombre, contacto, telefono, email, direccion) 
                  VALUES (:nombre, :contacto, :telefono, :email, :direccion)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar entradas
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->contacto = htmlspecialchars(strip_tags($this->contacto));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));

        // Asignar parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);

        return $stmt->execute();
    }

    // Función para actualizar un proveedor existente
    public function actualizarProveedor() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, contacto = :contacto, telefono = :telefono, email = :email, direccion = :direccion 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar entradas
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->contacto = htmlspecialchars(strip_tags($this->contacto));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Asignar parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Función para eliminar un proveedor
    public function eliminarProveedor() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
