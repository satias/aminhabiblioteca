<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];

    $resultado = $controlo->cancelar_requisicao($req_id);

    if ($resultado["success"]) {
        echo '
        <div class="alert alert-success alert-dismissible" role="alert">
            ' . htmlspecialchars($resultado["data"]) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="location.reload();"></button>
        </div>
        ';
    } else {
        echo '
        <div class="alert alert-warning alert-dismissible" role="alert">
            ' . htmlspecialchars($resultado["data"]) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
         ';
    }
    exit;
}
