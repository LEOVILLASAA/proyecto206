<?php
require_once 'Database.php';

class Producto {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $categoria_id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para leer todos los productos con sus respectivas categorías
    public function leerProductos() {
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, p.imagen, c.nombre as categoria 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  ORDER BY p.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para obtener un producto por su ID
    public function obtenerProductoPorId() {
        $query = "SELECT id, nombre, descripcion, precio, stock, categoria_id, imagen 
                  FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);  // Asignar el parámetro ID

        if ($stmt->execute()) {
            // Verificar si se encontraron registros
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);  // Devolver el producto como array asociativo
            } else {
                return false;  // No se encontró el producto
            }
        } else {
            return false;  // Error en la consulta
        }
    }

    // Función para crear un nuevo producto
    public function crearProducto() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (categoria_id, nombre, descripcion, precio, stock, imagen) 
                  VALUES (:categoria_id, :nombre, :descripcion, :precio, :stock, :imagen)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar entradas
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));

        // Asignar parámetros
        $stmt->bindParam(":categoria_id", $this->categoria_id);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":imagen", $this->imagen);

        return $stmt->execute();
    }

    // Función para actualizar un producto existente
    public function actualizarProducto() {
        $query = "UPDATE " . $this->table_name . " 
                  SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, 
                      precio = :precio, stock = :stock, imagen = :imagen 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar entradas
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Asignar parámetros
        $stmt->bindParam(":categoria_id", $this->categoria_id);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Función para actualizar el stock de un producto
    public function actualizarStock($cantidad) {
        $query = "UPDATE " . $this->table_name . " 
                  SET stock = stock + :cantidad 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Asignar parámetros
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Función para eliminar un producto
    public function eliminarProducto() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
