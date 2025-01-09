<?php
// Dentro do controller ou model que você está utilizando
require_once "../../models/Senha.php";
require_once "../../models/User.php"; // Supondo que você tenha uma classe para isso
require_once "../../models/Local.php"; // E para locais
require_once "../../models/Service.php"; // E para serviços

session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$senhaModel = new Senha();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $senhaModel->adicionarSenha($_POST['id_utilizador'], $_POST['id_local'], $_POST['id_servico'], $_POST['status']);
    } elseif (isset($_POST['remover'])) {
        $senhaModel->removerSenha($_POST['id_senha']);
    } elseif (isset($_POST['atualizar'])) {
        $senhaModel->atualizarSenha($_POST['id_senha'], $_POST['status'], $_POST['data_hora_atendimento'] ?? null);
    }

    // Redirecionar após o POST para evitar o problema do refresh
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit;
}

$senhas = $senhaModel->listarSenhas();

// Obter utilizadores, locais e serviços usando os modelos ou controllers
$utilizadorModel = new User();
$locaisModel = new Local();
$servicosModel = new Service();

$utilizadores = $utilizadorModel->listarUtilizadores();
$locais = $locaisModel->listarLocais();
$servicos = $servicosModel->listarServicos();

// Para carregar os dados de uma senha selecionada para editar
$senhaParaAtualizar = null;
if (isset($_GET['id_senha'])) {
    $senhaParaAtualizar = $senhaModel->obterSenhaPorId($_GET['id_senha']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Senhas</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Gerenciar Senhas</h1>
    
    <h2>Lista de Senhas</h2>
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Utilizador</th>
            <th>Local</th>
            <th>Serviço</th>
            <th>Status</th>
            <th>Criado em</th>
            <th>Atendimento</th>
            <th>Ações</th> <!-- Nova coluna para as ações -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($senhas as $senha): ?>
            <tr>
                <td><?= $senha['id_senha']; ?></td>
                <td><?= $senha['nome_utilizador']; ?></td>
                <td><?= $senha['nome_local']; ?></td>
                <td><?= $senha['nome_servico']; ?></td>
                <td><?= $senha['status']; ?></td>
                <td><?= $senha['data_hora_criacao']; ?></td>
                <td><?= $senha['data_hora_atendimento'] ?? 'N/A'; ?></td>
                <td>
                    <!-- Formulário de remoção -->
                    <form method="POST">
                        <input type="hidden" name="id_senha" value="<?= $senha['id_senha']; ?>">
                        <button type="submit" name="remover" onclick="return confirm('Tem certeza que deseja remover esta senha?');">Remover</button>
                    </form>
                    <a href="?id_senha=<?= $senha['id_senha']; ?>">Editar</a> <!-- Link para editar -->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>

    <h2>Adicionar Senha</h2>
    <form method="POST">
        <!-- ID Utilizador - Exibe os nomes dos utilizadores -->
        Utilizador: 
        <select name="id_utilizador" required>
            <?php foreach ($utilizadores as $utilizador): ?>
                <option value="<?= $utilizador['id_utilizador']; ?>"><?= $utilizador['nome']; ?></option>
            <?php endforeach; ?>
        </select>

        <!-- ID Local - Exibe os nomes dos locais -->
        Local: 
        <select name="id_local" id="local-select" required>
            <option value="">Selecione um local</option>
            <?php foreach ($locais as $local): ?>
                <option value="<?= $local['id_local']; ?>"><?= $local['nome_local']; ?></option>
            <?php endforeach; ?>
        </select>

        <!-- ID Serviço - Exibe os nomes dos serviços -->
        Serviço: 
        <select name="id_servico" id="servico-select" required>
            <option value="">Selecione um serviço</option>
            <?php foreach ($servicos as $servico): ?>
                <option class="servico servico-<?= $servico['id_local']; ?>" value="<?= $servico['id_servico']; ?>" data-local="<?= $servico['id_local']; ?>"><?= $servico['nome_servico']; ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Status -->
        Status: 
        <select name="status" required>
            <option value="em_espera">Em Espera</option>
            <option value="em_atendimento">Em Atendimento</option>
            <option value="concluido">Concluído</option>
        </select>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <script>
        // Script para filtrar serviços com base no local selecionado
        document.getElementById('local-select').addEventListener('change', function() {
            var localId = this.value; // ID do local selecionado
            var servicos = document.querySelectorAll('#servico-select .servico');

            // Habilitar todos os serviços
            servicos.forEach(function(servico) {
                servico.style.display = 'none';  // Inicialmente esconde todos os serviços
                servico.disabled = true;         // Desabilita todos os serviços
            });

            if (localId) {
                // Exibir e habilitar serviços relacionados ao local selecionado
                var servicosVisiveis = document.querySelectorAll('.servico-' + localId);
                servicosVisiveis.forEach(function(servico) {
                    servico.style.display = 'block';  // Exibe os serviços relacionados
                    servico.disabled = false;         // Habilita os serviços relacionados
                });
            }
        });
    </script>

    <h2>Atualizar Senha</h2>
    <?php if ($senhaParaAtualizar): ?>
        <form method="POST">
            <input type="hidden" name="id_senha" value="<?= $senhaParaAtualizar['id_senha']; ?>">
            Status: 
            <select name="status" required>
                <option value="em_espera" <?= $senhaParaAtualizar['status'] === 'em_espera' ? 'selected' : ''; ?>>Em Espera</option>
                <option value="em_atendimento" <?= $senhaParaAtualizar['status'] === 'em_atendimento' ? 'selected' : ''; ?>>Em Atendimento</option>
                <option value="concluido" <?= $senhaParaAtualizar['status'] === 'concluido' ? 'selected' : ''; ?>>Concluído</option>
            </select>
            Data/Hora Atendimento: <input type="datetime-local" name="data_hora_atendimento" value="<?= $senhaParaAtualizar['data_hora_atendimento']; ?>">
            <button type="submit" name="atualizar">Atualizar</button>
        </form>
    <?php else: ?>
        <p>Selecione uma senha para editar.</p>
    <?php endif; ?>

    <a href="dashboard.php">Voltar</a>
</body>
</html>
