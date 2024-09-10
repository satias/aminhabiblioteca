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
if (isset($_POST['password'])) {
    $password = $_POST['password'];

    $atualizar = $controlo->pedir_apagar_user($user_id, $password, $username);
    $response = array();
    if ($atualizar["success"]) {
        $response["success"] = true;
        $response["message"] = '
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                        <span class="subtitulo-font color-primary fs-5" id="exampleModalLabel">' . $mensagemapagarsucessotitulo . '</span>
                </div>
                <div class="modal-body texto-font py-5 color-text">
                        ' . $atualizar["data"] . '
                </div>
                <div class="modal-footer">
                        <a type="button" href="' . get_link("logout") . '" class="btn-vazio-borda-accent color-text px-2">' . $confirmar . '</a>
                </div>
            </div>
        </div>
        ';
        $_SESSION = array(); // Limpa todas as variáveis de sessão
        session_destroy(); // Destrói a sessão atual
    } else {
        $response["success"] = false;
        $response["message"] = $atualizar["data"];
    }
    // Retornar a resposta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
