<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'cliente') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente - Página Inicial</title>
</head>
<body>
    <h1>Bem-vindo(a), <?= $_SESSION['user']['nome'] ?></h1>
    <nav>
        <ul>
            <li><a href="retirar_senha.php">Retirar Senha</a></li>
            <li><a href="acompanhar_senhas.php">Acompanhar Senhas</a></li>
            <li><a href="historico.php">Historico</a></li>
            <li><a href="../../logout.php">Sair</a></li>
        </ul>
    </nav>
</body>
</html>
