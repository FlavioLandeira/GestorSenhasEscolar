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
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h1>Painel de Administração</h1>
    <h2>Bem-vindo, <?php echo $_SESSION['user']['nome']; ?></h2>
    
    <ul>
        <li><a href="gerenciar_servicos.php">Gerenciar Serviços</a></li>
        <li><a href="gerenciar_funcionarios.php">Gerenciar Funcionários</a></li>
        <li><a href="relatorios.php">Visualizar Relatórios</a></li>
    </ul>

    <a href="../../view/logout.php">Sair</a>
</body>
</html>
