<?php
require_once "../../config/database.php";

class Service {
    public $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function listarServicos() {
        $stmt = $this->conn->prepare("SELECT * FROM servicos"); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarServico($nomeServico, $preco, $idLocal) {
        $stmt = $this->conn->prepare("INSERT INTO servicos (nome_servico, preco, id_local) VALUES (:nome, :preco, :id_local)"); 
        return $stmt->execute([
            ':nome' => $nomeServico,
            ':preco' => $preco,
            ':id_local' => $idLocal
        ]);
    }

    public function removerServico($idServico) {
        $stmt = $this->conn->prepare("DELETE FROM servicos WHERE id_servico = :id"); 
        return $stmt->execute([':id' => $idServico]);
    }

    public function listarServicosPorLocal($idLocal) {
        $query = "SELECT * FROM servicos WHERE id_local = :id_local";
        $stmt = $this->conn->prepare($query); 
        $stmt->bindParam(':id_local', $idLocal);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function atualizarServico($idServico, $nomeServico, $preco, $idLocal) {
        $query = "
            UPDATE sistema_senhas.servicos 
            SET nome_servico = :nome_servico, preco = :preco, id_local = :id_local 
            WHERE id_servico = :id_servico
        ";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_servico' => $idServico,
            ':nome_servico' => $nomeServico,
            ':preco' => $preco,
            ':id_local' => $idLocal
        ]);
    }
    public function listarServicosPorUsuario($idUsuario) {
        $query = "SELECT se.id_servico, se.nome_servico, se.preco
                  FROM servicos se
                  INNER JOIN utilizadores u ON se.id_local = u.id_local
                  WHERE u.id_utilizador = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
