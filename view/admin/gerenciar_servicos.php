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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Serviços</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Gerenciar Serviços</h1>
    
    <h2>Lista de Serviços</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td><?= $servico['id_servico']; ?></td>
                    <td><?= $servico['nome_servico']; ?></td>
                    <td><?= $servico['preco']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
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
