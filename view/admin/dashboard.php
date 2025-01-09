<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Painel de Administração</h1>
    <h2>Bem-vindo, <?php echo $_SESSION['user']['nome']; ?></h2>
    
    <ul>
        <li><a href="gerenciar_servicos.php">Gerenciar Serviços</a></li>
        <li><a href="gerenciar_local.php">Gerenciar Local</a></li>
        <li><a href="gerenciar_utilizadores.php">Gerenciar Utilizadores</a></li>
        <li><a href="gerenciar_senhas.php">Gerenciar Senhas</a></li>
        <li><a href="relatorios.php">Visualizar Relatórios</a></li>
    </ul>

    <a href="../../view/logout.php">Sair</a>
</body>
</html>
