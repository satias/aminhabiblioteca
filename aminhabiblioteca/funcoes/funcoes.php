<?php
// Define a variável global que contém o diretório base
$diretorio_base = "/aminhabiblioteca/";

// Função que gera um link completo com base no diretório base e no link fornecido
function get_link(string $link)
{

    // Torna a variável $diretorio_base disponível dentro da função
    global $diretorio_base;

    // Concatena o diretório base com o link fornecido para formar o link final
    $link_final = $diretorio_base . $link;

    // Retorna o link final
    return $link_final;
}
function get_link_completo(string $link, int $code = 0)
{
    global $diretorio_base;

    // Concatena o diretório base com o link fornecido
    $link_final = $diretorio_base . $link;

    // Se o codigo for fornecidos, adiciona-os ao link
    if ($code !== 0) {
        $link_final .= '/' . urlencode($code);
    }

    return $link_final;
}

function adicionarDoisDiasUteisAPartirDeHoje()
{
    // Função para verificar se a data é um final de semana
    function eFimDeSemana($data)
    {
        return (date('N', strtotime($data)) >= 6);
    }

    // Função para adicionar dias úteis
    function adicionarDiasUteis($data, $dias)
    {
        $dataAtual = new DateTime($data);
        while ($dias > 0) {
            $dataAtual->modify('+1 day');
            if (!eFimDeSemana($dataAtual->format('Y-m-d'))) {
                $dias--;
            }
        }
        return $dataAtual->format('Y-m-d');
    }

    // Obtendo a data de hoje
    $hoje = date('Y-m-d');

    // Adicionando 2 dias úteis
    $novaData = adicionarDiasUteis($hoje, 2);

    return $novaData;
}

// function get_pt_dicionario(string $variavel)
// {
//     // Torna a variável $diretorio_base disponível dentro da função
//     global $diretorio_base;
//     require_once $diretorio_base . 'dicionario/pt/pt-base.php';
//     if (isset(${$variavel})) {
//         return ${$variavel};
//     }
// }
// function get_eng_dicionario(string $variavel)
// {
//     // Torna a variável $diretorio_base disponível dentro da função
//     global $diretorio_base;
//     require_once $diretorio_base . 'dicionario/eng/eng-base.php';
//     if (isset(${$variavel})) {
//         return ${$variavel};
//     }
// }
