<?php
require_once "../../config/database.php";

class Senha {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para retirar senha com o novo campo id_servico
    public function retirarSenha($idUsuario, $idServico, $idLocal) {
        $query = "INSERT INTO senhas (id_utilizador, id_servico, id_local, status, data_hora_criacao)
                  VALUES (:id_usuario, :id_servico, :id_local, 'em_espera', NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':id_servico', $idServico);
        $stmt->bindParam(':id_local', $idLocal);
        return $stmt->execute();
    }

    // Listar senhas por local, incluindo o id_servico como chave de ligação
    public function listarSenhasPorLocal($idLocal) {
        $query = "SELECT s.id_senha, u.nome AS cliente, se.nome_servico, s.status, s.data_hora_criacao
                  FROM senhas s
                  INNER JOIN utilizadores u ON s.id_utilizador = u.id_utilizador
                  INNER JOIN servicos se ON s.id_servico = se.id_servico
                  WHERE s.id_local = :id_local
                  ORDER BY s.data_hora_criacao ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para chamar a próxima senha em um local específico
    public function chamarProximaSenha($idLocal) {
        $query = "UPDATE senhas SET status = 'em_atendimento', data_hora_atendimento = NOW()
                  WHERE id_local = :id_local AND status = 'em_espera' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        return $stmt->execute();
    }

    // Histórico de senhas de um usuário, incluindo os serviços relacionados
    public function historicoSenhas($idUsuario) {
        $query = "SELECT s.id_senha, se.nome_servico, s.status, s.data_hora_criacao, s.data_hora_atendimento
                  FROM senhas s
                  INNER JOIN servicos se ON s.id_servico = se.id_servico
                  WHERE s.id_utilizador = :id_usuario
                  ORDER BY s.data_hora_criacao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
