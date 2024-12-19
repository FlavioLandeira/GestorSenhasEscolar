<?php
class Database {
    private $host = "localhost";
    private $dbname = "sistema_senhas";
    private $username = "root";
    private $password = "";
    private static $conn = null;

    // Método para criar uma nova conexão (interno)
    public function connect() {
        try {
            $conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Erro na conexão com a base de dados: " . $e->getMessage());
        }
    }

    // Método estático para obter a conexão
    public static function getConnection() {
        if (self::$conn === null) {
            $database = new Database();
            self::$conn = $database->connect();
        }
        return self::$conn;
    }
}

?>
