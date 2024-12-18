<?php
require_once "../../models/Senha.php";
session_start();

if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}

$senhaModel = new Senha();
$idLocal = $_SESSION['user']['id_local'];

if ($senhaModel->chamarProximaSenha($idLocal)) {
    echo "Próxima senha chamada com sucesso!";
} else {
    echo "Nenhuma senha disponível na fila.";
}
header("Location: dashboard.php");
exit;
?>
