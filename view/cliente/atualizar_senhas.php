<?php
require_once "../../models/Senha.php";
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'cliente') {
    exit;
}

$senhaModel = new Senha();
$senhas = $senhaModel->listarSenhasCliente($_SESSION['user']['id_utilizador']);

if (empty($senhas)) {
    echo "<p>Você não possui senhas no momento.</p>";
} else {
    foreach ($senhas as $senha) {
        echo "<p>Senha ID: {$senha['id_senha']} | Status: {$senha['status']} | Criada em: {$senha['data_hora_criacao']}</p>";
    }
}
?>
