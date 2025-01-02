<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'cliente') {
    header("Location: ../login.php");
    exit;
}

require_once "../../models/Senha.php";

$senhaModel = new Senha();
$historico = $senhaModel->historicoSenhas($_SESSION['user']['id_utilizador']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Senhas</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h1>Histórico de Senhas</h1>
    <table>
        <tr>
            <th>ID Senha</th>
            <th>Serviço</th>
            <th>Status</th>
            <th>Data de Criação</th>
            <th>Data de Atendimento</th>
        </tr>
        <?php foreach ($historico as $senha): ?>
        <tr>
            <td><?= htmlspecialchars($senha['id_senha']); ?></td>
            <td><?= htmlspecialchars($senha['nome_servico']); ?></td>
            <td><?= htmlspecialchars($senha['status']); ?></td>
            <td><?= htmlspecialchars($senha['data_hora_criacao']); ?></td>
            <td><?= htmlspecialchars($senha['data_hora_atendimento'] ?? 'N/A'); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">Voltar ao Painel</a>
</body>
</html>