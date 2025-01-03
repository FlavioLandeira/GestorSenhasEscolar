<?php
require_once __DIR__ . "/../models/Senha.php";
require_once __DIR__ . "/../models/Funcionario.php";

class FuncionarioController {
    private $senhaModel;
    private $funcionarioModel;
    private $db; // Adicionado para conexão com o banco de dados

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header("Location: ../view/login.php"); // Redireciona para login se a sessão não for válida
            exit;
        }

        // Inicializar os modelos
        $this->senhaModel = new Senha();
        $this->funcionarioModel = new Funcionario();

        // Inicializar a conexão com o banco de dados
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=gestor_senhas', 'root', '');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    // Gerenciar fila do local gerido
    public function gerenciarFila() {
        $idLocal = $_SESSION['user']['id_local'];
        return $this->funcionarioModel->obterFila($idLocal);
    }
    

    // Chamar próximo cliente da fila
    public function chamarProximoCliente() {
        $idLocal = $_SESSION['user']['id_local'];
        $proximoCliente = $this->senhaModel->chamarProximo($idLocal);

        if ($proximoCliente) {
            $_SESSION['mensagem'] = "Próximo cliente chamado: Senha " . $proximoCliente['id_senha'];
        } else {
            $_SESSION['mensagem'] = "Nenhum cliente na fila.";
        }
    }
    

    // Visualizar histórico de atendimentos
    public function visualizarHistorico() {
        $idLocal = $_SESSION['user']['id_local'];
        $historico = $this->funcionarioModel->obterHistorico($idLocal);
        require_once "../../view/funcionario/historico.php";
    }

    // Gerar relatórios para o funcionário
    public function gerarRelatoriosFunc() {
        try {
            $stmt = $this->db->prepare("SELECT id_relatorio, descricao, data_geracao FROM relatorios WHERE tipo_utilizador = :tipo ORDER BY data_geracao DESC");
            $stmt->execute(['tipo' => 'funcionario']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar relatórios: " . $e->getMessage());
            return [];
        }
    }

    // Buscar relatório por ID
    public function buscarRelatorioPorId($idRelatorio) {
        try {
            $stmt = $this->db->prepare("SELECT conteudo FROM relatorios WHERE id_relatorio = :id");
            $stmt->execute(['id' => $idRelatorio]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar relatório por ID: " . $e->getMessage());
            return null;
        }
    }
    public function concluirAtendimento($idSenha) {
        $resultado = $this->funcionarioModel->concluirAtendimento($idSenha);
    
        if ($resultado) {
            // Após concluir, chamar o próximo cliente
            $idLocal = $_SESSION['user']['id_local'];
            $proximoCliente = $this->senhaModel->chamarProximo($idLocal);
    
            if ($proximoCliente) {
                $_SESSION['mensagem'] = "Atendimento da senha $idSenha concluído com sucesso. Próximo cliente chamado: Senha $proximoCliente.";
            } else {
                $_SESSION['mensagem'] = "Atendimento da senha $idSenha concluído com sucesso. Não há mais clientes na fila.";
            }
        } else {
            $_SESSION['mensagem'] = "Erro ao concluir atendimento.";
        }
    }
}
