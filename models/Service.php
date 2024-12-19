<?php
require_once "../../config/database.php";

class Service {
    public $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function listarServicos() {
        $stmt = $this->db->prepare("SELECT * FROM servicos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarServico($nomeServico, $preco, $idLocal) {
        $stmt = $this->db->prepare("INSERT INTO servicos (nome_servico, preco, id_local) VALUES (:nome, :preco, :id_local)");
        return $stmt->execute([
            ':nome' => $nomeServico,
            ':preco' => $preco,
            ':id_local' => $idLocal
        ]);
    }

    public function removerServico($idServico) {
        $stmt = $this->db->prepare("DELETE FROM servicos WHERE id_servico = :id");
        return $stmt->execute([':id' => $idServico]);
    }
    public function listarServicosPorLocal($idLocal) {
        $query = "SELECT * FROM servicos WHERE id_local = :id_local";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_local', $idLocal);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
