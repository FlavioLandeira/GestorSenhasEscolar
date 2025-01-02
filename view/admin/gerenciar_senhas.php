<?php
require_once "../../models/Senha.php";
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$senhaModel = new Senha();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $senhaModel->adicionarSenha($_POST['id_utilizador'], $_POST['id_local'], $_POST['id_servico'], $_POST['status']);
    } elseif (isset($_POST['remover'])) {
        $senhaModel->removerSenha($_POST['id_senha']);
    } elseif (isset($_POST['atualizar'])) {
        $senhaModel->atualizarSenha($_POST['id_senha'], $_POST['status'], $_POST['data_hora_atendimento'] ?? null);
    }
}

$senhas = $senhaModel->listarSenhas();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Senhas</title>
</head>
<body>
    <h1>Gerenciar Senhas</h1>
    
    <h2>Adicionar Senha</h2>
    <form method="POST">
        ID Utilizador: <input type="text" name="id_utilizador" required>
        ID Local: <input type="text" name="id_local" required>
        ID Serviço: <input type="text" name="id_servico" required>
        Status: 
        <select name="status" required>
            <option value="em_espera">Em Espera</option>
            <option value="em_atendimento">Em Atendimento</option>
            <option value="concluido">Concluído</option>
        </select>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <h2>Remover Senha</h2>
    <form method="POST">
        ID Senha: <input type="text" name="id_senha" required>
        <button type="submit" name="remover">Remover</button>
    </form>

    <h2>Atualizar Senha</h2>
    <form method="POST">
        ID Senha: <input type="text" name="id_senha" required>
        Status: 
        <select name="status" required>
            <option value="em_espera">Em Espera</option>
            <option value="em_atendimento">Em Atendimento</option>
            <option value="concluido">Concluído</option>
        </select>
        Data/Hora Atendimento: <input type="datetime-local" name="data_hora_atendimento">
        <button type="submit" name="atualizar">Atualizar</button>
    </form>

    <h2>Lista de Senhas</h2>
    <ul>
        <?php foreach ($senhas as $senha): ?>
            <li>
                ID: <?= $senha['id_senha']; ?> | 
                Utilizador: <?= $senha['nome_utilizador']; ?> | 
                Local: <?= $senha['nome_local']; ?> | 
                Serviço: <?= $senha['nome_servico']; ?> | 
                Status: <?= $senha['status']; ?> | 
                Criado em: <?= $senha['data_hora_criacao']; ?> | 
                Atendimento: <?= $senha['data_hora_atendimento'] ?? 'N/A'; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
