<?php
require_once "../../models/Senha.php";
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_utilizador'] !== 'cliente') {
    exit;
}

$senhaModel = new Senha();
$idServico = $_GET['id_servico'] ?? null;

if ($idServico) {
    $fila = $senhaModel->listarFilaPorServico($idServico);
    echo "<p>Pessoas na fila: " . count($fila) . "</p>";
}
?>
