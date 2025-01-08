<?php
require_once __DIR__ . "../../config/database.php";

class Local {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Função para listar todos os locais
    public function listarLocais() {
        $query = "SELECT * FROM locais";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para adicionar um novo local
    public function adicionarLocal($nome, $descricao) {
        $query = "INSERT INTO locais (nome_local, descricao) VALUES (:nome_local, :descricao)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome_local', $nome);
        $stmt->bindParam(':descricao', $descricao);
        return $stmt->execute();
    }

    // Função para buscar informações de um local específico
    public function buscarLocal($idLocal) {
        $query = "SELECT * FROM locais WHERE id_local = :id_local";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para buscar apenas o nome do local
    public function buscarNomeLocal($idLocal) {
        $query = "SELECT nome_local FROM locais WHERE id_local = :id_local";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nome_local'] : null;
    }

    // Função para listar serviços de um local específico
    public function listarServicosPorLocal($idLocal) {
        $query = "SELECT * FROM servicos WHERE id_local = :id_local";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para atualizar informações de um local
    public function atualizarLocal($idLocal, $nomeLocal, $descricao) {
        $query = "
            UPDATE sistema_senhas.locais 
            SET nome_local = :nome_local, descricao = :descricao 
            WHERE id_local = :id_local
        ";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_local' => $idLocal,
            ':nome_local' => $nomeLocal,
            ':descricao' => $descricao
        ]);
    }
    
    // Função para remover um local
    public function removerLocal($idLocal) {
        $query = "DELETE FROM locais WHERE id_local = :id_local";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        return $stmt->execute();
    }

    public function getLocais() {
        try {
            $stmt = $this->conn->prepare("SELECT id_local, nome_local FROM locais"); // Consulta para pegar os locais
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os locais como um array associativo
        } catch (PDOException $e) {
            error_log("Erro ao obter locais: " . $e->getMessage());
            return [];
        }
    }
    public function obterIdPorNome($nomeLocal) {
        // Supondo que haja um método para buscar pelo nome
        $stmt = $this->conn->prepare("SELECT id_local FROM locais WHERE nome_local = ?");
        $stmt->execute([$nomeLocal]);
        $result = $stmt->fetch();
        return $result['id_local'] ?? null;
    }
    // No modelo Local.php

    public function obterLocalPorId($id_local) {
        // Prepara a consulta para obter o local pelo ID
        $stmt = $this->conn->prepare("SELECT * FROM locais WHERE id_local = ?");
        
        // Executa a consulta passando o ID do local
        $stmt->execute([$id_local]);
        
        // Retorna o resultado (um único local) ou false se não encontrado
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
}
