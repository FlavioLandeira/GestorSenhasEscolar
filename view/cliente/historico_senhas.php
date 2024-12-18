<?php
require_once "../../models/Senha.php";
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'cliente') {
    header("Location: ../login.php");
    exit;
}

$senhaModel = new Senha();
$senhas = $senhaModel->listarHistoricoSenhas($_SESSION['user']['id_utilizador']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Senhas</title>
</head>
<body>
    <h1>Histórico de Senhas</h1>
    <ul>
        <?php foreach ($senhas as $senha): ?>
            <li>ID: <?= $senha['id_senha']; ?> | Status: <?= $senha['status']; ?> | Atendido em: <?= $senha['data_hora_atendimento']; ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="dashboard.php">Voltar</a>
</body>
</html>
