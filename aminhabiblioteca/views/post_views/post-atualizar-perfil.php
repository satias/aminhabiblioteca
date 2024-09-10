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
if (isset($_POST['prinome']) && isset($_POST['ultnome']) && isset($_POST['email']) && isset($_POST['oldemail']) 
&& isset($_POST['numero']) && isset($_POST['morada']) && isset($_POST['codigopostal']) && isset($_POST['photo_url'])) {
    $first_name = $_POST['prinome'];
    $last_name = $_POST['ultnome'];
    $email = $_POST['email'];
    $oldemail = $_POST['oldemail'];
    $number = $_POST['numero'];
    $address = $_POST['morada'];
    $postal_code = $_POST['codigopostal'];
    $photo_url = $_POST['photo_url'];

    $atualizar = $controlo->update_user($user_id, $first_name, $last_name, $email, $oldemail, $photo_url, $address, $postal_code, $number);

    if ($atualizar["success"]) {
        $_SESSION['user_dados']['photo_url'] = basename($photo_url);;
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
