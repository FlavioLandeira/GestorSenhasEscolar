<?php
require_once "../../models/Senha.php";
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}

$senhaModel = new Senha();
$idLocal = $_SESSION['user']['id_local'];
$fila = $senhaModel->listarFila($idLocal);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Atendimentos</title>
</head>
<body>
    <h1>Histórico de Atendimentos</h1>
    <ul>
        <?php foreach ($fila as $senha): ?>
            <li>ID Senha: <?= $senha['id_senha']; ?> | Status: <?= $senha['status']; ?> | Criada em: <?= $senha['data_hora_criacao']; ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="dashboard.php">Voltar</a>
</body>
</html>
