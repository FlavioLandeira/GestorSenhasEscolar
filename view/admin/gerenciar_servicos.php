<?php
require_once "../../models/Service.php";
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$serviceModel = new Service();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $serviceModel->adicionarServico($_POST['nome'], $_POST['preco'], $_POST['id_local']);
    } elseif (isset($_POST['remover'])) {
        $serviceModel->removerServico($_POST['id_servico']);
    } elseif (isset($_POST['atualizar'])) {
        $serviceModel->atualizarServico($_POST['id_servico'],$_POST['nome'],$_POST['preco'],$_POST['id_local']);
    }
}

$servicos = $serviceModel->listarServicos();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Serviços</title>
</head>
<body>
    <h1>Gerenciar Serviços</h1>
    
    <h2>Adicionar Serviço</h2>
    <form method="POST">
        Nome: <input type="text" name="nome" required>
        Preço: <input type="text" name="preco" required>
        ID Local: <input type="text" name="id_local" required>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <h2>Remover Serviço</h2>
    <form method="POST">
        ID Serviço: <input type="text" name="id_servico" required>
        <button type="submit" name="remover">Remover</button>
    </form>

    <h2>Lista de Serviços</h2>
    <ul>
        <?php foreach ($servicos as $servico): ?>
            <li>ID: <?= $servico['id_servico']; ?> | Nome: <?= $servico['nome_servico']; ?> | Preço: <?= $servico['preco']; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Atualizar Serviço</h2>
        <form method="POST">
            ID do Serviço: <input type="text" name="id_servico" required>
            Nome do Serviço: <input type="text" name="nome" required>
            Preço: <input type="text" name="preco" required>
            ID Local: <input type="text" name="id_local" required>
            <button type="submit" name="atualizar">Atualizar</button>
        </form>
    <a href="dashboard.php">Voltar</a>
</body>
</html>
