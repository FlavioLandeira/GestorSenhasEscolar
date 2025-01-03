<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}

require_once "../../controllers/FuncionarioController.php";

$controller = new FuncionarioController();
$historico = $controller->visualizarHistorico();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Atendimentos</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Histórico de Atendimentos</h1>
    </header>

    <main>
        <h2>Registros de Atendimentos</h2>
        <table class="historico-table">
            <thead>
                <tr>
                    <th>Senha</th>
                    <th>Cliente</th>
                    <th>Serviço</th>
                    <th>Data/Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $registro): ?>
                    <tr>
                        <td><?= $registro['id_senha']; ?></td>
                        <td><?= $registro['cliente']; ?></td>
                        <td><?= $registro['servico']; ?></td>
                        <td><?= $registro['data_hora']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <a href="dashboard.php">Voltar ao Painel</a>
    </footer>
</body>
</html>
