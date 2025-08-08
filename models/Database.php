<?php
class Database {
    private $host = "localhost";
    private $db_name = "inventario";
    private $username = "root";
    private $password = "";
    public $conn;

    // Obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Asegurar que PDO lance excepciones en caso de error
            $this->conn->exec("set names utf8"); // Establecer el juego de caracteres en utf8
        } catch(PDOException $exception) {
            echo "Error en la conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
