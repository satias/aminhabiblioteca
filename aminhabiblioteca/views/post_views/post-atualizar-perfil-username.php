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
$username_antigo = $_SESSION['user_dados']['username'];
if (isset($_POST['password']) && isset($_POST['username'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];

    $atualizar = $controlo->atualizar_username($user_id, $password, $username,$username_antigo);

    if ($atualizar["success"]) {
        $_SESSION['user_dados']['username'] =$username;
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
