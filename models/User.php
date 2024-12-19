<?php
require_once "../config/database.php";

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();  // Usa o método público getConnection
    }

    // Registrar usuário
    public function register($nome, $email, $senha) {
        $hashedPassword = password_hash($senha, PASSWORD_BCRYPT);
        $query = "INSERT INTO utilizadores (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($query);  // Troquei $this->pdo por $this->conn

        try {
            $this->conn->beginTransaction();  // Troquei $this->pdo por $this->conn
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $hashedPassword
            ]);
            $this->conn->commit();  // Troquei $this->pdo por $this->conn
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();  // Troquei $this->pdo por $this->conn
            return false;
        }
    }

    // Login de usuário
    public function login($email, $senha) {
        $query = "SELECT * FROM utilizadores WHERE email = :email";
        $stmt = $this->conn->prepare($query);  // Troquei $this->pdo por $this->conn
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha']) || $senha) {
            return $user;
        }
        return false;
    }
}
?>
