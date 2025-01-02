<?php
require_once "../models/Senha.php";

class SenhaController {
    private $senhaModel;

    public function __construct() {
        $this->senhaModel = new Senha();
    }

    public function retirarSenha($idUsuario, $idServico, $idLocal) {
        if ($this->senhaModel->retirarSenha($idUsuario, $idServico, $idLocal)) {
            echo "Senha retirada com sucesso!";
        } else {
            echo "Erro ao retirar a senha.";
        }
    }

    public function listarSenhas($idLocal) {
        return $this->senhaModel->listarSenhasPorLocal($idLocal);
    }

    public function chamarProximaSenha($idLocal) {
        if ($this->senhaModel->chamarProximaSenha($idLocal)) {
            echo "Próxima senha chamada com sucesso!";
        } else {
            echo "Erro ao chamar a próxima senha.";
        }
    }

    public function historicoSenhas($idUsuario) {
        return $this->senhaModel->historicoSenhas($idUsuario);
    }
}
?>