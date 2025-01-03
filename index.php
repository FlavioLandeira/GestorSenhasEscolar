<?php
session_start();

// Se o usuário estiver logado, redireciona para o dashboard
if (isset($_SESSION['user'])) {
    header("Location: view/dashboard.php");
    exit;
}

// Se não estiver logado, redireciona para a página de login
header("Location: view/home/login.php");
exit;
?>
