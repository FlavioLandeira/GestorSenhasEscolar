<?php
require_once "../../models/Local.php";
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$localModel = new Local();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $localModel->adicionarLocal($_POST['nome_local'], $_POST['descricao']);
    } elseif (isset($_POST['remover'])) {
        $localModel->removerLocal($_POST['id_local']);
    } elseif (isset($_POST['atualizar'])) {
        $localModel->atualizarLocal($_POST['id_local'],$_POST['nome_local'],$_POST['descricao']);
    }
}

$locais = $localModel->listarLocais();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Locais</title>
</head>
<body>
    <h1>Gerenciar Locais</h1>
    
    <h2>Adicionar Local</h2>
    <form method="POST">
        Nome do Local: <input type="text" name="nome_local" required>
        Descrição: <textarea name="descricao" required></textarea>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <h2>Remover Local</h2>
    <form method="POST">
        ID do Local: <input type="text" name="id_local" required>
        <button type="submit" name="remover">Remover</button>
    </form>

    <h2>Lista de Locais</h2>
    <ul>
        <?php foreach ($locais as $local): ?>
            <li>
                ID: <?= $local['id_local']; ?> | 
                Nome: <?= $local['nome_local']; ?> | 
                Descrição: <?= $local['descricao']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <h2>Atualizar Local</h2>
    <form method="POST">
        ID do Local: <input type="text" name="id_local" required>
        Nome do Local: <input type="text" name="nome_local" required>
        Descrição: <textarea name="descricao" required></textarea>
        <button type="submit" name="atualizar">Atualizar</button>
    </form>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
