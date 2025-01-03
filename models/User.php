<?php
require_once __DIR__ . '/../config/database.php';
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

    public function listarUsuarios() {
        $stmt = $this->conn->prepare("SELECT * FROM sistema_senhas.utilizadores");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarUsuario($nome, $email, $senha, $tipoUtilizador, $idLocal = null) {
        $stmt = $this->conn->prepare("
            INSERT INTO sistema_senhas.utilizadores (nome, email, senha, tipo_utilizador, id_local) 
            VALUES (:nome, :email, :senha, :tipo_utilizador, :id_local)
        ");
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => password_hash($senha, PASSWORD_BCRYPT),
            ':tipo_utilizador' => $tipoUtilizador,
            ':id_local' => $idLocal !== '' ? $idLocal : null // Aceita nulo caso esteja vazio
        ]);
    }    

    public function removerUsuario($idUtilizador) {
        $stmt = $this->conn->prepare("DELETE FROM sistema_senhas.utilizadores WHERE id_utilizador = :id");
        return $stmt->execute([':id' => $idUtilizador]);
    }

    public function atualizarUsuario($idUtilizador, $nome, $email, $senha, $tipoUtilizador, $idLocal = null) {
        $query = "
            UPDATE sistema_senhas.utilizadores 
            SET nome = :nome, email = :email, senha = :senha, tipo_utilizador = :tipo_utilizador, id_local = :id_local 
            WHERE id_utilizador = :id_utilizador
        ";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_utilizador' => $idUtilizador,
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => password_hash($senha, PASSWORD_DEFAULT), // Hashing for security
            ':tipo_utilizador' => $tipoUtilizador,
            ':id_local' => $idLocal
        ]);
    }
    

}
?>
