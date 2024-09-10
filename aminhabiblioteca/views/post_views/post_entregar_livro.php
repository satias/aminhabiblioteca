<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['requisicao_id'])) {
    $requisicao_id = $_POST['requisicao_id'];

    $entregar = $controlo->entregar_livro($requisicao_id);

    if ($entregar) {
        echo json_encode(['success' => true, 'message' => ""]);
    } else {
        echo json_encode(['success' => false, 'message' => $bd_erro]);
    }
    exit;
}
