<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();


if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confi_pass'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confi_pass = $_POST['confi_pass'];

    $registo = $controlo->registo($email, $username, $password, $confi_pass);

    if ($registo) {
        echo '
        <div class="alert alert-success" role="alert">
            <div class="d-flex">
                <h4 class="alert-heading">'. $conta_criada .'</h4>
                <button type="button" class=" ms-auto btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <p>'.$mensagem_conta_criada1.' <a class="color-unset" href='.get_link("login").'>'.$login.'</a>'.$mensagem_conta_criada2.'</p>
        </div>
        ';
    } else {
        echo '
        <div class="alert alert-warning alert-dismissible" role="alert">
            ' . $controlo->mensagem . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
         ';
    }
    exit;
}
