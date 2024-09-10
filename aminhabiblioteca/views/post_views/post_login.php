<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();


if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['manter'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $manter = $_POST['manter'];

    $login = $controlo->login($username, $password);

    if ($login['success']) {
        session_start();
        $_SESSION['user_dados'] = $login['data'];
        setcookie("user_data", json_encode($login['data']), time() + (30 * 24 * 60 * 60), "/");
        if ($manter) {
            setcookie("manter_sessao", 1, time() + (30 * 24 * 60 * 60), "/");
        } else {
            setcookie("manter_sessao", 0, time() + (30 * 24 * 60 * 60), "/");
            setcookie("sessao_ativa", 1, 0, "/");
        }
        echo '
        <a id="invisible-link" href="javascript:void(0);" style="display: none;"></a>
        <script>
        $(document).ready(function() {
            $("#invisible-link").trigger("click");
        });
    
        $("#invisible-link").on("click", function() {
            window.location.href = "' . get_link("") . '";
        });
    </script>
    ';
        exit;
    } else {
        echo '
        <div class="alert alert-warning alert-dismissible" role="alert">
            ' . $login['data'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
         ';
        exit;
    }
}
