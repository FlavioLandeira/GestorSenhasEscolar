<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}
$nomeLocal = $_SESSION['user']['id_local']; // Buscar o nome do local com base no ID.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Painel do Funcionário</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h1><?php echo "Local: " . $nomeLocal; ?></h1>
    <h2>Bem-vindo, <?php echo $_SESSION['user']['nome']; ?></h2>

    <ul>
        <li><a href="chamar_proximo.php">Chamar Próximo Cliente</a></li>
        <li><a href="historico_atendimentos.php">Histórico de Atendimentos</a></li>
    </ul>

    <a href="../../controllers/logout.php">Sair</a>
</body>
</html>
