<?php
class Service {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createService($name, $price, $localId) {
        $query = 'INSERT INTO servicos (nome_servico, preco, id_local) VALUES (:name, :price, :localId)';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':localId', $localId);

        return $stmt->execute();
    }

    public function deleteService($serviceId) {
        $query = 'DELETE FROM servicos WHERE id_servico = :serviceId';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':serviceId', $serviceId);

        return $stmt->execute();
    }

    public function getStatistics() {
        $query = 'SELECT COUNT(*) as total, AVG(TIMESTAMPDIFF(MINUTE, data_hora_criacao, data_hora_atendimento)) as avg_time FROM senhas';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>