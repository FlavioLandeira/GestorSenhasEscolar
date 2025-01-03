<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Funcionário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Painel do Funcionário</h1>
        <h2>Bem-vindo, <?php echo $_SESSION['user']['nome']; ?></h2>
    </header>

    <nav>
        <ul class="dashboard-menu">
            <li><a href="gestao_fila.php">Gestão de Fila</a></li>
            <li><a href="historico.php">Histórico de Atendimentos</a></li>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="../../view/logout.php">Sair</a></li>
        </ul>
    </nav>

</body>
</html>
