<!-- acompanhar_senhas.php -->
<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../models/Senha.php";

$senhaModel = new Senha();
$senhas = $senhaModel->historicoSenhas($_SESSION['user']['id_utilizador']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acompanhar Senhas</title>
</head>
<body>
    <h1>Acompanhar Senhas</h1>

    <?php if (empty($senhas)): ?>
        <p>Você ainda não retirou nenhuma senha.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th>Data de Criação</th>
                    <th>Data de Atendimento</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($senhas as $senha): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($senha['id_senha']); ?></td>
                        <td><?php echo htmlspecialchars($senha['nome_servico']); ?></td>
                        <td><?php echo htmlspecialchars($senha['status']); ?></td>
                        <td><?php echo htmlspecialchars($senha['data_hora_criacao']); ?></td>
                        <td><?php echo htmlspecialchars($senha['data_hora_atendimento'] ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="retirar_senha.php">Retirar outra Senha</a>
</body>
</html>
