<?php
require_once "../../models/Service.php";
require_once "../../models/Local.php"; // Para obter os locais
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$serviceModel = new Service();
$localModel = new Local();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $serviceModel->adicionarServico($_POST['nome'], $_POST['preco'], $_POST['id_local']);
    } elseif (isset($_POST['remover'])) {
        $serviceModel->removerServico($_POST['id_servico']);
    } elseif (isset($_POST['atualizar'])) {
        $serviceModel->atualizarServico($_POST['id_servico'], $_POST['nome'], $_POST['preco'], $_POST['id_local']);
    }
}

$servicos = $serviceModel->listarServicos();
$locais = $localModel->listarLocais(); // Obter os locais
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Serviços</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Gerenciar Serviços</h1>
    
    <h2>Lista de Serviços</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>ID Local</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td><?= $servico['id_servico']; ?></td>
                    <td><?= $servico['nome_servico']; ?></td>
                    <td><?= $servico['preco']; ?></td>
                    <td><?= $servico['id_local']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_servico" value="<?= $servico['id_servico']; ?>">
                            <button type="submit" name="remover" onclick="return confirm('Tem certeza que deseja remover este serviço?')">Remover</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h2>Adicionar Serviço</h2>
    <form method="POST">
        Nome: <input type="text" name="nome" required>
        Preço: <input type="text" name="preco" required>
        Local:
        <select name="id_local" required>
            <?php foreach ($locais as $local): ?>
                <option value="<?= $local['id_local']; ?>"><?= $local['nome_local']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <h2>Atualizar Serviço</h2>
    <form method="POST">
        Serviço:
        <select name="id_servico" required>
            <option value="">Selecione um serviço</option>
            <?php foreach ($servicos as $servico): ?>
                <option value="<?= $servico['id_servico']; ?>"><?= $servico['nome_servico']; ?></option>
            <?php endforeach; ?>
        </select>
        Nome: <input type="text" name="nome" required>
        Preço: <input type="text" name="preco" required>
        Local:
        <select name="id_local" required>
            <?php foreach ($locais as $local): ?>
                <option value="<?= $local['id_local']; ?>"><?= $local['nome_local']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="atualizar">Atualizar</button>
    </form>
    
    <a href="dashboard.php">Voltar</a>
</body>
</html>
<?php
require_once "../../models/Service.php";
require_once "../../models/Local.php"; // Para obter os locais
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

$serviceModel = new Service();
$localModel = new Local();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar'])) {
        $serviceModel->adicionarServico($_POST['nome'], $_POST['preco'], $_POST['id_local']);
    } elseif (isset($_POST['remover'])) {
        $serviceModel->removerServico($_POST['id_servico']);
    } elseif (isset($_POST['atualizar'])) {
        $serviceModel->atualizarServico($_POST['id_servico'], $_POST['nome'], $_POST['preco'], $_POST['id_local']);
    }
}

$servicos = $serviceModel->listarServicos();
$locais = $localModel->listarLocais(); // Obter os locais
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Serviços</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Gerenciar Serviços</h1>
    
    <h2>Lista de Serviços</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>ID Local</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td><?= $servico['id_servico']; ?></td>
                    <td><?= $servico['nome_servico']; ?></td>
                    <td><?= $servico['preco']; ?></td>
                    <td><?= $servico['id_local']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_servico" value="<?= $servico['id_servico']; ?>">
                            <button type="submit" name="remover" onclick="return confirm('Tem certeza que deseja remover este serviço?')">Remover</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h2>Adicionar Serviço</h2>
    <form method="POST">
        Nome: <input type="text" name="nome" required>
        Preço: <input type="text" name="preco" required>
        Local:
        <select name="id_local" required>
            <?php foreach ($locais as $local): ?>
                <option value="<?= $local['id_local']; ?>"><?= $local['nome_local']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="adicionar">Adicionar</button>
    </form>

    <h2>Atualizar Serviço</h2>
    <form method="POST">
        Serviço:
        <select name="id_servico" required>
            <option value="">Selecione um serviço</option>
            <?php foreach ($servicos as $servico): ?>
                <option value="<?= $servico['id_servico']; ?>"><?= $servico['nome_servico']; ?></option>
            <?php endforeach; ?>
        </select>
        Nome: <input type="text" name="nome" required>
        Preço: <input type="text" name="preco" required>
        Local:
        <select name="id_local" required>
            <?php foreach ($locais as $local): ?>
                <option value="<?= $local['id_local']; ?>"><?= $local['nome_local']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="atualizar">Atualizar</button>
    </form>
    
    <a href="dashboard.php">Voltar</a>
</body>
</html>
