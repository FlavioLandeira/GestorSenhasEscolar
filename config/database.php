<?php
class Database {
    private $host = "localhost";
    private $dbname = "sistema_senhas";
    private $username = "root";
    private $password = "";
    private static $conn;

    // Connect method to create a new connection
    public function connect() {
        self::$conn = null;
        try {
            self::$conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão com a base de dados: " . $e->getMessage());
        }
        return self::$conn;
    }

    // Static method to get the connection
    public static function getConnection() {
        if (self::$conn === null) {
            $database = new Database();
            self::$conn = $database->connect();
        }
        return self::$conn;
    }
}
?>
