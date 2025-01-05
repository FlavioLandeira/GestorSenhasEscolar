<?php
// Modelo: Funcionario.php

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
            $stmt = $this->conn->prepare("SELECT * FROM senhas WHERE id_local = :id_local AND status = 'em_espera' ORDER BY data_hora_criacao ASC");
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

            $stmt = $this->conn->prepare("SELECT id_senha FROM senhas WHERE id_local = :id_local AND status = 'em_espera' ORDER BY data_hora_criacao ASC LIMIT 1");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();

            $proximaSenha = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($proximaSenha) {
                $updateStmt = $this->conn->prepare("UPDATE senhas SET status = 'em_atendimento', data_hora_atendimento = NOW() WHERE id_senha = :id_senha");
                $updateStmt->bindParam(':id_senha', $proximaSenha['id_senha'], PDO::PARAM_INT);
                $updateStmt->execute();

                $this->conn->commit();
                return $proximaSenha['id_senha'];
            }

            $this->conn->rollBack();
            return null;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Erro ao chamar próximo cliente: " . $e->getMessage());
            return null;
        }
    }

    // Concluir atendimento (mantendo apenas uma implementação)
    public function concluirAtendimento($idSenha) {
        try {
            $stmt = $this->conn->prepare("UPDATE senhas SET status = 'concluido', data_hora_atendimento = NOW() WHERE id_senha = :id_senha");
            $stmt->bindParam(':id_senha', $idSenha, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao concluir atendimento: " . $e->getMessage());
            return false;
        }
    }    

    // Obter o histórico de atendimentos de um local
    public function obterHistorico($idLocal) {
        try {
            $stmt = $this->conn->prepare("SELECT s.id_senha, u.nome AS cliente, s.status, s.data_hora_criacao, s.data_hora_atendimento FROM senhas s JOIN utilizadores u ON s.id_utilizador = u.id_utilizador WHERE s.id_local = :id_local AND s.status = 'concluido' ORDER BY s.data_hora_atendimento DESC");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter histórico: " . $e->getMessage());
            return [];
        }
    }

    // Gerar relatórios para o funcionário
    public function gerarRelatorios($idLocal) {
        try {
            $stmt = $this->conn->prepare("SELECT id_relatorio, descricao, data_geracao FROM relatorios WHERE id_local = :id_local ORDER BY data_geracao DESC");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao gerar relatórios: " . $e->getMessage());
            return [];
        }
    }

    // Gerar relatório detalhado de atendimentos
    public function gerarRelatorioAtendimentos($idLocal) {
        try {
            $query = "
                SELECT s.id_senha, u.nome AS cliente, s.status, s.data_hora_criacao, s.data_hora_atendimento
                FROM senhas s
                INNER JOIN utilizadores u ON s.id_utilizador = u.id_utilizador
                WHERE s.id_local = :id_local
                ORDER BY s.data_hora_atendimento DESC
            ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao gerar relatório: " . $e->getMessage());
            return [];
        }
    }
    public function listarFila($id_local) {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    s.id_senha, 
                    u.nome AS nome_cliente, 
                    s.data_hora_criacao 
                FROM senhas s
                JOIN utilizadores u ON s.id_utilizador = u.id_utilizador
                WHERE s.id_local = :id_local AND s.status = 'em_espera'
                ORDER BY s.data_hora_criacao ASC
            ");
            $stmt->bindParam(':id_local', $id_local, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar fila: " . $e->getMessage());
            return [];
        }
    }
    
}
