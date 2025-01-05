<?php
// FuncionarioController.php

require_once __DIR__ . '/../models/Senha.php';
require_once __DIR__ . '/../models/Funcionario.php';

class FuncionarioController {
    private $senhaModel;
    private $funcionarioModel;

    public function __construct() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
            header("Location: ../view/login.php");
            exit;
        }

        $this->senhaModel = new Senha();
        $this->funcionarioModel = new Funcionario();
    }
    // Atender cliente
    public function atenderCliente($idSenha) {
        $resultado = $this->funcionarioModel->concluirAtendimento($idSenha);

        if ($resultado) {
            $_SESSION['mensagem'] = "Cliente atendido com sucesso.";
        } else {
            $_SESSION['mensagem'] = "Erro ao atender cliente.";
        }

        header("Location: ../../view/funcionario/gestao_fila.php");
    }

    public function gerenciarFila() {
        $funcionarioModel = new Funcionario();
        $id_local = $_SESSION['user']['id_local']; // Pegue o ID do local do funcionário logado
        return $funcionarioModel->listarFila($id_local);
    }
    

    // Chamar o próximo cliente
    public function chamarProximoCliente()
    {
        $id_local = $_SESSION['user']['id_local'];

        if ($this->senhaModel->chamarProximoCliente($id_local)) {
            $_SESSION['mensagem'] = "Próximo cliente chamado com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao chamar o próximo cliente.";
        }
    }

    // Concluir atendimento
    public function concluirAtendimento($idSenha) {
        $funcionarioModel = new Funcionario();
    
        // Atualizar o status para "concluído"
        if ($funcionarioModel->concluirAtendimento($idSenha)) {
            $_SESSION['mensagem'] = "Atendimento da senha $idSenha concluído com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao concluir o atendimento da senha $idSenha.";
        }
    
        // Redirecionar para evitar reenvio do formulário
        header("Location: gestao_fila.php");
        exit;
    }
    
}

