<?php
session_start();
require_once "../../models/Senha.php";

// Verificar se o usuário é um funcionário
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    echo "Acesso restrito!";
    exit;
}

// Inicializando o modelo de Senha
$senhaModel = new Senha();

// Verificar se o botão de chamar próximo cliente foi clicado
if (isset($_POST['chamar_cliente'])) {
    // Chamar o próximo cliente (muda o status da senha para 'em_atendimento')
    $senhaModel->chamarProximoCliente();
    echo "Cliente chamado com sucesso!";
    exit;
}

// Listar todas as senhas na fila (pendentes)
$senhas = $senhaModel->listarSenhasFila();

$html = "";
if (!empty($senhas)) {
    foreach ($senhas as $senha) {
        $html .= "<p>Senha: {$senha['id_senha']} - Serviço: {$senha['nome_servico']} - Status: {$senha['status']}</p>";
    }
} else {
    $html = "<p>Não há clientes na fila.</p>";
}

echo $html;
?>
