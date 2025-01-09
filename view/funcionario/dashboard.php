<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Funcionário</title>
    <link rel="stylesheet" href="func.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Painel do Funcionário</h1>
            <h2>Bem-vindo, <?php echo $_SESSION['user']['nome']; ?></h2>
        </div>
    </header>

    <main>
        <div class="container">
            <nav>
                <ul>
                    <li>
                        <a href="gestao_fila.php" class="nav-button">
                            <img src="icons/gerenciamento.png" alt="Gestão de Fila" class="icon">
                            Gestão de Fila
                        </a>
                        <p>Gerencie a fila de atendimentos e administre as senhas.</p>
                    </li>
                    <li>
                        <a href="historico.php" class="nav-button">
                            <img src="icons/historia.png" alt="Histórico" class="icon">
                            Histórico de Atendimentos
                        </a>
                        <p>Veja o histórico de atendimentos realizados.</p>
                    </li>
                    <li>
                        <a href="relatorios.php" class="nav-button">
                            <img src="icons/plano.png" alt="Relatórios" class="icon">
                            Relatórios
                        </a>
                        <p>Acesse relatórios sobre o atendimento e a fila.</p>
                    </li>
                    <li>
                        <a href="../../view/logout.php" class="nav-button logout">
                            <img src="icons/sair.png" alt="Sair" class="icon">
                            Sair
                        </a>
                        <p>Saia da sua conta para garantir a segurança.</p>
                    </li>
                </ul>
            </nav>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date("Y") ?> - Sistema de Gestão de Senhas</p>
        </div>
    </footer>
</body>
</html>
