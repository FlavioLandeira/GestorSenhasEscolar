<?php
require_once __DIR__ . '/../config/database.php';

class Funcionario {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obter a fila de espera para um local específico
    public function obterFila($idLocal) {
        try {
            $stmt = $this->conn->prepare("
                SELECT s.id_senha, u.nome AS cliente, s.data_hora_criacao
                FROM senhas s
                JOIN utilizadores u ON s.id_utilizador = u.id_utilizador
                WHERE s.id_local = :id_local AND s.status = 'em_espera'
                ORDER BY s.data_hora_criacao ASC
            ");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter fila: " . $e->getMessage());
            return [];
        }
    }
    

    // Chamar o próximo cliente da fila
    public function chamarProximo($idLocal) {
        try {
            $this->conn->beginTransaction();

            // Buscar o próximo cliente
            $query = "
                SELECT id_senha 
                FROM sistema_senhas.senhas
                WHERE id_local = :id_local AND status = 'em_espera'
                ORDER BY data_hora_criacao ASC LIMIT 1
            ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id_local' => $idLocal]);
            $proximaSenha = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($proximaSenha) {
                // Atualizar status para "em atendimento"
                $updateQuery = "
                    UPDATE sistema_senhas.senhas 
                    SET status = 'em_atendimento', data_hora_atendimento = NOW()
                    WHERE id_senha = :id_senha
                ";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->execute([':id_senha' => $proximaSenha['id_senha']]);

                $this->conn->commit();
                return $proximaSenha['id_senha'];
            }

            $this->conn->rollBack();
            return null;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Obter o histórico de atendimentos de um local
    public function obterHistorico($idLocal) {
        $query = "
            SELECT s.id_senha, u.nome AS cliente, s.status, s.data_hora_criacao, s.data_hora_atendimento
            FROM sistema_senhas.senhas s
            INNER JOIN sistema_senhas.utilizadores u ON s.id_utilizador = u.id_utilizador
            WHERE s.id_local = :id_local AND s.status = 'concluido'
            ORDER BY s.data_hora_atendimento DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id_local' => $idLocal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function concluirAtendimento($idSenha) {
        try {
            $this->conn->beginTransaction();
    
            // Concluir o atendimento atual
            $updateQuery = "UPDATE senhas SET status = 'concluido' WHERE id_senha = :id_senha";
            $stmt = $this->conn->prepare($updateQuery);
            $stmt->execute([':id_senha' => $idSenha]);
    
            // Buscar o próximo cliente na fila
            $proximoQuery = "
                SELECT id_senha 
                FROM senhas
                WHERE status = 'em_espera'
                ORDER BY data_hora_criacao ASC LIMIT 1
            ";
            $proximoStmt = $this->conn->prepare($proximoQuery);
            $proximoStmt->execute();
            $proximoCliente = $proximoStmt->fetch(PDO::FETCH_ASSOC);
    
            if ($proximoCliente) {
                // Atualizar o próximo cliente para "em atendimento"
                $updateProximoQuery = "
                    UPDATE senhas 
                    SET status = 'em_atendimento', data_hora_atendimento = NOW()
                    WHERE id_senha = :id_senha
                ";
                $updateProximoStmt = $this->conn->prepare($updateProximoQuery);
                $updateProximoStmt->execute([':id_senha' => $proximoCliente['id_senha']]);
            }
    
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Erro ao concluir atendimento: " . $e->getMessage());
            return false;
        }
    }
    
}
