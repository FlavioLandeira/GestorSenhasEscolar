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
    <link rel="stylesheet" href="style.css">
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
                                <td><?php echo htmlspecialchars($senha['id_senha']); ?></td>
                                <td><?php echo isset($senha['id_utilizador']) ? htmlspecialchars($senha['id_utilizador']) : 'N/A'; ?></td>
                                <td><?php echo htmlspecialchars($senha['data_hora_criacao']); ?></td>
                                <td>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="id_senha" value="<?php echo $senha['id_senha']; ?>">
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

        <!-- Botão para Chamar o Próximo Cliente -->
        <section class="chamar-cliente">
            <form method="post">
                <button type="submit" name="chamar" class="btn-chamar">Chamar Próximo Cliente</button>
            </form>

            <!-- Mensagem de Feedback -->
            <?php if (isset($_SESSION['mensagem'])): ?>
                <p class="mensagem"><?php echo htmlspecialchars($_SESSION['mensagem']); unset($_SESSION['mensagem']); ?></p>
            <?php endif; ?>
        </section>
    </main>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        header {
            background: #007bff;
            color: #fff;
            padding: 1em 2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-voltar {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            background: #0056b3;
            padding: 0.5em 1em;
            border-radius: 5px;
        }
        h1, h2 {
            margin: 0;
        }
        main {
            padding: 2em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2em;
        }
        table th, table td {
            padding: 0.5em;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background: #007bff;
            color: #fff;
        }
        .no-data {
            color: #555;
            font-style: italic;
        }
        .btn-chamar {
            display: inline-block;
            padding: 0.5em 1.5em;
            font-size: 1em;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
        }
        .btn-chamar:hover {
            background: #0056b3;
        }
        .mensagem {
            margin-top: 1em;
            color: green;
            font-weight: bold;
        }
    </style>
</body>
</html>
