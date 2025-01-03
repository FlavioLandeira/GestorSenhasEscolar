<?php
// retirar_senha.php
session_start(); // Inicializa a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php"); // Redireciona para o login se não estiver autenticado
    exit();
}

require_once "../../models/Senha.php";
require_once "../../models/Service.php";
require_once "../../models/Local.php";

$senhaModel = new Senha();
$serviceModel = new Service();
$localModel = new Local();

// Obtém os serviços e locais disponíveis para exibição no formulário
$servicos = $serviceModel->listarServicos();
$locais = $localModel->listarLocais();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idServico = $_POST['id_servico'];
    $idLocal = $_POST['id_local'];
    $idUsuario = $_SESSION['user']['id_utilizador'];

    $senhaModel->retirarSenha($idUsuario, $idServico, $idLocal);
    header("Location: acompanhar_senhas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retirar Senha</title>
</head>
<body>
    <h1>Retirar Senha</h1>
    <form method="POST" action="">
        <label for="id_servico">Escolha o Serviço:</label>
        <select name="id_servico" id="id_servico" required>
            <option value="">Selecione um serviço</option>
            <?php foreach ($servicos as $servico): ?>
                <option value="<?php echo $servico['id_servico']; ?>">
                    <?php echo htmlspecialchars($servico['nome_servico']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="id_local">Escolha o Local:</label>
        <select name="id_local" id="id_local" required>
            <option value="">Selecione um local</option>
            <?php foreach ($locais as $local): ?>
                <option value="<?php echo $local['id_local']; ?>">
                    <?php echo htmlspecialchars($local['nome_local']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Retirar Senha</button>
    </form>
    <a href="acompanhar_senhas.php">Acompanhar Senhas</a>
</body>
</html>