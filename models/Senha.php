<?php
require_once __DIR__ . '/../config/database.php';

class Senha {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function retirarSenha($idUtilizador, $idServico) {
        $stmt = $this->db->prepare("INSERT INTO senhas (id_utilizador, id_local, status, data_hora_criacao) 
                                    VALUES (:id_utilizador, (SELECT id_local FROM servicos WHERE id_servico = :id_servico), 'em_espera', NOW())");
        return $stmt->execute([
            ':id_utilizador' => $idUtilizador,
            ':id_servico' => $idServico
        ]);
    }

    public function chamarProximaSenha($idLocal) {
        $stmt = $this->db->prepare("UPDATE senhas 
                                    SET status = 'em_atendimento', data_hora_atendimento = NOW()
                                    WHERE id_local = :id_local AND status = 'em_espera'
                                    ORDER BY data_hora_criacao ASC LIMIT 1");
        return $stmt->execute([':id_local' => $idLocal]);
    }

    public function listarFila($idLocal) {
        $stmt = $this->db->prepare("SELECT * FROM senhas WHERE id_local = :id_local AND status = 'em_espera'");
        $stmt->execute([':id_local' => $idLocal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarSenhasCliente($idUtilizador) {
        $stmt = $this->db->prepare("SELECT * FROM senhas WHERE id_utilizador = :id_utilizador ORDER BY data_hora_criacao DESC");
        $stmt->execute([':id_utilizador' => $idUtilizador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarFilaPorServico($idServico) {
        $stmt = $this->db->prepare("SELECT * FROM senhas 
                                    WHERE id_local = (SELECT id_local FROM servicos WHERE id_servico = :id_servico) 
                                    AND status = 'em_espera'");
        $stmt->execute([':id_servico' => $idServico]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarHistoricoSenhas($idUtilizador) {
        $stmt = $this->db->prepare("SELECT * FROM senhas 
                                    WHERE id_utilizador = :id_utilizador AND status = 'concluido'");
        $stmt->execute([':id_utilizador' => $idUtilizador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
}
