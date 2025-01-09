<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}

require_once "../../controllers/FuncionarioController.php";
$controller = new FuncionarioController();

// Verificar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['chamar'])) {
        $controller->chamarProximoCliente();
    } elseif (isset($_POST['concluir']) && isset($_POST['id_senha'])) {
        $controller->concluirAtendimento($_POST['id_senha']);
    }
}

// Obter fila
$fila = $controller->gerenciarFila();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Fila</title>
    <link rel="stylesheet" href="func.css">
</head>
<body>
    <header>
        <h1>Gestão de Fila</h1>
        <a href="dashboard.php" class="btn-voltar">Voltar ao Painel</a>
    </header>

    <main>
        <!-- Exibição da Fila -->
        <section>
            <h2>Senhas em Espera</h2>
            <?php if (!empty($fila)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Data/Hora da Solicitação</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fila as $senha): ?>
                            <tr>
                                <td><?= htmlspecialchars($senha['id_senha']); ?></td>
                                <td><?= htmlspecialchars($senha['nome_cliente']); ?></td>
                                <td><?= htmlspecialchars($senha['data_hora_criacao']); ?></td>
                                <td>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="id_senha" value="<?= htmlspecialchars($senha['id_senha']); ?>">
                                        <button type="submit" name="concluir" class="btn-concluir">Concluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else: ?>
                <p>Não há clientes na fila no momento.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
