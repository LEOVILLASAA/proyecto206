<?php
require_once 'Database.php';
require_once 'Producto.php'; // Incluir el modelo Producto para actualizar el stock

class Compra {
    private $conn;
    private $table_name = "compras";

    public $id;
    public $proveedor_id;
    public $producto_id;
    public $cantidad;
    public $costo;
    public $fecha;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para leer todas las compras
    public function leerCompras() {
        $query = "SELECT c.id, c.cantidad, c.costo, c.fecha, 
                         p.nombre as producto, pr.nombre as proveedor 
                  FROM " . $this->table_name . " c 
                  LEFT JOIN productos p ON c.producto_id = p.id 
                  LEFT JOIN proveedores pr ON c.proveedor_id = pr.id 
                  ORDER BY c.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Función para crear una nueva compra y actualizar el stock del producto
    public function crearCompra() {
        try {
            // Iniciar transacción
            $this->conn->beginTransaction();

            // Insertar la nueva compra
            $query = "INSERT INTO " . $this->table_name . " (proveedor_id, producto_id, cantidad, costo) 
                      VALUES (:proveedor_id, :producto_id, :cantidad, :costo)";
            $stmt = $this->conn->prepare($query);

            // Sanitizar entradas
            $this->proveedor_id = htmlspecialchars(strip_tags($this->proveedor_id));
            $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
            $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
            $this->costo = htmlspecialchars(strip_tags($this->costo));

            // Asignar parámetros
            $stmt->bindParam(":proveedor_id", $this->proveedor_id);
            $stmt->bindParam(":producto_id", $this->producto_id);
            $stmt->bindParam(":cantidad", $this->cantidad);
            $stmt->bindParam(":costo", $this->costo);

            // Ejecutar consulta de inserción
            $stmt->execute();

            // Actualizar el stock del producto
            $producto = new Producto($this->conn);
            $producto->id = $this->producto_id;

            // Llamar a la función actualizarStock con la cantidad comprada
            if ($producto->actualizarStock($this->cantidad)) {
                // Confirmar transacción si todo es correcto
                $this->conn->commit();
                return true;
            } else {
                // Revertir transacción en caso de error
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de una excepción
            $this->conn->rollBack();
            return false;
        }
    }

    // Función para actualizar una compra existente
    public function actualizarCompra() {
        try {
            // Iniciar transacción
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table_name . " 
                      SET proveedor_id = :proveedor_id, producto_id = :producto_id, cantidad = :cantidad, costo = :costo 
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            // Sanitizar entradas
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->proveedor_id = htmlspecialchars(strip_tags($this->proveedor_id));
            $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
            $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
            $this->costo = htmlspecialchars(strip_tags($this->costo));

            // Asignar parámetros
            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":proveedor_id", $this->proveedor_id);
            $stmt->bindParam(":producto_id", $this->producto_id);
            $stmt->bindParam(":cantidad", $this->cantidad);
            $stmt->bindParam(":costo", $this->costo);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Si se actualizó correctamente, confirmar la transacción
                $this->conn->commit();
                return true;
            } else {
                // Revertir transacción en caso de error
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de una excepción
            $this->conn->rollBack();
            return false;
        }
    }

    // Función para eliminar una compra
    public function eliminarCompra() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar entrada
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Asignar parámetros
        $stmt->bindParam(":id", $this->id);

        // Ejecutar la consulta
        return $stmt->execute();
    }

    // Función para leer una compra específica (por ID)
    public function leerCompraPorId() {
        $query = "SELECT c.id, c.cantidad, c.costo, c.fecha, 
                         c.proveedor_id, c.producto_id
                  FROM " . $this->table_name . " c 
                  WHERE c.id = :id 
                  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // Asignar parámetro
        $stmt->bindParam(":id", $this->id);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el registro
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retornar el registro como un array
    }
}
