<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../models/Funcionario.php";
require_once "../../models/User.php";

$funcionarioModel = new Funcionario();
$utilizadorModel = new User();

// Obtém o id_utilizador da sessão
$idUtilizador = $_SESSION['user']['id_utilizador']; 

// Obtém o id_local com base no id_utilizador
$idLocal = $utilizadorModel->obterIdLocalPorUtilizador($idUtilizador);
if (!$idLocal) {
    echo "Erro: Local do funcionário não encontrado.";
    exit();
}

// Obtém o histórico de senhas para o local
$historicoSenhas = $funcionarioModel->obterSenhasAtendidasPorLocal($idLocal);
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Senhas</title>
    <script>
        // Redireciona para atualizar o histórico com base no local selecionado
        function atualizarHistorico() {
            const idLocal = document.getElementById('id_local').value;

            // Redireciona para a página com o parâmetro id_local
            if (idLocal) {
                window.location.href = `historico.php?id_local=${idLocal}`;
            }
        }
    </script>
</head>
<body>
    <h1>Histórico de Senhas</h1>

    <?php if (empty($historicoSenhas)): ?>
        <p>Você não possui senhas no histórico.</p>
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
                <?php foreach ($historicoSenhas as $senha): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($senha['id_senha']); ?></td>
                        <td><?php echo htmlspecialchars($senha['nome_servico']); ?></td>
                        <td><?php echo htmlspecialchars($senha['status']); ?></td>
                        <td><?php echo htmlspecialchars($senha['data_hora_criacao']); ?></td>
                        <td><?php echo htmlspecialchars($senha['data_hora_atendimento']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="dashboard.php">Voltar ao painel</a>
</body>
</html>
