<?php
session_start();
if ($_SESSION['user']['tipo_utilizador'] !== 'funcionario') {
    header("Location: ../login.php");
    exit;
}

require_once "../../controllers/FuncionarioController.php";

if (isset($_GET['id'])) {
    $idRelatorio = intval($_GET['id']);
    $controller = new FuncionarioController();
    $relatorio = $controller->buscarRelatorioPorId($idRelatorio);

    if ($relatorio) {
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=relatorio_{$idRelatorio}.pdf");
        echo $relatorio['conteudo']; // Assumindo que o conteúdo está em um campo da tabela
        exit;
    } else {
        echo "Relatório não encontrado.";
    }
} else {
    echo "ID do relatório não fornecido.";
}
?>
