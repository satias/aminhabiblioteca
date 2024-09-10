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
$username = $_SESSION['user_dados']['username'];
if (isset($_POST['password']) && isset($_POST['passnova']) && isset($_POST['passconfinova'])) {
    $password = $_POST['password'];
    $passnova = $_POST['passnova'];
    $passconfinova = $_POST['passconfinova'];

    $atualizar = $controlo->atualizar_password($user_id, $password, $username,$passnova,$passconfinova);

    if ($atualizar["success"]) {
        echo '
        <div class="alert alert-success alert-dismissible" role="alert">
            ' . $atualizar["data"] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="location.reload();"></button>
        </div>
        ';
    } else {
        echo '
        <div class="alert alert-warning alert-dismissible" role="alert">
            ' . $atualizar["data"] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
         ';
    }
    exit;
}
