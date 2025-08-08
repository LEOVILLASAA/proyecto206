<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";  // Nombre de la tabla en la base de datos

    // Propiedades del usuario
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol_id;

    // Constructor con la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener un usuario por su email (usado en la autenticación)
    public function getUserByEmail($email) {
        $query = "SELECT id, nombre, email, password, rol_id FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->rol_id = $row['rol_id'];
            return $row;
        }
        return false;
    }

    // Método para leer todos los usuarios
    public function leerUsuarios() {
        $query = "SELECT u.id, u.nombre, u.email, r.nombre AS rol 
                  FROM " . $this->table_name . " u 
                  LEFT JOIN roles r ON u.rol_id = r.id 
                  ORDER BY u.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para obtener un usuario por ID (usado en la edición)
    public function getUserById($id) {
        $query = "SELECT id, nombre, email, password, rol_id FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Asignar los valores a las propiedades del objeto
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->rol_id = $row['rol_id'];
            return $row;
        }
        return false;
    }

    // Método para leer los datos de un usuario específico según el ID
    public function leerUsuarioPorID() {
        $query = "SELECT id, nombre, email FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        return $stmt;
    }

    // Método para crear un nuevo usuario en la base de datos
    public function crearUsuario() {
        $query = "INSERT INTO " . $this->table_name . " (nombre, email, password, rol_id) 
                  VALUES (:nombre, :email, :password, :rol_id)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar las entradas
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->rol_id = htmlspecialchars(strip_tags($this->rol_id));

        // Vincular los parámetros con los valores correspondientes
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password); // La contraseña ya debe estar encriptada
        $stmt->bindParam(':rol_id', $this->rol_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para actualizar un usuario existente
    public function actualizarUsuario() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, email = :email, password = :password, rol_id = :rol_id 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar las entradas
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->rol_id = htmlspecialchars(strip_tags($this->rol_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular los parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':rol_id', $this->rol_id);
        $stmt->bindParam(':id', $this->id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para eliminar un usuario
    public function eliminarUsuario() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular el ID
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        // Ejecutar la consulta y verificar si fue exitosa
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
