<?php
require_once __DIR__ . '/../config/database.php';
class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();  // Usa o método público getConnection
    }

    // Registrar usuário
    public function register($nome, $email, $senha) {
        $hashedPassword = password_hash($senha, PASSWORD_BCRYPT);
        $query = "INSERT INTO utilizadores (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($query);  // Troquei $this->pdo por $this->conn

        try {
            $this->conn->beginTransaction();  // Troquei $this->pdo por $this->conn
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $hashedPassword
            ]);
            $this->conn->commit();  // Troquei $this->pdo por $this->conn
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();  // Troquei $this->pdo por $this->conn
            return false;
        }
    }

    // Login de usuário
    public function login($email, $senha) {
        $query = "SELECT * FROM utilizadores WHERE email = :email";
        $stmt = $this->conn->prepare($query);  // Troquei $this->pdo por $this->conn
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha']) || $senha) {
            return $user;
        }
        return false;
    }

    public function listarUtilizadores() {
        $stmt = $this->conn->prepare("SELECT * FROM sistema_senhas.utilizadores");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarUtilizadores($nome, $email, $senha, $tipoUtilizador, $idLocal = null) {
        $stmt = $this->conn->prepare("
            INSERT INTO sistema_senhas.utilizadores (nome, email, senha, tipo_utilizador, id_local) 
            VALUES (:nome, :email, :senha, :tipo_utilizador, :id_local)
        ");
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => password_hash($senha, PASSWORD_BCRYPT),
            ':tipo_utilizador' => $tipoUtilizador,
            ':id_local' => $idLocal !== '' ? $idLocal : null // Aceita nulo caso esteja vazio
        ]);
    }    

    public function removerUtilizadores($idUtilizador) {
        $stmt = $this->conn->prepare("DELETE FROM sistema_senhas.utilizadores WHERE id_utilizador = :id");
        return $stmt->execute([':id' => $idUtilizador]);
    }

    public function atualizarUtilizadores($idUtilizador, $nome, $email, $senha, $tipoUtilizador, $idLocal = null) {
        $query = "
            UPDATE sistema_senhas.utilizadores 
            SET nome = :nome, email = :email, senha = :senha, tipo_utilizador = :tipo_utilizador, id_local = :id_local 
            WHERE id_utilizador = :id_utilizador
        ";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_utilizador' => $idUtilizador,
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => password_hash($senha, PASSWORD_DEFAULT), // Hashing for security
            ':tipo_utilizador' => $tipoUtilizador,
            ':id_local' => $idLocal
        ]);
    }
    public function obterLocais() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM locais");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter locais: " . $e->getMessage());
            return [];
        }
    }

    // Obter serviços de um local
    public function obterServicosPorLocal($idLocal) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM servicos WHERE id_local = :id_local");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter serviços: " . $e->getMessage());
            return [];
        }
    }

    // Criar senha para o cliente
    public function criarSenha($idUtilizador, $idLocal, $idServico) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO senhas (id_utilizador, id_local, id_servico, status, data_hora_criacao) 
                VALUES (:id_utilizador, :id_local, :id_servico, 'em_espera', NOW())
            ");
            $stmt->bindParam(':id_utilizador', $idUtilizador, PDO::PARAM_INT);
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->bindParam(':id_servico', $idServico, PDO::PARAM_INT);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar senha: " . $e->getMessage());
            return false;
        }
    }

    // Verificar posição na fila
    public function obterPosicaoNaFila($idSenha, $idLocal) {
        try {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) AS posicao
                FROM senhas
                WHERE id_local = :id_local AND status = 'em_espera' AND data_hora_criacao < (
                    SELECT data_hora_criacao FROM senhas WHERE id_senha = :id_senha
                )
            ");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->bindParam(':id_senha', $idSenha, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['posicao'] + 1; // Posição real na fila
        } catch (PDOException $e) {
            error_log("Erro ao obter posição na fila: " . $e->getMessage());
            return null;
        }
    }

    // Obter tempo estimado de espera
    public function obterTempoEstimado($idLocal) {
        try {
            $stmt = $this->conn->prepare("
                SELECT AVG(TIMESTAMPDIFF(MINUTE, data_hora_criacao, data_hora_atendimento)) AS tempo_medio
                FROM senhas
                WHERE id_local = :id_local AND status = 'concluido'
            ");
            $stmt->bindParam(':id_local', $idLocal, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['tempo_medio'] ?? 5; // Tempo médio em minutos, padrão 5 minutos
        } catch (PDOException $e) {
            error_log("Erro ao obter tempo estimado: " . $e->getMessage());
            return null;
        }
    }
}
?>
