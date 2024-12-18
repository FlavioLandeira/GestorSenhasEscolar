<?php
require_once __DIR__ . '/../config/database.php';

class Service {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect(); // Use the connect() method
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
}
?>
