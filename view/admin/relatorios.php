<?php
require_once "../../models/Senha.php";
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$senhaModel = new Senha();

// Consultar relatórios
$dadosRelatorios = $senhaModel->gerarRelatorios();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios de Filas</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Relatórios Detalhados sobre Filas</h1>

    <h2>Número Total de Senhas Retiradas</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Local</th>
                <th>Serviço</th>
                <th>Total de Senhas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dadosRelatorios['total_senhas'] as $linha): ?>
                <tr>
                    <td><?= $linha['nome_local']; ?></td>
                    <td><?= $linha['nome_servico']; ?></td>
                    <td><?= $linha['total_senhas']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Tempo Médio de Espera (em minutos)</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Local</th>
                <th>Serviço</th>
                <th>Tempo Médio de Espera</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dadosRelatorios['tempo_medio_espera'] as $linha): ?>
                <tr>
                    <td><?= $linha['nome_local']; ?></td>
                    <td><?= $linha['nome_servico']; ?></td>
                    <td><?= $linha['tempo_medio_espera']; ?> minutos</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Tempo Médio de Atendimento (em minutos)</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Local</th>
                <th>Serviço</th>
                <th>Tempo Médio de Atendimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dadosRelatorios['tempo_medio_atendimento'] as $linha): ?>
                <tr>
                    <td><?= $linha['nome_local']; ?></td>
                    <td><?= $linha['nome_servico']; ?></td>
                    <td><?= $linha['tempo_medio_atendimento']; ?> minutos</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Status Atual das Filas</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Local</th>
                <th>Serviço</th>
                <th>Em Espera</th>
                <th>Em Atendimento</th>
                <th>Concluído</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dadosRelatorios['status_filas'] as $linha): ?>
                <tr>
                    <td><?= $linha['nome_local']; ?></td>
                    <td><?= $linha['nome_servico']; ?></td>
                    <td><?= $linha['em_espera']; ?></td>
                    <td><?= $linha['em_atendimento']; ?></td>
                    <td><?= $linha['concluido']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
