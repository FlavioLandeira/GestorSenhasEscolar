<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}

require_once "../../controllers/FuncionarioController.php";

$controller = new FuncionarioController();
$relatorios = $controller->gerarRelatoriosFunc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Relatórios</h1>
    </header>

    <main>
        <h2>Relatórios Gerados</h2>
        <table class="relatorios-table">
            <thead>
                <tr>
                    <th>ID Relatório</th>
                    <th>Descrição</th>
                    <th>Data</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($relatorios as $relatorio): ?>
                    <tr>
                        <td><?= $relatorio['id_relatorio']; ?></td>
                        <td><?= $relatorio['descricao']; ?></td>
                        <td><?= $relatorio['data_geracao']; ?></td>
                        <td><a href="download.php?id=<?= $relatorio['id_relatorio']; ?>">Baixar</a></td>
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
