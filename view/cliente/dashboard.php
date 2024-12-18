<?php
require_once "../../models/Service.php";
require_once "../../models/Senha.php";
require_once "../../config/database.php";
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'cliente') {
    header("Location: ../login.php");
    exit;
}

$serviceModel = new Service();
$senhaModel = new Senha();
$servicos = $serviceModel->listarServicos();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['retirar_senha'])) {
    $idServico = $_POST['id_servico'];
    $senhaModel->retirarSenha($_SESSION['user']['id_utilizador'], $idServico);
    $mensagem = "Senha retirada com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }
        h1 { text-align: center; }
        .servico { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['user']['nome']); ?>!</h1>
    <h2>Serviços Disponíveis</h2>

    <?php if (isset($mensagem)) echo "<p style='color: green;'>$mensagem</p>"; ?>

    <?php foreach ($servicos as $servico): ?>
        <div class="servico">
            <h3><?= htmlspecialchars($servico['nome_servico']); ?></h3>
            <p>Preço: €<?= number_format($servico['preco'], 2); ?></p>
            <form method="POST">
                <input type="hidden" name="id_servico" value="<?= $servico['id_servico']; ?>">
                <button type="submit" name="retirar_senha">Retirar Senha</button>
            </form>
        </div>
    <?php endforeach; ?>

    <h2>Suas Senhas</h2>
    <div id="fila">
        <!-- Fila em tempo real -->
    </div>
</div>

<script>
setInterval(function() {
    fetch('atualizar_senhas.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('fila').innerHTML = data;
        if (data.includes('em_atendimento')) {
            alert('Sua vez chegou! Dirija-se ao balcão.');
        }
    });
}, 3000);
</script>
</body>
</html>
