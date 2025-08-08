<?php
class DetalleVenta {
    private $conn;
    private $table_name = "detalle_ventas";

    public $id;
    public $venta_id;
    public $producto_id;
    public $cantidad;
    public $precio;
    public $ganancia;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para crear un nuevo detalle de venta
    public function crearDetalleVenta() {
        $query = "INSERT INTO " . $this->table_name . " (venta_id, producto_id, cantidad, precio, ganancia) 
                  VALUES (:venta_id, :producto_id, :cantidad, :precio, :ganancia)";

        $stmt = $this->conn->prepare($query);

        // Vincular los parámetros con los valores de las propiedades
        $stmt->bindParam(':venta_id', $this->venta_id);
        $stmt->bindParam(':producto_id', $this->producto_id);
        $stmt->bindParam(':cantidad', $this->cantidad);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':ganancia', $this->ganancia);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para leer el detalle de una venta específica según el ID de la venta
    public function leerDetallePorVentaID() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE venta_id = :venta_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':venta_id', $this->venta_id);

        // Ejecutar la consulta
        $stmt->execute();

        return $stmt;
    }
}
?>
