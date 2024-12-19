<?php
require_once "../../models/Senha.php";

$idLocal = $_GET['id_local'];
$senhaModel = new Senha();

$senhas = $senhaModel->listarSenhasPorLocal($idLocal);

$html = "";
foreach ($senhas as $senha) {
    $html .= "<p>Senha: {$senha['id_senha']} - Cliente: {$senha['cliente']} - Status: {$senha['status']}</p>";
}

echo $html;
