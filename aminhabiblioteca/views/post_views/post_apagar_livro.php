<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new controlo();

if (isset($_POST['livro_id'])) {
    $livro_id = $_POST['livro_id'];

    $apagar = $controlo->apagar_livro($livro_id);
    if ($apagar === true) {
        echo json_encode(['success' => true, 'message' => $apagarlivrosucesso]);
    } else {
        echo json_encode(['success' => false, 'message' => $apagar]);
    }
    exit;
}
