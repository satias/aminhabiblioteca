<?php
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$informaces = $controlo->atualizar_informacoes($_SESSION['user_dados']['username']);
if ($informaces['success'] == true) {
    $_SESSION['user_dados']['type'] = $informaces['data']['type'];
    $_SESSION['user_dados']['status'] = $informaces['data']['status'];
}