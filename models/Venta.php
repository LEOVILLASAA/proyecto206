<?php
class Venta {
    private $conexion;
    private $table_name = "ventas";

    // Propiedades de la venta
    public $id;
    public $producto_id;
    public $cantidad;
    public $precio; // Precio del producto en ventas
    public $ganancia; // Ganancia por unidad
    public $monto_igv;
    public $total;
    public $usuario_id;
    public $cliente_id;
    public $fecha;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Método para leer las ventas con la información del cliente y del producto
    public function leerVentas() {
        $query = "
            SELECT 
                v.id, 
                c.dni AS cliente_dni, 
                c.nombre AS cliente_nombre, 
                p.nombre AS producto, 
                p.precio AS precio_producto,  
                v.ganancia,  
                v.cantidad, 
                v.precio, -- Precio base en la tabla de ventas
                (v.cantidad * v.precio) AS subtotal,  
                v.monto_igv,  
                v.total,  
                v.fecha,  
                u.nombre AS usuario  
            FROM " . $this->table_name . " v
            LEFT JOIN productos p ON v.producto_id = p.id
            LEFT JOIN clientes c ON v.cliente_id = c.id
            LEFT JOIN usuarios u ON v.usuario_id = u.id
            ORDER BY v.fecha DESC";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para leer una venta específica por su ID
    public function leerVentaPorID() {
        $query = "
            SELECT 
                v.id, 
                v.producto_id, 
                v.cantidad, 
                v.precio, 
                v.ganancia, 
                v.monto_igv, 
                v.total, 
                v.usuario_id, 
                v.cliente_id, 
                v.fecha,
                p.nombre AS producto, 
                c.dni AS cliente_dni, 
                u.nombre AS usuario  
            FROM " . $this->table_name . " v
            LEFT JOIN productos p ON v.producto_id = p.id
            LEFT JOIN clientes c ON v.cliente_id = c.id
            LEFT JOIN usuarios u ON v.usuario_id = u.id
            WHERE v.id = :id
            LIMIT 1";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        // Retornar el statement para que se pueda utilizar el método fetch() en `editar.php`
        return $stmt;
    }

    // Método para crear una nueva venta
    public function crearVenta() {
        $query = "INSERT INTO " . $this->table_name . " (producto_id, cantidad, precio, ganancia, monto_igv, total, usuario_id, cliente_id)
                  VALUES (:producto_id, :cantidad, :precio, :ganancia, :monto_igv, :total, :usuario_id, :cliente_id)";
        $stmt = $this->conexion->prepare($query);

        // Limpiar datos
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->ganancia = htmlspecialchars(strip_tags($this->ganancia));
        $this->monto_igv = htmlspecialchars(strip_tags($this->monto_igv));
        $this->total = htmlspecialchars(strip_tags($this->total));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id)); 

        // Vincular parámetros
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":ganancia", $this->ganancia);
        $stmt->bindParam(":monto_igv", $this->monto_igv);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":cliente_id", $this->cliente_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->errorInfo()[2]);
            return false;
        }
    }

    // Método para actualizar una venta existente
    public function actualizarVenta() {
        $query = "UPDATE " . $this->table_name . " 
                  SET producto_id = :producto_id, cliente_id = :cliente_id, cantidad = :cantidad, precio = :precio, ganancia = :ganancia, monto_igv = :monto_igv, total = :total 
                  WHERE id = :id";
        
        $stmt = $this->conexion->prepare($query);

        // Limpiar datos
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->ganancia = htmlspecialchars(strip_tags($this->ganancia));
        $this->monto_igv = htmlspecialchars(strip_tags($this->monto_igv));
        $this->total = htmlspecialchars(strip_tags($this->total));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":ganancia", $this->ganancia);
        $stmt->bindParam(":monto_igv", $this->monto_igv);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->errorInfo()[2]);
            return false;
        }
    }

    // Método para eliminar una venta
    public function eliminarVenta() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conexion->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->errorInfo()[2]);
            return false;
        }
    }
}
?>
