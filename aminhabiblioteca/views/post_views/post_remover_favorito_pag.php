<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new controlo();

if (isset($_POST['bookid'])) {
    $bookid = $_POST['bookid'];

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user_id = $_SESSION['user_dados']['id'];

    $remover = $controlo->retirar_favorito($user_id, $bookid);
    if ($remover['success']) {
        // Envia uma resposta JSON indicando sucesso
        echo json_encode(['success' => true]);
    } else {
        // Envia uma resposta JSON com a mensagem de erro
        echo json_encode(['success' => false, 'data' => $remover['data']]);
    }
    exit();
}
?>