<?php
require_once __DIR__ . "../../config/database.php";

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
        try {
            $stmt = $this->conn->prepare("SELECT * FROM servicos WHERE id_local = :id_local");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar serviços por local: " . $e->getMessage());
            return [];
        }
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
    public function obterServicosPorLocal($idLocal) {
        try {
            $stmt = $this->conn->prepare("SELECT id_servico, nome_servico FROM servicos WHERE id_local = :id_local");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter serviços por local: " . $e->getMessage());
            return [];
        }
    }

}
?>
