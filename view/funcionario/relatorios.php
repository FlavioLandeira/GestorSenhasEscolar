<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}

$relatorio = isset($relatorio) ? $relatorio : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Relatórios de Atendimentos</h1>
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
            <?php foreach ($relatorio as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['id_senha']); ?></td>
                    <td><?php echo htmlspecialchars($item['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($item['status']); ?></td>
                    <td><?php echo htmlspecialchars($item['data_hora_criacao']); ?></td>
                    <td><?php echo htmlspecialchars($item['data_hora_atendimento']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav>
        <a href="dashboard.php">Voltar ao Painel</a>
    </nav>
</body>
</html>
