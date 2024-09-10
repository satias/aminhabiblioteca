<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_dados']['id'];
if (isset($_POST['ticketidname']) && isset($_POST['btnaccao']) && isset($_POST['respo'])) {
    $ticket_id = $_POST['ticketidname'];
    $btnaccao = $_POST['btnaccao'];
    $resposta = $_POST['respo'];

    $resultado = $controlo->ticket_fechar_resposta($user_id, $ticket_id, $resposta, $btnaccao);

    if ($resultado["success"]) {
        echo '
        <div class="alert alert-success alert-dismissible" role="alert">
            ' . $resultado["data"] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="location.reload();"></button>
        </div>
        ';
    } else {
        echo '
        <div class="alert alert-warning alert-dismissible" role="alert">
            ' . $resultado["data"] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
         ';
    }
    exit;
}
