<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['user_id']) && isset($_POST['multa_id']) && isset($_POST['totalmultas'])) {
    $user_id = $_POST['user_id'];
    $multa_id = $_POST['multa_id'];
    $totalmultas = $_POST['totalmultas'];

    $atualizar = $controlo->pagar_multa_user($user_id,$multa_id, $totalmultas);

    if ($atualizar) {
        echo json_encode(['success' => true, 'message' => ""]);
    } else {
        echo json_encode(['success' => false, 'message' => $bd_erro]);
    }
    exit;
}
