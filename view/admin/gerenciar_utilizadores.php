<?php
require_once "../../models/User.php";
require_once "../../models/Local.php"; // Para carregar os locais
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$userModel = new User();
$localModel = new Local(); // Modelo para carregar locais

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $userModel->adicionarUtilizadores($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['tipo_utilizador'], $_POST['id_local']);
    } elseif (isset($_POST['remover'])) {
        // Remover o utilizador usando o ID passado
        $userModel->removerUtilizadores($_POST['id_utilizador']);
    } elseif (isset($_POST['atualizar'])) {
        // Atualizar utilizador pelo ID, passando os novos valores
        $userModel->atualizarUtilizadores($_POST['id_utilizador'], $_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['tipo_utilizador'], $_POST['id_local']);
    }

    // Redirecionar após a ação para evitar o problema do refresh
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit;
}

$utilizadores = $userModel->listarUtilizadores();
$locais = $localModel->listarLocais(); // Obter os locais existentes

// Para carregar os dados de um utilizador selecionado para atualizar
$utilizadorParaAtualizar = null;
if (isset($_GET['id_utilizador'])) {
    $utilizadorParaAtualizar = $userModel->obterUtilizadorPorId($_GET['id_utilizador']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Utilizadores</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Gerenciar Utilizadores</h1>
    
    <h2>Lista de Utilizadores</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Local</th>
                <th>Ações</th> <!-- Nova coluna para os botões de ação -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilizadores as $utilizador): ?>
                <tr>
                    <td><?= $utilizador['id_utilizador']; ?></td>
                    <td><?= $utilizador['nome']; ?></td>
                    <td><?= $utilizador['email']; ?></td>
                    <td><?= $utilizador['tipo_utilizador']; ?></td>
                    <td><?= $utilizador['id_local']; ?></td>
                    <td>
                        <!-- Botões de ação na tabela -->
                        <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover este utilizador?');">
                            <input type="hidden" name="id_utilizador" value="<?= $utilizador['id_utilizador']; ?>">
                            <button type="submit" name="remover">Remover</button>
                        </form>
                        <a href="?id_utilizador=<?= $utilizador['id_utilizador']; ?>">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Adicionar Utilizador</h2>
    <form method="POST">
        Nome: <input type="text" name="nome" required>
        Email: <input type="email" name="email" required>
        Senha: <input type="password" name="senha" required>
        Tipo de Utilizador: 
        <select name="tipo_utilizador" required>
            <option value="cliente">Cliente</option>
            <option value="funcionario">Funcionário</option>
            <option value="administrador">Administrador</option>
        </select>
        ID Local: 
        <select name="id_local">
            <option value="">Sem Local (Null)</option> <!-- Opção null adicionada -->
            <?php foreach ($locais as $local): ?>
                <option value="<?= $local['id_local']; ?>"><?= $local['nome_local']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>


    <h2>Atualizar Utilizador</h2>
    <?php if ($utilizadorParaAtualizar): ?>
        <form method="POST">
            <input type="hidden" name="id_utilizador" value="<?= $utilizadorParaAtualizar['id_utilizador']; ?>">
            Nome: <input type="text" name="nome" value="<?= $utilizadorParaAtualizar['nome']; ?>" required>
            Email: <input type="email" name="email" value="<?= $utilizadorParaAtualizar['email']; ?>" required>
            Senha: <input type="password" name="senha" required>
            Tipo de Utilizador: 
            <select name="tipo_utilizador" required>
                <option value="cliente" <?= $utilizadorParaAtualizar['tipo_utilizador'] == 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                <option value="funcionario" <?= $utilizadorParaAtualizar['tipo_utilizador'] == 'funcionario' ? 'selected' : ''; ?>>Funcionário</option>
                <option value="administrador" <?= $utilizadorParaAtualizar['tipo_utilizador'] == 'administrador' ? 'selected' : ''; ?>>Administrador</option>
            </select>
            ID Local: 
            <select name="id_local">
            <option value="" <?= is_null($utilizadorParaAtualizar['id_local']) ? 'selected' : ''; ?>>Sem Local (Null)</option>
            <?php foreach ($locais as $local): ?>
                <option value="<?= $local['id_local']; ?>" <?= $utilizadorParaAtualizar['id_local'] == $local['id_local'] ? 'selected' : ''; ?>>
                    <?= $local['nome_local']; ?>
                </option>
            <?php endforeach; ?>
        </select>
            <button type="submit" name="atualizar">Atualizar</button>
        </form>
    <?php else: ?>
        <p>Selecione um utilizador para editar.</p>
    <?php endif; ?>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
