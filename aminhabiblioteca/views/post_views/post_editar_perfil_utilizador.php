<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['user_id']) && isset($_POST['prinome']) && isset($_POST['ultnome']) && isset($_POST['email']) && isset($_POST['numero']) && isset($_POST['morada']) && isset($_POST['codigopostal'])) {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['prinome'];
    $last_name = $_POST['ultnome'];
    $email = $_POST['email'];
    $number = $_POST['numero'];
    $address = $_POST['morada'];
    $postal_code = $_POST['codigopostal'];

    $atualizar = $controlo->update_user_staff_admin($user_id, $first_name, $last_name, $email, $address, $postal_code, $number);

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
