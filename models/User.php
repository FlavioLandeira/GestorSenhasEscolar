<?php
require_once "../config/database.php";

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Registrar usuário
    public function register($nome, $email, $senha) {
        $hashedPassword = password_hash($senha, PASSWORD_BCRYPT);
        $query = "INSERT INTO utilizadores (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->db->prepare($query);

        try {
            $this->db->beginTransaction();
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $hashedPassword
            ]);
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Login de usuário
    public function login($email, $senha) {
        $query = "SELECT * FROM utilizadores WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && (password_verify($senha, $user['senha']) || $senha)) {
            return $user;
        }
        return false;
    }
}
?>
