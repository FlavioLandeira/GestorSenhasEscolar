<?php
// ClienteController.php

require_once __DIR__ . '/../models/Senha.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Local.php';
require_once __DIR__ . '/../models/Service.php';

class ClienteController {
    private $senhaModel;
    private $userModel;
    private $localModel;
    private $servicoModel;

    public function __construct() {
        session_start(); // Certifica-se de que a sessão está ativa

        if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'cliente') {
            header("Location: ../view/login.php");
            exit;
        }

        $this->senhaModel = new Senha();
        $this->userModel = new User();
        $this->localModel = new Local();
        $this->servicoModel = new Servico();
    }

    // Exibe a página de retirada de senha
    public function exibirPaginaRetiradaSenha() {
        $locais = $this->localModel->obterTodosLocais();
        require_once "../../view/cliente/retirada_senha.php";
    }

    // Retirar senha
    public function retirarSenha() {
        $idUtilizador = $_SESSION['user']['id_utilizador'];
        $idLocal = $_POST['id_local'];
        $idServico = $_POST['id_servico'];

        $idSenha = $this->senhaModel->criarSenha($idUtilizador, $idLocal, $idServico);

        if ($idSenha) {
            header("Location: ../../view/cliente/acompanhar_fila.php?id_senha=$idSenha");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro ao retirar senha. Tente novamente.";
            header("Location: ../../view/cliente/retirada_senha.php");
            exit();
        }
    }

    // Acompanhar fila
    public function acompanharFila() {
        $idSenha = $_GET['id_senha'];
        $dadosFila = $this->senhaModel->obterDetalhesFila($idSenha);
        require_once "../../view/cliente/acompanhar_fila.php";
    }

    // Obter serviços por local
    public function obterServicosPorLocal() {
        if (isset($_GET['id_local'])) {
            $idLocal = $_GET['id_local'];
            $servicos = $this->servicoModel->obterServicosPorLocal($idLocal);

            // Retorna os serviços como JSON
            header('Content-Type: application/json');
            echo json_encode($servicos);
        } else {
            // Caso não seja passado o ID do local, retorna erro
            http_response_code(400);
            echo json_encode(['erro' => 'ID do local não fornecido.']);
        }
        exit();
    }
}
