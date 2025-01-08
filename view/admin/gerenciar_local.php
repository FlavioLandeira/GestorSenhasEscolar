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
        // Remover o local usando o ID passado
        $localModel->removerLocal($_POST['id_local']);
    } elseif (isset($_POST['atualizar'])) {
        // Atualizar local pelo nome, buscando o ID e passando os novos valores.
        $localId = $localModel->obterIdPorNome($_POST['nome_local']);
        $localModel->atualizarLocal($localId, $_POST['novo_nome_local'], $_POST['descricao']);
    }

    // Redirecionar após a ação para evitar o problema do refresh
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit;
}

$locais = $localModel->listarLocais();

// Para carregar os dados de um local selecionado para atualizar
$localParaAtualizar = null;
if (isset($_GET['id_local'])) {
    $localParaAtualizar = $localModel->obterLocalPorId($_GET['id_local']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Locais</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Gerenciar Locais</h1>
    
    <h2>Lista de Locais</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th> <!-- Nova coluna para o botão de remoção -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locais as $local): ?>
                <tr>
                    <td><?= $local['id_local']; ?></td>
                    <td><?= $local['nome_local']; ?></td>
                    <td><?= $local['descricao']; ?></td>
                    <td>
                        <!-- Formulário de remoção na tabela -->
                        <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover este local?');">
                            <input type="hidden" name="id_local" value="<?= $local['id_local']; ?>">
                            <button type="submit" name="remover">Remover</button>
                            <a href="?id_local=<?= $local['id_local']; ?>">Editar</a>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Adicionar Local</h2>
    <form method="POST">
        Nome do Local: <input type="text" name="nome_local" required>
        Descrição: <textarea name="descricao" required></textarea>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <h2>Atualizar Local</h2>
    <?php if ($localParaAtualizar): ?>
        <form method="POST">
            <input type="hidden" name="nome_local" value="<?= $localParaAtualizar['nome_local']; ?>">
            Novo Nome: <input type="text" name="novo_nome_local" value="<?= $localParaAtualizar['nome_local']; ?>" required>
            Nova Descrição: <textarea name="descricao" required><?= $localParaAtualizar['descricao']; ?></textarea>
            <button type="submit" name="atualizar">Atualizar</button>
        </form>
    <?php else: ?>
        <p>Selecione um local para editar.</p>
    <?php endif; ?>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
