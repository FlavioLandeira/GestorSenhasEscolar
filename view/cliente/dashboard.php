<?php
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'cliente') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente - Página Inicial</title>
    <link rel="stylesheet" href="cliente.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Bem-vindo(a), <?= $_SESSION['user']['nome'] ?></h1>
        </div>
    </header>
    
    <main>
        <div class="container">
            <nav>
                <ul>
                    <li>
                        <a href="retirar_senha.php" class="nav-button">
                            <img src="icons/apoio-suporte.png" alt="Retirar Senha" class="icon">
                            Retirar Senha
                        </a>
                        <p>Clique aqui para pegar sua senha e aguarde ser chamado para o atendimento.</p>
                    </li>
                    <li>
                        <a href="acompanhar_senhas.php" class="nav-button">
                            <img src="icons/pedido.png" alt="Acompanhar Senhas" class="icon">
                            Acompanhar Senhas
                        </a>
                        <p>Aqui você pode acompanhar em tempo real a chamada das senhas.</p>
                    </li>
                    <li>
                        <a href="historico.php" class="nav-button">
                            <img src="icons/historia.png" alt="Histórico" class="icon">
                            Histórico
                        </a>
                        <p>Confira o histórico das senhas que você retirou anteriormente.</p>
                    </li>
                    <li>
                        <a href="../../view/logout.php" class="nav-button logout">
                            <img src="icons/sair.png" alt="Sair" class="icon">
                            Sair
                        </a>
                        <p>Saia da sua conta quando terminar, para garantir a segurança.</p>
                    </li>
                </ul>
            </nav>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date("Y") ?> - Sistema de Senhas</p>
        </div>
    </footer>
</body>
</html>
