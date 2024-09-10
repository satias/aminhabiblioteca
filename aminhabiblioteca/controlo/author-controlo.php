<?php
$rootDirectory = dirname(__DIR__);
$modelo = $rootDirectory . '/modelo/author-modelo.php';
$dicionario = $rootDirectory . '/setlanguage.php';
$funcoes = $rootDirectory . '/funcoes/funcoes.php';
require $modelo;
include_once $dicionario;
include_once $funcoes;

class author_controlo
{
    public $mensagem = "";
    public $control;

    function __construct()
    {
        $this->control =  new author_modelo();
    }
    public function listar_nacionalidades()
    {
        // Inicializa arrays para armazenar os resultados das consultas
        $results_nacionalidades = [];

        // Usa uma variável global para armazenar erros da base de dados e a configuração de idioma
        global $bd_erro;

        // Executa as consultas para listar linguagens, editoras e gêneros
        $listar_nacionalidades = $this->control->listar_nacionalidades();

        // Verifica se todas as consultas retornaram resultados válidos
        if ($listar_nacionalidades instanceof mysqli_result) {
            // Itera sobre os resultados da consulta de linguagens e armazena cada linha no array correspondente
            while ($linha = $listar_nacionalidades->fetch_assoc()) {
                $results_nacionalidades[] = $linha;
            }
            // Retorna um array com sucesso e os resultados das consultas
            return [
                'success' => true,
                'nacionalidades' => $results_nacionalidades
            ];
        } else {
            // Se qualquer consulta falhar, retorna um array com sucesso falso e o erro da base de dados
            return [
                'success' => false,
                'data' => $bd_erro
            ];
        }
    }
    public function listar_autores()
    {
        // Inicializa arrays para armazenar os resultados das consultas
        $results_autores = [];

        // Usa uma variável global para armazenar erros da base de dados e a configuração de idioma
        global $bd_erro;

        // Executa as consultas para listar linguagens, editoras e gêneros
        $listar_autores = $this->control->listar_autores();

        // Verifica se todas as consultas retornaram resultados válidos
        if ($listar_autores instanceof mysqli_result) {
            // Itera sobre os resultados da consulta de linguagens e armazena cada linha no array correspondente
            while ($linha = $listar_autores->fetch_assoc()) {
                $results_autores[] = $linha;
            }
            // Retorna um array com sucesso e os resultados das consultas
            return [
                'success' => true,
                'autores' => $results_autores
            ];
        } else {
            // Se qualquer consulta falhar, retorna um array com sucesso falso e o erro da base de dados
            return [
                'success' => false,
                'data' => $bd_erro
            ];
        }
    }
    public function pesquisa_livros($authorname, $nacionalidade)
    {
        // Inicializa um array para armazenar os resultados da consulta de livros
        $results_autores = [];

        // Determina os valores dos filtros com base nos parâmetros fornecidos
        $nacionalidade_autor = ($nacionalidade === "all") ? "" : $nacionalidade;

        // Realiza a consulta dos livros com os filtros aplicados
        $resultados = $this->control->pesquisa_livros($authorname, $nacionalidade_autor);

        // Verifica se a consulta retornou um resultado válido
        if ($resultados instanceof mysqli_result) {
            // Itera sobre os resultados da consulta de livros e armazena cada linha no array correspondente
            while ($linha = $resultados->fetch_assoc()) {
                $results_autores[] = $linha;
            }

            // Retorna um array indicando sucesso e os resultados da consulta
            return [
                'success' => true,
                'data' => $results_autores
            ];
        } else {
            // Se a consulta falhar, retorna um array indicando falha e o erro da base de dados
            return [
                'success' => false,
                'data' => $resultados
            ];
        }
    }
    function listar_autor_pag($codigo_autor)
    {
        global $setlang;
        $autor = $this->control->listar_autor_pag($codigo_autor);

        if ($autor instanceof mysqli_result) {
            $autor_detalhes = [];
            while ($linha = $autor->fetch_assoc()) {
                $descricao_lingua = "";
                switch ($setlang) {
                    case 'pt':
                        $descricao_lingua = $linha['field_pt'];
                        break;
                    case 'eng':
                        $descricao_lingua = $linha['field_eng'];
                        break;
                    default:
                        $descricao_lingua = $linha['field_pt'];
                        break;
                }
                $form_birth_date = null;
                if (!empty($linha['birth_date'])) {
                    $form_birth_date = date("d-m-Y", strtotime($linha['birth_date']));
                }
                $form_death_date = null;
                if (!empty($linha['death_date'])) {
                    $form_death_date = date("d-m-Y", strtotime($linha['death_date']));
                }
                $autor_detalhes = [
                    'id' => $linha['author_id'],
                    'first_name' => $linha['first_name'],
                    'last_name' => $linha['last_name'],
                    'nacionality' => $linha['nacionality'],
                    'photo_url' => $linha['photo_url'],
                    'birth_date' => $form_birth_date,
                    'death_date' => $form_death_date,
                    'personal_site' => $linha['personal_site'],
                    'wiki_page' => $linha['wiki_page'],
                    'facebook_link' => $linha['facebook_link'],
                    'twitter_link' => $linha['twitter_link'],
                    'instagram_link' => $linha['instagram_link'],
                    'reddit_link' => $linha['reddit_link'],
                    'tiktok_link' => $linha['tiktok_link'],
                    'descricao' => $descricao_lingua
                ];
                return [
                    'success' => true,
                    'data' => $autor_detalhes
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $autor
            ];
        }
    }
    function listar_autor_work($codigo_autor)
    {
        $work = $this->control->listar_autor_work($codigo_autor);

        if ($work instanceof mysqli_result) {
            $work_detalhes = [];
            while ($linha = $work->fetch_assoc()) {
                $work_detalhes[] = $linha;
                return [
                    'success' => true,
                    'data' => $work_detalhes
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $work
            ];
        }
    }
    function listar_autor_pag_gerir($codigo_autor)
    {
        global $setlang;
        $autor = $this->control->listar_autor_pag($codigo_autor);

        if ($autor instanceof mysqli_result) {
            $autor_detalhes = [];
            while ($linha = $autor->fetch_assoc()) {
                $autor_detalhes = [
                    'id' => $linha['author_id'],
                    'first_name' => $linha['first_name'],
                    'last_name' => $linha['last_name'],
                    'nacionality' => $linha['nacionality'],
                    'photo_url' => $linha['photo_url'],
                    'birth_date' => $linha['birth_date'],
                    'death_date' => $linha['death_date'],
                    'personal_site' => $linha['personal_site'],
                    'wiki_page' => $linha['wiki_page'],
                    'facebook_link' => $linha['facebook_link'],
                    'twitter_link' => $linha['twitter_link'],
                    'instagram_link' => $linha['instagram_link'],
                    'reddit_link' => $linha['reddit_link'],
                    'tiktok_link' => $linha['tiktok_link'],
                    'descricao_pt' => $linha['field_pt'],
                    'descricao_eng' => $linha['field_eng']
                ];
                return [
                    'success' => true,
                    'data' => $autor_detalhes
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $autor
            ];
        }
    }
    function adicionar_autor($prinome, $ultnome, $datanasc, $datamorte, $nacionalidade, $websitepessoal, $wiki, $facebook, $twitter, $instagram, $reddit, $tiktok, $desc_pt, $desc_eng, $photo_url)
    {
        global $adicionarautorcamposvazios,$adicionarautorsucesso;
        $p_prinome = ($prinome != null && $prinome != "") ? $prinome : null;
        $p_ultnome = ($ultnome != null && $ultnome != "") ? $ultnome : null;
        $p_datanasc = ($datanasc != null && $datanasc != "") ? $datanasc : null;
        $p_datamorte = ($datamorte != null && $datamorte != "") ? $datamorte : null;
        $p_nacionalidade = ($nacionalidade != null && $nacionalidade != "") ? $nacionalidade : null;
        $p_websitepessoal = ($websitepessoal != null && $websitepessoal != "") ? $websitepessoal : null;
        $p_wiki = ($wiki != null && $wiki != "") ? $wiki : null;
        $p_facebook = ($facebook != null && $facebook != "") ? $facebook : null;
        $p_twitter = ($twitter != null && $twitter != "") ? $twitter : null;
        $p_instagram = ($instagram != null && $instagram != "") ? $instagram : null;
        $p_reddit = ($reddit != null && $reddit != "") ? $reddit : null;
        $p_tiktok = ($tiktok != null && $tiktok != "") ? $tiktok : null;
        $p_desc_pt = ($desc_pt != null && $desc_pt != "") ? $desc_pt : null;
        $p_desc_eng = ($desc_eng != null && $desc_eng != "") ? $desc_eng : null;
        $p_photo_url = ($photo_url != null && $photo_url != "") ? $photo_url : null;
        if ($p_prinome != null && $p_photo_url != null && $p_datanasc != null && $p_nacionalidade != null) {
            $adicionar = $this->control->adicionar_autor($p_prinome, $p_ultnome, $p_datanasc, $p_datamorte, $p_nacionalidade, $p_websitepessoal, $p_wiki, $p_facebook, $p_twitter, $p_instagram, $p_reddit, $p_tiktok, $p_desc_pt, $p_desc_eng, $p_photo_url);
            if ($adicionar === true) {
                return [
                    'success' => true,
                    'data' => $adicionarautorsucesso,
                ];
            } else {
                return [
                    'success' => false,
                    'data' => $adicionar,
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $adicionarautorcamposvazios,
            ];
        }
    }
    public function apagar_autor($autor_id)
    {
        $ativar = $this->control->apagar_autor($autor_id);
        if ($ativar === true) {
            return true;
        } else {
            return $ativar;
        }
    }
    function atualizar_autor($autor_id,$prinome, $ultnome, $datanasc, $datamorte, $nacionalidade, $websitepessoal, $wiki, $facebook, $twitter, $instagram, $reddit, $tiktok, $desc_pt, $desc_eng, $photo_url)
    {
        global $adicionarautorcamposvazios,$atualizarautorsucesso;
        $p_prinome = ($prinome != null && $prinome != "") ? $prinome : null;
        $p_ultnome = ($ultnome != null && $ultnome != "") ? $ultnome : null;
        $p_datanasc = ($datanasc != null && $datanasc != "") ? $datanasc : null;
        $p_datamorte = ($datamorte != null && $datamorte != "") ? $datamorte : null;
        $p_nacionalidade = ($nacionalidade != null && $nacionalidade != "") ? $nacionalidade : null;
        $p_websitepessoal = ($websitepessoal != null && $websitepessoal != "") ? $websitepessoal : null;
        $p_wiki = ($wiki != null && $wiki != "") ? $wiki : null;
        $p_facebook = ($facebook != null && $facebook != "") ? $facebook : null;
        $p_twitter = ($twitter != null && $twitter != "") ? $twitter : null;
        $p_instagram = ($instagram != null && $instagram != "") ? $instagram : null;
        $p_reddit = ($reddit != null && $reddit != "") ? $reddit : null;
        $p_tiktok = ($tiktok != null && $tiktok != "") ? $tiktok : null;
        $p_desc_pt = ($desc_pt != null && $desc_pt != "") ? $desc_pt : null;
        $p_desc_eng = ($desc_eng != null && $desc_eng != "") ? $desc_eng : null;
        $p_photo_url = ($photo_url != null && $photo_url != "") ? $photo_url : null;
        if ($p_prinome != null && $p_photo_url != null && $p_datanasc != null && $p_nacionalidade != null) {
            $adicionar = $this->control->atualizar_autor($autor_id, $p_prinome, $p_ultnome, $p_datanasc, $p_datamorte, $p_nacionalidade, $p_websitepessoal, $p_wiki, $p_facebook, $p_twitter, $p_instagram, $p_reddit, $p_tiktok, $p_desc_pt, $p_desc_eng, $p_photo_url);
            if ($adicionar === true) {
                return [
                    'success' => true,
                    'data' => $atualizarautorsucesso,
                ];
            } else {
                return [
                    'success' => false,
                    'data' => $adicionar,
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $adicionarautorcamposvazios,
            ];
        }
    }
}
