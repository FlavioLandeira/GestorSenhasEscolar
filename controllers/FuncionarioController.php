<?php
require_once "../models/Senha.php";

class FuncionarioController {
    private $senhaModel;

    public function __construct() {
        $this->senhaModel = new Senha();
    }

    public function gerenciarSenhas() {
        $idLocal = $_SESSION['user']['id_local'];
        $senhas = $this->senhaModel->listarSenhasPorLocal($idLocal);
        require_once "../views/funcionario/dashboard.php";
    }

    public function chamarProximaSenha() {
        $idLocal = $_SESSION['user']['id_local'];
        $proximaSenha = $this->senhaModel->chamarProximaSenha($idLocal);

        if ($proximaSenha) {
            echo "Senha chamada: $proximaSenha";
        } else {
            echo "Nenhuma senha em espera.";
        }
    }

    public function concluirAtendimento() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idSenha = $_POST['id_senha'];
            $this->senhaModel->concluirAtendimento($idSenha);
            header("Location: dashboard.php");
            exit;
        }
    }
}
