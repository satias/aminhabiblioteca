<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $apagar = $controlo->apagar_utlizador_perma($user_id);
    if ($apagar) {
        echo json_encode(['success' => true, 'message' => $utilizadorapagadoperma]);
    } else {
        echo json_encode(['success' => false, 'message' => $bd_erro]);
    }
    exit;
}
