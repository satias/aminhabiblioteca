<?php
if ((isset($_COOKIE['manter_sessao']) && $_COOKIE['manter_sessao'] == 1) || 
    (isset($_COOKIE['manter_sessao']) && $_COOKIE['manter_sessao'] == 0 && isset($_COOKIE['sessao_ativa']))) {
    $user_type = ""; // Inicializa $user_type como uma string vazia
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
        // Verifica se a variável de sessão 'user_dados' está definida
        if (isset($_SESSION['user_dados'])) {
            // Se 'user_dados' estiver definida
            $atualizar_info = "views/post_views/post_atualizar_informacoes.php";
            include_once $atualizar_info;
            $user_type = $_SESSION['user_dados']['type'];
        } else if (!isset($_SESSION['user_dados']) && isset($_COOKIE['user_data'])) {
            $_SESSION['user_dados'] = json_decode($_COOKIE['user_data'], true);
            $atualizar_info = "views/post_views/post_atualizar_informacoes.php";
            include_once $atualizar_info;
            $user_type = $_SESSION['user_dados']['type'];
        } 
        else {
            header('Location: ' . get_link("regist") . '');
            exit; // Termina o script para garantir que o redirecionamento seja seguido
        }
    }
} else {
    header('Location: ' . get_link("logout") . '');
    exit;
}
