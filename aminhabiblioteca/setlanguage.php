<?php
// Verifica se existe o parâmetro 'setlang' na URL, indicando uma mudança de idioma
if (isset($_GET['setlang'])) {
    $setlang = $_GET['setlang']; // Armazena o valor do idioma selecionado na variável $setlang
    // Define uma cookie chamada 'lang' com o valor do idioma escolhido, persistente por um longo período
    setcookie("lang", "$setlang", 2147483647, "/");
}

// Verifica se a cookie 'lang' não está definida, ou seja, se é a primeira visita do utilizador ao site
if (!isset($_COOKIE['lang'])) {
    // Define a cookie 'lang' com o valor 'pt' (português) como idioma padrão
    setcookie("lang", "pt", 2147483647, "/");
}

// Verifica se a cookie 'lang' está definida
if (isset($_COOKIE['lang'])) {
    $setlang = $_COOKIE['lang']; // Atribui o valor da cookie à variável $setlang
} else {
    $setlang = null; // Se a cookie não estiver definida, $setlang é nulo (o que não deve acontecer por causa do código anterior)
}

// Carrega o ficheiro de tradução correspondente ao idioma definido
switch ($setlang) {
    case 'pt':
        // Se $setlang for 'pt', inclui o ficheiro de tradução para português
        include "dicionario/pt/pt-base.php";
        break;
    case 'eng':
        // Se $setlang for 'eng', inclui o ficheiro de tradução para inglês
        include "dicionario/eng/eng-base.php";
        break;
    default:
        // Se $setlang tiver um valor inesperado, carrega o ficheiro de tradução para português como padrão
        include "dicionario/pt/pt-base.php";
        break;
}





