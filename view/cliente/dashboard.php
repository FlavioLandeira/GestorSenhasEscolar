<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'cliente') {
    header("Location: ../login.php");
    exit;
}

require_once "../../models/Service.php";
require_once "../../models/Senha.php";

// Inicializar os modelos
$serviceModel = new Service();
$senhaModel = new Senha();

// Carregar os serviços disponíveis
$servicos = $serviceModel->listarServicosPorLocal($_SESSION['user']['id_local']);

// Lidar com o formulário de retirada de senha
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['retirar_senha'])) {
    $idServico = filter_input(INPUT_POST, 'id_servico', FILTER_VALIDATE_INT);
    $idLocal = filter_input(INPUT_POST, 'id_local', FILTER_VALIDATE_INT);

    if ($idServico && $idLocal) {
        if ($senhaModel->retirarSenha($_SESSION['user']['id_utilizador'], $idServico, $idLocal)) {
            $_SESSION['mensagem'] = "Senha retirada com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao retirar senha. Tente novamente.";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['mensagem'] = "Dados inválidos. Por favor, tente novamente.";
    }
}

// Exibir mensagem da sessão, se houver
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <header>
        <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['user']['nome']); ?>!</h1>
    </header>

    <main>
        <section>
            <h2>Serviços Disponíveis</h2>

            <?php if (isset($mensagem)) echo "<p style='color: green;'>$mensagem</p>"; ?>

            <?php foreach ($servicos as $servico): ?>
                <div class="servico">
                    <h3><?= htmlspecialchars($servico['nome_servico']); ?></h3>
                    <p>Preço: €<?= number_format($servico['preco'], 2); ?></p>
                    <form method="POST">
                        <input type="hidden" name="id_servico" value="<?= $servico['id_servico']; ?>">
                        <input type="hidden" name="id_local" value="<?= $_SESSION['user']['id_local']; ?>">
                        <button type="submit" name="retirar_senha">Retirar Senha</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </section>

        <section>
            <h2>Suas Senhas</h2>
            <div id="fila">
                <!-- Fila em tempo real -->
            </div>
        </section>
    </main>

    <footer>
        <a href="../../controllers/logout.php">Sair</a>
    </footer>

    <script>
        setInterval(function() {
    fetch('atualizar_senhas.php')
    .then(response => {
        if (!response.ok) throw new Error('Erro na resposta do servidor.');
        return response.text();
    })
    .then(data => {
        document.getElementById('fila').innerHTML = data;
        if (data.includes('em_atendimento')) {
            alert('Sua vez chegou! Dirija-se ao balcão.');
        }
    })
    .catch(error => console.error('Erro ao atualizar as senhas:', error));
}, 5000); // Intervalo de 5 segundos

    </script>
</body>
</html>
