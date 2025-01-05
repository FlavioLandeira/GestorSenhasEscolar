<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}

$historico = isset($historico) ? $historico : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Atendimentos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Histórico de Atendimentos</h1>
        <h2>Bem-vindo, <?php echo $_SESSION['user']['nome']; ?></h2>
    </header>

    <table>
        <thead>
            <tr>
                <th>Senha</th>
                <th>Cliente</th>
                <th>Status</th>
                <th>Data Criação</th>
                <th>Data Atendimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historico as $atendimento): ?>
                <tr>
                    <td><?php echo htmlspecialchars($atendimento['id_senha']); ?></td>
                    <td><?php echo htmlspecialchars($atendimento['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($atendimento['status']); ?></td>
                    <td><?php echo htmlspecialchars($atendimento['data_hora_criacao']); ?></td>
                    <td><?php echo htmlspecialchars($atendimento['data_hora_atendimento']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav>
        <a href="dashboard.php">Voltar ao Painel</a>
    </nav>
</body>
</html>
