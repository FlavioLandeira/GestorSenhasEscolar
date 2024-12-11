<?php
class Queue {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function addToQueue($userId, $serviceId) {
        try {
            $this->conn->beginTransaction();

            $query = 'INSERT INTO senhas (id_utilizador, id_local, status, data_hora_criacao) VALUES (:userId, (SELECT id_local FROM servicos WHERE id_servico = :serviceId), "em_espera", NOW())';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':serviceId', $serviceId);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getQueueStatus($serviceId) {
        $query = 'SELECT * FROM senhas WHERE id_local = (SELECT id_local FROM servicos WHERE id_servico = :serviceId) AND status = "em_espera" ORDER BY data_hora_criacao ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':serviceId', $serviceId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function processNextInQueue($serviceId) {
        try {
            $this->conn->beginTransaction();

            $query = 'SELECT * FROM senhas WHERE id_local = (SELECT id_local FROM servicos WHERE id_servico = :serviceId) AND status = "em_espera" ORDER BY data_hora_criacao ASC LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':serviceId', $serviceId);
            $stmt->execute();

            $nextInQueue = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($nextInQueue) {
                $updateQuery = 'UPDATE senhas SET status = "em_atendimento", data_hora_atendimento = NOW() WHERE id_senha = :senhaId';
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':senhaId', $nextInQueue['id_senha']);
                $updateStmt->execute();

                $this->conn->commit();
                return $nextInQueue;
            }

            $this->conn->rollBack();
            return null;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getServiceHistory($serviceId) {
        $query = 'SELECT * FROM senhas WHERE id_local = (SELECT id_local FROM servicos WHERE id_servico = :serviceId)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':serviceId', $serviceId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>