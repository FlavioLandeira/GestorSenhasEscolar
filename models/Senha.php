<?php
require_once __DIR__ . "../../config/database.php";

class Senha {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

     // Método para retirar senha com validação de id_servico
     public function retirarSenha($idUsuario, $idServico, $idLocal) {
        $query = "INSERT INTO senhas (id_utilizador, id_servico, id_local, status, data_hora_criacao)
                  VALUES (:id_usuario, :id_servico, :id_local, 'em_espera', NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':id_servico', $idServico);
        $stmt->bindParam(':id_local', $idLocal);
        return $stmt->execute();
    }

    // Listar senhas por local
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

    //Gerenciamento de Senhas

    //Gerenciamento - Listar todas as Senhas
    public function listarSenhas() {
        $stmt = $this->conn->prepare("
            SELECT s.*, u.nome AS nome_utilizador, l.nome_local AS nome_local, se.nome_servico AS nome_servico
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.utilizadores u ON s.id_utilizador = u.id_utilizador
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Gerenciamento - Adicionar Senhas
    public function adicionarSenha($idUtilizador, $idLocal, $idServico, $status) {
        $query = "
            INSERT INTO sistema_senhas.senhas (id_utilizador, id_local, id_servico, status, data_hora_criacao)
            VALUES (:id_utilizador, :id_local, :id_servico, :status, NOW())
        ";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_utilizador' => $idUtilizador,
            ':id_local' => $idLocal,
            ':id_servico' => $idServico,
            ':status' => $status
        ]);
    }

    //Gerenciamnento - Remover Senhas
    public function removerSenha($idSenha) {
        $query = "DELETE FROM sistema_senhas.senhas WHERE id_senha = :id_senha";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id_senha' => $idSenha]);
    }

    //Gerenciamento - Atualizar Senhas
    public function atualizarSenha($idSenha, $status, $dataHoraAtendimento = null) {
        $query = "
            UPDATE sistema_senhas.senhas 
            SET status = :status, data_hora_atendimento = :data_hora_atendimento 
            WHERE id_senha = :id_senha
        ";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_senha' => $idSenha,
            ':status' => $status,
            ':data_hora_atendimento' => $dataHoraAtendimento
        ]);
    }
    public function gerarRelatorios() {
        $relatorios = [];
    
        // Número total de senhas retiradas por local e serviço
        $query1 = "
            SELECT l.nome_local, se.nome_servico, COUNT(s.id_senha) AS total_senhas
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
            GROUP BY l.nome_local, se.nome_servico
        ";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();
        $relatorios['total_senhas'] = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    
        // Tempo médio de espera (em minutos) por local e serviço
        $query2 = "
            SELECT l.nome_local, se.nome_servico, 
                   AVG(TIMESTAMPDIFF(MINUTE, s.data_hora_criacao, s.data_hora_atendimento)) AS tempo_medio_espera
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
            WHERE s.data_hora_atendimento IS NOT NULL
            GROUP BY l.nome_local, se.nome_servico
        ";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->execute();
        $relatorios['tempo_medio_espera'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
        // Tempo médio de atendimento (em minutos) por local e serviço
        $query3 = "
            SELECT l.nome_local, se.nome_servico, 
                   AVG(TIMESTAMPDIFF(MINUTE, s.data_hora_atendimento, NOW())) AS tempo_medio_atendimento
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
            WHERE s.status = 'concluido'
            GROUP BY l.nome_local, se.nome_servico
        ";
        $stmt3 = $this->conn->prepare($query3);
        $stmt3->execute();
        $relatorios['tempo_medio_atendimento'] = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
        // Status atual das filas
        $query4 = "
            SELECT l.nome_local, se.nome_servico,
                   SUM(CASE WHEN s.status = 'em_espera' THEN 1 ELSE 0 END) AS em_espera,
                   SUM(CASE WHEN s.status = 'em_atendimento' THEN 1 ELSE 0 END) AS em_atendimento,
                   SUM(CASE WHEN s.status = 'concluido' THEN 1 ELSE 0 END) AS concluido
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
            GROUP BY l.nome_local, se.nome_servico
        ";
        $stmt4 = $this->conn->prepare($query4);
        $stmt4->execute();
        $relatorios['status_filas'] = $stmt4->fetchAll(PDO::FETCH_ASSOC);
    
        return $relatorios;
    }
    public function gerarRelatoriosPorLocal($id_local) {
        $relatorios = [];
    
        // Número total de senhas retiradas por local e serviço
        $query1 = "
            SELECT l.nome_local, se.nome_servico, COUNT(s.id_senha) AS total_senhas
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
            WHERE s.id_local = :id_local
            GROUP BY l.nome_local, se.nome_servico
        ";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam(':id_local', $id_local, PDO::PARAM_INT);
        $stmt1->execute();
        $relatorios['total_senhas'] = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    
        // Tempo médio de espera (em minutos) por local e serviço
        $query2 = "
            SELECT l.nome_local, se.nome_servico, 
                   AVG(TIMESTAMPDIFF(MINUTE, s.data_hora_criacao, s.data_hora_atendimento)) AS tempo_medio_espera
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
            WHERE s.id_local = :id_local
            AND s.data_hora_atendimento IS NOT NULL
            GROUP BY l.nome_local, se.nome_servico
        ";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(':id_local', $id_local, PDO::PARAM_INT);
        $stmt2->execute();
        $relatorios['tempo_medio_espera'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
        // Tempo médio de atendimento (em minutos) por local e serviço
        $query3 = "
            SELECT l.nome_local, se.nome_servico, 
                   AVG(TIMESTAMPDIFF(MINUTE, s.data_hora_atendimento, NOW())) AS tempo_medio_atendimento
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
            WHERE s.id_local = :id_local
            AND s.status = 'concluido'
            GROUP BY l.nome_local, se.nome_servico
        ";
        $stmt3 = $this->conn->prepare($query3);
        $stmt3->bindParam(':id_local', $id_local, PDO::PARAM_INT);
        $stmt3->execute();
        $relatorios['tempo_medio_atendimento'] = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
        // Status atual das filas
        $query4 = "
            SELECT l.nome_local, se.nome_servico,
                   SUM(CASE WHEN s.status = 'em_espera' THEN 1 ELSE 0 END) AS em_espera,
                   SUM(CASE WHEN s.status = 'em_atendimento' THEN 1 ELSE 0 END) AS em_atendimento,
                   SUM(CASE WHEN s.status = 'concluido' THEN 1 ELSE 0 END) AS concluido
            FROM sistema_senhas.senhas s
            JOIN sistema_senhas.locais l ON s.id_local = l.id_local
            JOIN sistema_senhas.servicos se ON s.id_servico = se.id_servico
            WHERE s.id_local = :id_local
            GROUP BY l.nome_local, se.nome_servico
        ";
        $stmt4 = $this->conn->prepare($query4);
        $stmt4->bindParam(':id_local', $id_local, PDO::PARAM_INT);
        $stmt4->execute();
        $relatorios['status_filas'] = $stmt4->fetchAll(PDO::FETCH_ASSOC);
    
        return $relatorios;
    }
    

    //Funcionários functions

    // Listar senhas por serviço
    public function listarSenhasPorServicoFunc($idServico) {
        $query = "SELECT * FROM senhas WHERE id_servico = :id_servico AND estado = 'pendente'";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_servico', $idServico, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar histórico de atendimentos por serviço
    public function listarHistoricoPorServicoFunc($idServico) {
        $query = "SELECT * FROM historico WHERE id_servico = :id_servico ORDER BY data_hora DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_servico', $idServico, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Listar senhas do cliente
    public function listarSenhasCliente($idUsuario) {
        $query = "SELECT s.id_senha, se.nome_servico, s.status, s.data_hora_criacao
                  FROM senhas s
                  INNER JOIN servicos se ON s.id_servico = se.id_servico
                  WHERE s.id_utilizador = :id_usuario
                  ORDER BY s.data_hora_criacao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Função para verificar se há senhas pendentes antes de chamar a próxima
    public function listarSenhasEmEsperaUsuario($idUsuario) {
        $query = "
            SELECT s.id_senha, serv.nome_servico, s.status, s.data_hora_criacao
            FROM senhas AS s
            INNER JOIN servicos AS serv ON s.id_servico = serv.id_servico
            WHERE s.id_utilizador = :id_utilizador AND s.status = 'em_espera'
            ORDER BY s.data_hora_criacao ASC
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_utilizador', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function contarPessoasNaFrente($idUsuario) {
        $query = "
            SELECT COUNT(*) AS pessoas_na_frente
            FROM senhas AS s
            WHERE s.id_local = (
                SELECT id_local 
                FROM senhas
                WHERE id_utilizador = :id_utilizador AND status = 'em_espera'
                LIMIT 1
            )
            AND s.status = 'em_espera'
            AND s.data_hora_criacao < (
                SELECT data_hora_criacao 
                FROM senhas 
                WHERE id_utilizador = :id_utilizador AND status = 'em_espera'
                LIMIT 1
            )
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_utilizador', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC)['pessoas_na_frente'];
    }
    
}
?>
