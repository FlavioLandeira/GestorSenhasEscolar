<?php
require_once "../../config/database.php";

class Senha {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function retirarSenha($idUsuario, $idServico, $idLocal) {
        $query = "INSERT INTO senhas (id_utilizador, id_servico, id_local, status, data_hora_criacao)
                  VALUES (:id_usuario, :id_servico, :id_local, 'em_espera', NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':id_servico', $idServico);
        $stmt->bindParam(':id_local', $idLocal);
        return $stmt->execute();
    }

    public function listarSenhasPorLocal($idLocal) {
        $query = "SELECT s.id_senha, u.nome AS cliente, se.nome_servico, s.status, s.data_hora_criacao
                  FROM senhas s
                  INNER JOIN utilizadores u ON s.id_utilizador = u.id_utilizador
                  INNER JOIN servicos se ON s.id_servico = se.id_servico
                  WHERE s.id_local = :id_local
                  ORDER BY s.data_hora_criacao ASC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function chamarProximaSenha($idLocal) {
        // Atualiza a próxima senha em espera para "em_atendimento"
        $this->pdo->beginTransaction();
        $query = "SELECT id_senha FROM senhas
                  WHERE id_local = :id_local AND status = 'em_espera'
                  ORDER BY data_hora_criacao ASC LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        $stmt->execute();
        $senha = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($senha) {
            $updateQuery = "UPDATE senhas SET status = 'em_atendimento', data_hora_atendimento = NOW()
                            WHERE id_senha = :id_senha";
            $updateStmt = $this->pdo->prepare($updateQuery);
            $updateStmt->bindParam(':id_senha', $senha['id_senha']);
            $updateStmt->execute();
            $this->pdo->commit();
            return $senha['id_senha'];
        }

        $this->pdo->rollBack();
        return null;
    }

    public function concluirAtendimento($idSenha) {
        $query = "UPDATE senhas SET status = 'concluido' WHERE id_senha = :id_senha";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_senha', $idSenha);
        return $stmt->execute();
    }

    public function listarSenhasCliente($idUsuario) {
        $query = "SELECT * FROM senhas WHERE id_utilizador = :id_utilizador";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_utilizador', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
