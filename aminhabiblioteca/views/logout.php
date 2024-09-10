<?php
session_start(); // Inicia a sessão

// Destroi a sessão
$_SESSION = array(); // Limpa todas as variáveis de sessão
session_destroy(); // Destrói a sessão atual

// Elimina o cookie se existir
if (isset($_COOKIE['manter_sessao'])) {
    setcookie('manter_sessao', '', time() - 3600, '/'); // Define o cookie para expirar no passado
}
if (isset($_COOKIE['sessao_ativa'])) {
    setcookie('sessao_ativa', '', time() - 3600, '/'); // Define o cookie para expirar no passado
}
include_once "funcoes/funcoes.php";
// Redireciona para a página de login 
header('Location: '.get_link("login").'');
exit;
?>