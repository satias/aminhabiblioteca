<?php
$rootDirectory = dirname(__DIR__);
$modelo = $rootDirectory . '/modelo/modelo.php';
$dicionario = $rootDirectory . '/setlanguage.php';
$funcoes = $rootDirectory . '/funcoes/funcoes.php';
require $modelo;
include_once $dicionario;
include_once $funcoes;

class controlo
{
    public $mensagem = "";
    public $control;

    function __construct()
    {
        $this->control =  new modelo();
    }

    public function n_livros_autores()
    {
        global $bd_erro;
        $nlivros = $this->control->contador_livros();
        $nautores = $this->control->contador_autores();
        $n_livros_autores = [
            "nlivros" => $nlivros,
            "nautores" => $nautores
        ];
        return $n_livros_autores;
    }
    public function novos_livros_home()
    {
        $livros = $this->control->novos_livros_home();
        if ($livros instanceof mysqli_result) {
            $novos_livros_home = [];
            while ($linha = $livros->fetch_assoc()) {
                $novos_livros_home[] = [
                    "title" => $linha['title'],
                    "internal_code" => $linha['internal_code'],
                    "fcover_url" => $linha['fcover_url']
                ];
            }
            return $novos_livros_home;
        }
    }
    public function livros_populares_home()
    {
        $livros = $this->control->livros_populares_home();
        if ($livros instanceof mysqli_result) {
            $novos_livros_home = [];
            while ($linha = $livros->fetch_assoc()) {
                $novos_livros_home[] = [
                    "title" => $linha['book_title'],
                    "internal_code" => $linha['internal_code'],
                    "fcover_url" => $linha['front_cover']
                ];
            }
            return $novos_livros_home;
        }
    }
    public function categorias_populares_home()
    {
        global $setlang;
        $cate_pop = $this->control->categorias_populares_home();
        if ($cate_pop instanceof mysqli_result) {
            $cate_pop_home = [];
            while ($linha = $cate_pop->fetch_assoc()) {
                $categoria = "";
                switch ($setlang) {
                    case 'pt':
                        $categoria = $linha['genre_name_pt'];
                        break;
                    case 'eng':
                        $categoria = $linha['genre_name_eng'];
                        break;
                    default:
                        $categoria = $linha['genre_name_pt'];
                        break;
                }
                $cate_pop_home[] = [
                    "categoria" => $categoria,
                    "num_requests" => $linha['num_requests']
                ];
            }
            return $cate_pop_home;
        }
    }
    public function pesquisa_rapida_home($nome)
    {
        $results = [];

        // Pesquisar por livros
        $pesquisa_rapida_livro = $this->control->pesquisa_rapida_livro($nome);
        if ($pesquisa_rapida_livro instanceof mysqli_result) {
            while ($row = $pesquisa_rapida_livro->fetch_assoc()) {
                $row['type'] = 'book';
                $results[] = $row;
            }
        }

        // Pesquisar por autores
        $pesquisa_rapida_autor = $this->control->pesquisa_rapida_autor($nome);
        if ($pesquisa_rapida_autor instanceof mysqli_result) {
            while ($row = $pesquisa_rapida_autor->fetch_assoc()) {
                $row['type'] = 'author';
                $results[] = $row;
            }
        }

        // Ordenar resultados pelo nome
        usort($results, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return [
            'success' => true,
            'data' => $results
        ];
    }
    public function listar_filtros()
    {
        // Inicializa arrays para armazenar os resultados das consultas
        $results_linguagens = [];
        $results_editoras = [];
        $results_generos = [];

        // Usa uma variável global para armazenar erros do banco de dados e a configuração de idioma
        global $bd_erro, $setlang;

        // Executa as consultas para listar linguagens, editoras e gêneros
        $listar_linguagens = $this->control->listar_linguagens();
        $listar_editoras = $this->control->listar_editoras();
        $listar_generos = $this->control->listar_generos();

        // Verifica se todas as consultas retornaram resultados válidos
        if ($listar_linguagens instanceof mysqli_result && $listar_editoras instanceof mysqli_result && $listar_generos instanceof mysqli_result) {
            // Itera sobre os resultados da consulta de linguagens e armazena cada linha no array correspondente
            while ($linha = $listar_linguagens->fetch_assoc()) {
                $results_linguagens[] = $linha;
            }

            // Itera sobre os resultados da consulta de editoras e armazena cada linha no array correspondente
            while ($linha = $listar_editoras->fetch_assoc()) {
                $results_editoras[] = $linha;
            }

            // Itera sobre os resultados da consulta de gêneros e armazena cada linha no array correspondente
            while ($linha = $listar_generos->fetch_assoc()) {
                // Determina o nome do gênero conforme o idioma configurado
                $genero_lingua = "";
                switch ($setlang) {
                    case 'pt':
                        $genero_lingua = $linha['genre_pt'];
                        break;
                    case 'eng':
                        $genero_lingua = $linha['genre_eng'];
                        break;
                    default:
                        $genero_lingua = $linha['genre_pt']; // Caso padrão: usar nome em português
                        break;
                }

                // Adiciona cada gênero ao array de resultados de gêneros
                $results_generos[] = [
                    'genre_id' => $linha['genre_id'],
                    'genre_name' => $genero_lingua
                ];
            }

            // Retorna um array com sucesso e os resultados das consultas
            return [
                'success' => true,
                'linguagens' => $results_linguagens,
                'editoras' => $results_editoras,
                'generos' => $results_generos,
            ];
        } else {
            // Se qualquer consulta falhar, retorna um array com sucesso falso e o erro do banco de dados
            return [
                'success' => false,
                'data' => $bd_erro
            ];
        }
    }
    public function listar_livros()
    {
        // Inicializa um array para armazenar os resultados da consulta de livros
        $results_livros = [];

        // Executa a consulta para listar livros
        $listar_livros = $this->control->listar_livros();

        // Verifica se a consulta retornou um resultado válido
        if ($listar_livros instanceof mysqli_result) {
            // Itera sobre os resultados da consulta de livros e armazena cada linha no array correspondente
            while ($linha = $listar_livros->fetch_assoc()) {
                $results_livros[] = $linha;
            }

            // Retorna um array indicando sucesso e os resultados da consulta
            return [
                'success' => true,
                'livros' => $results_livros
            ];
        } else {
            // Se a consulta falhar, retorna um array indicando falha e o erro do banco de dados
            return [
                'success' => false,
                'data' => $listar_livros
            ];
        }
    }
    public function pequisa_livros($bookname, $authorname, $genero, $linguagem, $editora, $dispo, $indispo, $localcons)
    {
        // Inicializa um array para armazenar os resultados da consulta de livros
        $results_livros = [];

        // Determina os valores dos filtros com base nos parâmetros fornecidos
        $linguagem_livro = ($linguagem === "all") ? "" : $linguagem;
        $editora_livro = ($editora === "all") ? "" : $editora;
        $genero_livro = ($genero === "all") ? "" : $genero;

        // Define a disponibilidade com base nos parâmetros fornecidos
        if ($dispo == 1) {
            $disponibilidade = 1;
        } elseif ($indispo == 1) {
            $disponibilidade = 0;
        } elseif ($localcons == 1) {
            $disponibilidade = 10;
        } else {
            $disponibilidade = null;
        }

        // Realiza a consulta dos livros com os filtros aplicados
        $resultados = $this->control->pesquisa_livros($bookname, $authorname, $genero_livro, $linguagem_livro, $editora_livro, $disponibilidade);

        // Verifica se a consulta retornou um resultado válido
        if ($resultados instanceof mysqli_result) {
            // Itera sobre os resultados da consulta de livros e armazena cada linha no array correspondente
            while ($linha = $resultados->fetch_assoc()) {
                $results_livros[] = $linha;
            }

            // Retorna um array indicando sucesso e os resultados da consulta
            return [
                'success' => true,
                'data' => $results_livros
            ];
        } else {
            // Se a consulta falhar, retorna um array indicando falha e o erro do banco de dados
            return [
                'success' => false,
                'data' => $resultados
            ];
        }
    }
    public function listar_livro_pag($codigo_livro)
    {
        global $setlang;
        $livro = $this->control->listar_livro_pag($codigo_livro);

        if ($livro instanceof mysqli_result) {
            $livro_detalhes = [];
            while ($linha = $livro->fetch_assoc()) {
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
                $form_release_date = null;
                if (!empty($linha['release_date'])) {
                    $form_release_date = date("d-m-Y", strtotime($linha['release_date']));
                }
                $livro_detalhes = [
                    'id' => $linha['book_id'],
                    'title' => $linha['title'],
                    'internal_code' => $linha['internal_code'],
                    'fcover_url' => $linha['fcover_url'],
                    'bcover_url' => $linha['bcover_url'],
                    'available' => $linha['available'],
                    'physical_condition' => $linha['physical_condition'],
                    'release_date' => $form_release_date,
                    'available_req' => $linha['available_req'],
                    'language' => $linha['language'],
                    'publisher' => $linha['publisher'],
                    'isbn' => $linha['isbn'],
                    'page_number' => $linha['page_number'],
                    'edition_number' => $linha['edition_number'],
                    'author_id' => $linha['author_id'],
                    'first_name' => $linha['first_name'],
                    'last_name' => $linha['last_name'],
                    'photo_url' => $linha['photo_url'],
                    'descricao' => $descricao_lingua,
                    'descricao_pt' => $linha['field_pt'],
                    'descricao_eng' => $linha['field_eng'],
                    'datalanc' => $linha['release_date']
                ];
                return [
                    'success' => true,
                    'data' => $livro_detalhes
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $livro
            ];
        }
    }
    public function listar_livro_generos($codigo_livro)
    {
        global $setlang;
        $generos = $this->control->listar_livro_generos($codigo_livro);

        if ($generos instanceof mysqli_result) {
            $lista_generos = [];
            while ($linha = $generos->fetch_assoc()) {
                $generos_lingua = "";
                switch ($setlang) {
                    case 'pt':
                        $generos_lingua = $linha['field_pt'];
                        break;
                    case 'eng':
                        $generos_lingua = $linha['field_eng'];
                        break;
                    default:
                        $generos_lingua = $linha['field_pt'];
                        break;
                }
                $lista_generos[] = [
                    'genero' => $generos_lingua,
                    'genero_id' => $linha['genre_id']
                ];
            }
            return [
                'success' => true,
                'data' => $lista_generos
            ];
        } else {
            return [
                'success' => false,
                'data' => $generos
            ];
        }
    }
    public function adicionar_favorito($user_id, $book_id)
    {
        $adicionar = $this->control->adicionar_favorito($user_id, $book_id);
        if ($adicionar === true) {
            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false,
                'data' => $adicionar
            ];
        }
    }
    public function verificar_favorito($user_id, $book_id)
    {
        $verificar = $this->control->verificar_favorito($user_id, $book_id);
        if ($verificar > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function retirar_favorito($user_id, $book_id)
    {
        $retirar = $this->control->retirar_favorito($user_id, $book_id);
        if ($retirar === true) {
            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false,
                'data' => $retirar
            ];
        }
    }
    public function requisitar_livro($user_id, $book_id, $user_status)
    {
        global $contabloqueada, $operafalhada, $reqs5, $contsuporte, $vermultas, $reqsucesso, $levantarlivro, $reqinfor;
        $verifica_livro_requisitado = $this->control->verifica_livro_requisitado($user_id, $book_id);
        $ver_livro_req = ($verifica_livro_requisitado === $user_id) ? true : false;
        if ($user_status != 0 && !$ver_livro_req) {
            $numero_reqs = $this->control->contador_requisicoes($user_id);
            $numero_revs = $this->control->contador_reservas($user_id);
            $numero_reqs_revs = $numero_reqs + $numero_revs;
            if ($numero_reqs < 5 && $numero_reqs_revs < 5) {
                $start_at = adicionarDoisDiasUteisAPartirDeHoje(); // metodo nas funcoes
                $requisitar = $this->control->requisitar_livro($user_id, $book_id, $start_at);
                if ($requisitar === true) {
                    return [
                        'success' => true,
                        'data' => [
                            'reqsucesso' => $reqsucesso,
                            'levantarlivro' => $levantarlivro,
                            'reqinfor' => $reqinfor
                        ]
                    ];
                } else {
                    return [
                        'success' => false,
                        'data' => [
                            'reqfalhada' => $operafalhada,
                            'reqsucesso' => $requisitar
                        ]
                    ];
                }
            } elseif ($numero_reqs == 5 || $numero_reqs_revs >= 5) {
                return [
                    'success' => false,
                    'data' => [
                        'reqfalhada' => $operafalhada,
                        'reqs5' => $reqs5,
                        'contsuporte' => $contsuporte,

                    ]
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => [
                    'reqfalhada' => $operafalhada,
                    'conta_block' => $contabloqueada,
                    'vermultas' => $vermultas,
                    'contsuporte' => $contsuporte,
                ]
            ];
        }
    }
    public function reservar_livro($user_id, $book_id, $user_status)
    {
        global $contabloqueada, $operafalhada, $rese2, $contsuporte, $vermultas, $resesucesso, $reserinfor, $reslimite;
        $verifica_livro_reservado = $this->verifica_livro_reservado($book_id);
        $ver_livro_res = ($verifica_livro_reservado === $user_id) ? true : false;
        if ($user_status != 0 && !$ver_livro_res) {
            $max_queue_num = $this->control->contador_reservas_do_livro($book_id);
            if ($max_queue_num < 2) {
                $numero_reqs = $this->control->contador_requisicoes($user_id);
                $numero_revs = $this->control->contador_reservas($user_id);
                $numero_reqs_revs = $numero_reqs + $numero_revs;
                if ($numero_revs < 2 && $numero_reqs_revs < 5) {
                    $queue_num = 0;
                    if ($max_queue_num == 0) {
                        $queue_num = 1;
                    }
                    if ($max_queue_num == 1) {
                        $queue_num = 2;
                    }
                    $reservar = $this->control->reservar_livro($user_id, $book_id, $queue_num);
                    if ($reservar === true) {
                        return [
                            'success' => true,
                            'data' => [
                                'resesucesso' => $resesucesso,
                                'reserinfor' => $reserinfor
                            ]
                        ];
                    } else {
                        return [
                            'success' => false,
                            'data' => [
                                'reqfalhada' => $operafalhada,
                                'contsuporte' => $contsuporte,
                                'reqsucesso' => $reservar,
                            ]
                        ];
                    }
                } elseif ($numero_revs == 2 || $numero_reqs_revs >= 5) {
                    return [
                        'success' => false,
                        'data' => [
                            'reqfalhada' => $operafalhada,
                            'rese2' => $rese2,
                            'contsuporte' => $contsuporte,

                        ]
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'data' => [
                        'reqfalhada' => $operafalhada,
                        'reslimite' => $reslimite,
                        'contsuporte' => $contsuporte,

                    ]
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => [
                    'reqfalhada' => $operafalhada,
                    'conta_block' => $contabloqueada,
                    'vermultas' => $vermultas,
                    'contsuporte' => $contsuporte,
                ]
            ];
        }
    }
    public function verifica_livro_requisitado($book_id)
    {
        $verifica_livro_requisitado = $this->control->verifica_livro_requisitado($book_id);
        if ($verifica_livro_requisitado && $verifica_livro_requisitado->num_rows > 0) {
            $row = $verifica_livro_requisitado->fetch_assoc();
            return $row['req_user_id'];
        } else {
            return null; // Ou qualquer outra ação em caso de não encontrar resultados
        }
    }
    public function verifica_livro_reservado($book_id)
    {
        $verifica_livro_reservado = $this->control->verifica_livro_reservado($book_id);
        if ($verifica_livro_reservado && $verifica_livro_reservado->num_rows > 0) {
            $rows = [];
            while ($linha = $verifica_livro_reservado->fetch_assoc()) {
                $int_linha = array_map('intval', $linha);
                $rows[] = $int_linha;
            }
            return $rows;
        } else {
            return null; // Ou qualquer outra ação em caso de não encontrar resultados
        }
    }
    public function adicionar_livro($titulo, $linguagem, $codinter, $editora, $datalanc, $isbn, $numedit, $numpag, $condicao, $discbiblio, $disreq, $desc_pt, $desc_eng, $photo_url_capa, $photo_url_contracapa, $generos, $autor_id)
    {
        global $adicionarlivrocamposvazios, $adicionarlivrosucesso, $codinterisbn;
        $p_titulo = ($titulo != null && $titulo != "") ? $titulo : null;
        $p_linguagem = ($linguagem != null && $linguagem != "") ? $linguagem : null;
        $p_codinter = ($codinter != null && $codinter != "") ? $codinter : null;
        $p_editora = ($editora != null && $editora != "") ? $editora : 'UNKNOWN';
        $p_datalanc = ($datalanc != null && $datalanc != "") ? $datalanc : null;
        $p_isbn = ($isbn != null && $isbn != "") ? $isbn : null;
        $p_numedit = ($numedit != null && $numedit != "") ? $numedit : null;
        $p_numpag = ($numpag != null && $numpag != "") ? $numpag : null;
        $p_condicao = ($condicao != null && $condicao != "") ? $condicao : null;
        $p_discbiblio = ($discbiblio != null) ? $discbiblio : 0;
        $p_disreq = ($disreq != null) ? $disreq : 0;
        $p_desc_pt = ($desc_pt != null && $desc_pt != "") ? $desc_pt : null;
        $p_desc_eng = ($desc_eng != null && $desc_eng != "") ? $desc_eng : null;
        $p_photo_url_capa = ($photo_url_capa != null && $photo_url_capa != "") ? $photo_url_capa : null;
        $p_photo_url_contracapa = ($photo_url_contracapa != null && $photo_url_contracapa != "") ? $photo_url_contracapa : null;
        $p_generos = ($generos != null && $generos != "") ? $generos : null;
        $p_autor_id = ($autor_id != null && $autor_id != "") ? $autor_id : null;
        if ($p_titulo != null && $p_codinter != null && $p_photo_url_capa != null && $p_discbiblio != null && $p_condicao != null && $p_disreq != null && $p_linguagem != null) {

            $ver_livros = $this->listar_internalcode_isbn();
            if ($ver_livros['success']) {
                $ver_codinter = false;
                $ver_isbn = false;
                foreach ($ver_livros['livros'] as $linha) {
                    if ($p_codinter == $linha['internal_code']) {
                        $ver_codinter = true;
                    }
                    if ($p_isbn != null && $p_isbn == $linha['isbn']) {
                        $ver_isbn = true;
                    }
                }
                if ($ver_codinter == false && $ver_isbn == false) {
                    $adicionar = $this->control->adicionar_livro(
                        $p_titulo,
                        $p_linguagem,
                        $p_codinter,
                        $p_editora,
                        $p_datalanc,
                        $p_isbn,
                        $p_numedit,
                        $p_numpag,
                        $p_condicao,
                        $p_discbiblio,
                        $p_disreq,
                        $p_desc_pt,
                        $p_desc_eng,
                        $p_photo_url_capa,
                        $p_photo_url_contracapa,
                        $p_generos,
                        $p_autor_id
                    );
                    if ($adicionar === true) {
                        return [
                            'success' => true,
                            'data' => $adicionarlivrosucesso,
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
                        'data' => $codinterisbn,
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'data' => $ver_livros['data'],
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $adicionarlivrocamposvazios,
            ];
        }
    }
    public function apagar_livro($livro_id)
    {
        global $apagarimpossivel;
        $ativar = $this->control->apagar_livro($livro_id);
        if ($ativar === true) {
            return true;
        } else if ($ativar === false){
            return $apagarimpossivel;
        }else {
            return $ativar;
        }
    }
    public function atualizar_livro($book_id, $titulo, $linguagem, $codinter, $editora, $datalanc, $isbn, $numedit, $numpag, $condicao, $discbiblio, $disreq, $desc_pt, $desc_eng, $photo_url_capa, $photo_url_contracapa, $generos, $autor_id)
    {
        global $adicionarlivrocamposvazios, $atualizarlivrosucesso, $codinterisbn;
        $p_titulo = ($titulo != null && $titulo != "") ? $titulo : null;
        $p_linguagem = ($linguagem != null && $linguagem != "") ? $linguagem : null;
        $p_codinter = ($codinter != null && $codinter != "") ? $codinter : null;
        $p_editora = ($editora != null && $editora != "") ? $editora : 'UNKNOWN';
        $p_datalanc = ($datalanc != null && $datalanc != "") ? $datalanc : null;
        $p_isbn = ($isbn != null && $isbn != "") ? $isbn : null;
        $p_numedit = ($numedit != null && $numedit != "") ? $numedit : null;
        $p_numpag = ($numpag != null && $numpag != "") ? $numpag : null;
        $p_condicao = ($condicao != null && $condicao != "") ? $condicao : null;
        $p_discbiblio = ($discbiblio != null) ? $discbiblio : 0;
        $p_disreq = ($disreq != null) ? $disreq : 0;
        $p_desc_pt = ($desc_pt != null && $desc_pt != "") ? $desc_pt : null;
        $p_desc_eng = ($desc_eng != null && $desc_eng != "") ? $desc_eng : null;
        $p_photo_url_capa = ($photo_url_capa != null && $photo_url_capa != "") ? $photo_url_capa : null;
        $p_photo_url_contracapa = ($photo_url_contracapa != null && $photo_url_contracapa != "") ? $photo_url_contracapa : null;
        $p_generos = ($generos != null && $generos != "") ? $generos : null;
        $p_autor_id = ($autor_id != null && $autor_id != "") ? $autor_id : null;
        if ($p_titulo != null && $p_codinter != null && $p_photo_url_capa != null && $p_discbiblio != null && $p_condicao != null && $p_disreq != null && $p_linguagem != null) {

            $ver_livros = $this->listar_internalcode_isbn();
            if ($ver_livros['success']) {
                $ver_codinter = false;
                $ver_isbn = false;
                foreach ($ver_livros['livros'] as $linha) {
                    if ($p_codinter == $linha['internal_code']) {
                        if ($book_id != $linha['id']) {
                            $ver_codinter = true;
                        }
                    }
                    if ($p_isbn != null && $p_isbn == $linha['isbn']) {
                        if ($book_id != $linha['id']) {
                            $ver_isbn = true;
                        }
                    }
                }
                if ($ver_codinter == false && $ver_isbn == false) {
                    $adicionar = $this->control->atualizar_livro(
                        $book_id,
                        $p_titulo,
                        $p_linguagem,
                        $p_codinter,
                        $p_editora,
                        $p_datalanc,
                        $p_isbn,
                        $p_numedit,
                        $p_numpag,
                        $p_condicao,
                        $p_discbiblio,
                        $p_disreq,
                        $p_desc_pt,
                        $p_desc_eng,
                        $p_photo_url_capa,
                        $p_photo_url_contracapa,
                        $p_generos,
                        $p_autor_id
                    );
                    if ($adicionar === true) {
                        return [
                            'success' => true,
                            'data' => $atualizarlivrosucesso,
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
                        'data' => $codinterisbn,
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'data' => $ver_livros['data'],
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $adicionarlivrocamposvazios,
            ];
        }
    }
    public function listar_internalcode_isbn()
    {
        $results_livros = [];
        $listar_livros = $this->control->listar_internalcode_isbn();

        if ($listar_livros instanceof mysqli_result) {
            while ($linha = $listar_livros->fetch_assoc()) {
                $results_livros[] = $linha;
            }
            return [
                'success' => true,
                'livros' => $results_livros
            ];
        } else {
            return [
                'success' => false,
                'data' => $listar_livros
            ];
        }
    }
}
