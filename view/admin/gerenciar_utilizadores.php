<?php
require_once "../../models/User.php";

session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$userModel = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $userModel->adicionarUsuario($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['tipo_utilizador'], $_POST['id_local']);
    } elseif (isset($_POST['remover'])) {
        $userModel->removerUsuario($_POST['id_utilizador']);
    } elseif (isset($_POST['atualizar'])) {
        $userModel->atualizarUsuario($_POST['id_utilizador'],$_POST['nome'],$_POST['email'], $_POST['senha'],$_POST['tipo_utilizador'],$_POST['id_local']);
    }
}

$utilizadores = $userModel->listarUsuarios();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
</head>
<body>
    <h1>Gerenciar Usuários</h1>
    
    <h2>Adicionar Usuário</h2>
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
        ID Local: <input type="text" name="id_local" placeholder="Opcional">
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <h2>Remover Usuário</h2>
    <form method="POST">
        ID Utilizador: <input type="text" name="id_utilizador" required>
        <button type="submit" name="remover">Remover</button>
    </form>

    <h2>Lista de Usuários</h2>
    <ul>
        <?php foreach ($utilizadores as $utilizador): ?>
            <li>
                ID: <?= $utilizador['id_utilizador']; ?> | 
                Nome: <?= $utilizador['nome']; ?> | 
                Email: <?= $utilizador['email']; ?> | 
                Tipo: <?= $utilizador['tipo_utilizador']; ?> | 
                Local: <?= $utilizador['id_local']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <h2>Atualizar Usuário</h2>
    <form method="POST">
        ID Utilizador: <input type="text" name="id_utilizador" required>
        Nome: <input type="text" name="nome" required>
        Email: <input type="email" name="email" required>
        Senha: <input type="password" name="senha" required>
        Tipo de Utilizador: 
        <select name="tipo_utilizador" required>
            <option value="cliente">Cliente</option>
            <option value="funcionario">Funcionário</option>
            <option value="administrador">Administrador</option>
        </select>
        ID Local: <input type="text" name="id_local">
        <button type="submit" name="atualizar">Atualizar</button>
    </form>
    <a href="dashboard.php">Voltar</a>
</body>
</html>
