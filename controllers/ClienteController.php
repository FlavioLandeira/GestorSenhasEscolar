<?php
require_once "../models/Senha.php";
require_once "../models/Service.php";

class ClienteController {
    private $senhaModel;
    private $serviceModel;

    public function __construct() {
        $this->senhaModel = new Senha();
        $this->serviceModel = new Service();
    }

    public function retirarSenha() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idServico = $_POST['id_servico'];
            $idLocal = $_POST['id_local'];
            $idUsuario = $_SESSION['user']['id_utilizador'];

            $this->senhaModel->retirarSenha($idUsuario, $idServico, $idLocal);
            header("Location: acompanhar_senhas.php");
            exit;
        }
    }

    public function acompanharSenhas() {
        $senhas = $this->senhaModel->listarSenhasCliente($_SESSION['user']['id_utilizador']);
        require_once "../views/cliente/acompanhar_senhas.php";
    }
}
