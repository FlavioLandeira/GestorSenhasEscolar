<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit
}

require_once "../../models/Local.php";
require_once "../../models/Service.php";

$localModel = new Local();
$servicoModel = new Service();

$idLocal = $_SESSION['user']['id_local'];
$nomeLocal = $localModel->buscarNomeLocal($idLocal);
$servicos = $localModel->listarServicosPorLocal($idLocal);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Painel do Funcionário</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($nomeLocal); ?></h1>
        <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?></h2>
    </header>

    <main>
        <ul>
            <li><a href="chamar_proximo.php">Chamar Próximo Cliente</a></li>
            <li><a href="historico_atendimentos.php">Histórico de Atendimentos</a></li>
            <li><a href="gerenciar_servicos.php">Gerenciar Serviços</a></li>
        </ul>
        
        <section>
            <h3>Fila de Espera</h3>
            <div id="fila">
                <!-- A fila será carregada via AJAX -->
            </div>
        </section>

        <section>
            <h3>Serviços Disponíveis no Local</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Serviço</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos as $servico): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($servico['nome_servico']); ?></td>
                            <td><?php echo htmlspecialchars($servico['preco']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <a href="../../controllers/logout.php">Sair</a>
    </footer>

    <script>
        // Atualização da fila de espera via AJAX
        setInterval(function() {
            fetch('atualizar_fila.php?id_local=<?= $idLocal; ?>')
            .then(response => response.text())
            .then(data => {
                document.getElementById('fila').innerHTML = data;
            });
        }, 3000);
    </script>
</body>
</html>
