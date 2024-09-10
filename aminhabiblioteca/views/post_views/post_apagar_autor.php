<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/author-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new author_controlo();

if (isset($_POST['autor_id'])) {
    $autor_id = $_POST['autor_id'];

    $apagar = $controlo->apagar_autor($autor_id);
    if ($apagar === true) {
        echo json_encode(['success' => true, 'message' => $apagarautorsucesso]);
    } else {
        echo json_encode(['success' => false, 'message' => $apagar]);
    }
    exit;
}
