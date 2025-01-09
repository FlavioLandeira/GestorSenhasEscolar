<?php
// retirar_senha.php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../models/Senha.php";
require_once "../../models/Service.php";
require_once "../../models/Local.php";

$senhaModel = new Senha();
$serviceModel = new Service();
$localModel = new Local();

// Caso seja uma solicitação AJAX para buscar serviços
if (isset($_GET['id_local'])) {
    $idLocal = $_GET['id_local'];
    $servicos = $serviceModel->listarServicosPorLocal($idLocal); // Crie esse método no modelo
    echo json_encode($servicos);
    exit();
}

// Obtém os locais disponíveis para exibição no formulário
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
    <link rel="stylesheet" href="cliente.css">
    <script>
        // Função para buscar serviços com base no local selecionado
        function atualizarServicos() {
            const idLocal = document.getElementById('id_local').value;
            const servicoSelect = document.getElementById('id_servico');

            // Limpa as opções atuais
            servicoSelect.innerHTML = '<option value="">Selecione um serviço</option>';

            if (idLocal) {
                fetch(`retirar_senha.php?id_local=${idLocal}`)
                    .then(response => response.json())
                    .then(servicos => {
                        servicos.forEach(servico => {
                            const option = document.createElement('option');
                            option.value = servico.id_servico;
                            option.textContent = servico.nome_servico;
                            servicoSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro ao buscar serviços:', error));
            }
        }
    </script>
</head>
<body>
    <h1>Retirar Senha</h1>
    <form method="POST" action="">
        <label for="id_local">Escolha o Local:</label>
        <select name="id_local" id="id_local" onchange="atualizarServicos()" required>
            <option value="">Selecione um local</option>
            <?php foreach ($locais as $local): ?>
                <option value="<?php echo $local['id_local']; ?>">
                    <?php echo htmlspecialchars($local['nome_local']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="id_servico">Escolha o Serviço:</label>
        <select name="id_servico" id="id_servico" required>
            <option value="">Selecione um serviço</option>
        </select>

        <button type="submit">Retirar Senha</button>
    </form>
    <a href="acompanhar_senhas.php">Acompanhar Senhas</a>
</body>
</html>
