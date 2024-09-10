<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $resultado = $controlo->pedir_apagar_user_procconta($user_id);

    if ($resultado) {
        echo '
        <div class="alert alert-success alert-dismissible" role="alert">
            ' . htmlspecialchars($mensagemapagarutilizadorpedir) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="location.reload();"></button>
        </div>
        ';
    } else {
        echo '
        <div class="alert alert-warning alert-dismissible" role="alert">
            ' . htmlspecialchars($db_erro) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
         ';
    }
    exit;
}
