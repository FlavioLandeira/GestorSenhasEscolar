<?php
require_once "../models/Senha.php";

session_start();

class SenhaController {
    private $senhaModel;

    public function __construct() {
        $this->senhaModel = new Senha();
    }

    public function retirarSenha() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idServico = $_POST['servico'];
            $idUtilizador = $_SESSION['user']['id_utilizador'];

            $this->senhaModel->retirarSenha($idUtilizador, $idServico);
            header("Location: ../view/cliente/dashboard.php");
            exit;
        }
    }
}

$senhaController = new SenhaController();
if (isset($_POST['action']) && $_POST['action'] === 'retirar_senha') {
    $senhaController->retirarSenha();
}
