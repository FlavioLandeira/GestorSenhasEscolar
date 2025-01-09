<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../models/Senha.php";

$senhaModel = new Senha();

// Obtém apenas as senhas com status "em_espera" para o usuário logado
$senhasEmEspera = $senhaModel->listarSenhasEmEsperaUsuario($_SESSION['user']['id_utilizador']); 

// Conta o número de pessoas à frente na fila
$pessoasNaFrente = $senhaModel->contarPessoasNaFrente($_SESSION['user']['id_utilizador']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acompanhar Senhas</title>
</head>
<body>
    <h1>Senhas em Espera</h1>

    <?php if (empty($senhasEmEspera)): ?>
        <p>Você não possui senhas em espera no momento.</p>
    <?php else: ?>
        <p>Pessoas à frente na fila: <strong><?php echo htmlspecialchars($pessoasNaFrente); ?></strong></p>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($senhasEmEspera as $senha): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($senha['id_senha']); ?></td>
                        <td><?php echo htmlspecialchars($senha['nome_servico']); ?></td>
                        <td><?php echo htmlspecialchars($senha['status']); ?></td>
                        <td><?php echo htmlspecialchars($senha['data_hora_criacao']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="retirar_senha.php">Retirar outra Senha</a>
</body>
</html>
