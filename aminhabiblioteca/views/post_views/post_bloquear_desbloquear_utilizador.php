<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['user_id']) && isset($_POST['status'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    $atualizar = $controlo->alterar_status_user($user_id, $status);

    if ($atualizar) {
        echo json_encode(['success' => true, 'message' => ""]);
    } else {
        echo json_encode(['success' => false, 'message' => $bd_erro]);
    }
    exit;
}
