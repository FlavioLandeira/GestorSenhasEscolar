<?php
class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createUser($name, $email, $password, $type, $localId) {
        $query = 'INSERT INTO utilizadores (nome, email, senha, tipo_utilizador, id_local) VALUES (:name, :email, :password, :type, :localId)';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':localId', $localId);

        return $stmt->execute();
    }

    public function getUserByEmail($email) {
        $query = 'SELECT * FROM utilizadores WHERE email = :email LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>