<?php
class Cliente {
    private $conexion;
    private $table_name = "clientes";

    public $id;
    public $dni;
    public $nombre;
    public $email;
    public $telefono;
    public $direccion;

    // Constructor con la base de datos
    public function __construct($db) {
        $this->conexion = $db;
    }

    // Método para listar todos los clientes
    public function listarClientes() {
        $query = "SELECT id, dni, nombre, email, telefono, direccion FROM " . $this->table_name . " ORDER BY creado_en DESC";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para leer todos los clientes (similar a listarClientes)
    public function leerClientes() {
        $query = "SELECT id, dni, nombre FROM " . $this->table_name . " ORDER BY nombre";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para leer un cliente por su DNI
    public function buscarClientePorDNI($dni) {
        $query = "SELECT id, dni, nombre FROM " . $this->table_name . " WHERE dni = ? LIMIT 0,1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(1, $dni);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear un nuevo cliente
    public function crearCliente($dni, $nombre, $email, $telefono, $direccion) {
        if ($this->existeDNI($dni)) {
            return false; // Retornar falso si el DNI ya existe
        }

        try {
            $query = "INSERT INTO clientes (dni, nombre, email, telefono, direccion) VALUES (:dni, :nombre, :email, :telefono, :direccion)";
            $stmt = $this->conexion->prepare($query);

            // Asignar los valores de los parámetros
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al crear cliente: " . $e->getMessage();
            return false;
        }
    }

    // Método para verificar si un DNI ya existe en la base de datos
    public function existeDNI($dni) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE dni = :dni";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':dni', $dni);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] > 0;
        } catch (PDOException $e) {
            echo "Error al verificar DNI: " . $e->getMessage();
            return false;
        }
    }

    // Método para obtener un cliente por su ID
    public function obtenerClientePorId($id) {
        try {
            $query = "SELECT * FROM clientes WHERE id = :id";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener cliente: " . $e->getMessage();
            return null;
        }
    }

    // Método para actualizar un cliente
    public function actualizarCliente($id, $dni, $nombre, $email, $telefono, $direccion) {
        try {
            $query = "UPDATE clientes SET dni = :dni, nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion WHERE id = :id";
            $stmt = $this->conexion->prepare($query);

            // Asignar los valores de los parámetros
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar cliente: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar un cliente
    public function eliminarCliente($id) {
        try {
            $query = "DELETE FROM clientes WHERE id = :id";
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al eliminar cliente: " . $e->getMessage();
            return false;
        }
    }
}
?>
