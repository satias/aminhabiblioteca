<?php
require_once 'connection.php';
$dicionario = $rootDirectory . '/setlanguage.php';
include_once $dicionario;
// Definição da classe 'modelo' que herda de 'coneccao'
class author_modelo extends coneccao
{
    // Propriedade para armazenar mensagens de controle
    public $mensagem_modelo = "";

    public function listar_nacionalidades()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT DISTINCT nacionality from author");
            //$query->bind_param("s", $nome);
            $query->execute();

            $res = $query->get_result();

            $query->close();
            $this->close_db();

            return $res;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    public function listar_autores()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT id, first_name, last_name, photo_url FROM author ORDER BY RAND()");
            //$query->bind_param("s", $nome);
            $query->execute();

            $res = $query->get_result();

            $query->close();
            $this->close_db();

            return $res;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    public function pesquisa_livros($authorname, $nacionalidade)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $comando_base = "
        SELECT DISTINCT
            id, first_name, last_name, photo_url FROM author
        WHERE 1=1"; // Adiciona uma cláusula sempre verdadeira para facilitar a concatenação de cláusulas

            $params = array();
            $tipos = ''; // String para armazenar os tipos dos parâmetros (s = string, i = integer, etc.)

            if (!empty($authorname)) {
                $comando_base .= " AND (first_name LIKE ? OR last_name LIKE ?)";
                $params[] = '%' . $authorname . '%';
                $params[] = '%' . $authorname . '%';
                $tipos .= 'ss'; // Dois parâmetros tipo string (first_name e last_name)
            }

            if (!empty($nacionalidade)) {
                $comando_base .= " AND nacionality = ?";
                $params[] = $nacionalidade;
                $tipos .= 's';
            }

            // Prepare a declaração final
            $stmt = $this->condb->prepare($comando_base);
            if (!$stmt) {
                throw new Exception("Erro na preparação da consulta SQL: " . $this->condb->error);
            }

            // Vincule os parâmetros dinamicamente
            if (!empty($params)) {
                $stmt->bind_param($tipos, ...$params);
            }

            // Execute a consulta
            $stmt->execute();
            $res = $stmt->get_result();

            $stmt->close();
            $this->close_db();

            return $res;
        } catch (Exception $e) {
            $this->close_db();
            throw $e; // Aqui estava com um erro de digitação, corrigido para 'throw $e'
        }
    }
    public function listar_autor_pag($codigo_autor)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                a.id as author_id,
                                                a.first_name,
                                                a.last_name,
                                                a.nacionality,
                                                a.photo_url,
                                                a.description,
                                                a.birth_date,
                                                a.death_date,
                                                a.personal_site,
                                                a.wiki_page,
                                                a.facebook_link,
                                                a.twitter_link,
                                                a.instagram_link,
                                                a.reddit_link,
                                                a.tiktok_link,
                                                tt.field_pt,
                                                tt.field_eng
                                            FROM 
                                                author a
                                            LEFT JOIN 
                                                translation_table tt ON a.description = tt.id
                                            WHERE 
                                                a.id = ?");
            $query->bind_param("i", $codigo_autor);
            $query->execute();

            $res = $query->get_result();

            $query->close();
            $this->close_db();

            return $res;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    public function listar_autor_work($codigo_autor)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                b.id AS book_id,
                                                b.title,
                                                b.internal_code,
                                                b.fcover_url
                                            FROM 
                                                author a
                                            LEFT JOIN 
                                                author_book ab ON a.id = ab.author_id
                                            LEFT JOIN 
                                                books b ON ab.book_id = b.id
                                            WHERE 
                                                a.id = ?;");
            $query->bind_param("i", $codigo_autor);
            $query->execute();

            $res = $query->get_result();

            $query->close();
            $this->close_db();

            return $res;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function adicionar_autor($p_prinome, $p_ultnome, $p_datanasc, $p_datamorte, $p_nacionalidade, $p_websitepessoal, $p_wiki, $p_facebook, $p_twitter, $p_instagram, $p_reddit, $p_tiktok, $p_desc_pt, $p_desc_eng, $p_photo_url)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("CALL adicionar_autor(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $query->bind_param("sssssssssssssss", $p_prinome,$p_ultnome,$p_nacionalidade,$p_photo_url,$p_desc_pt,$p_desc_eng,$p_datanasc,$p_datamorte,$p_websitepessoal,$p_wiki,$p_facebook,$p_twitter,$p_instagram,$p_reddit,$p_tiktok);
            $result = $query->execute();
            $query->close();
            $this->close_db();
            return $result;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $bd_erro;
        }
    }
    function apagar_autor($autor_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("CALL apagar_autor(?);");
            $query->bind_param("i", $autor_id);
            $result = $query->execute();
            $query->close();
            $this->close_db();
            return $result;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $bd_erro;
        }
    }
    function atualizar_autor($autor_id, $p_prinome, $p_ultnome, $p_datanasc, $p_datamorte, $p_nacionalidade, $p_websitepessoal, $p_wiki, $p_facebook, $p_twitter, $p_instagram, $p_reddit, $p_tiktok, $p_desc_pt, $p_desc_eng, $p_photo_url)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("CALL atualizar_autor(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $query->bind_param("isssssssssssssss",$autor_id, $p_prinome,$p_ultnome,$p_nacionalidade,$p_photo_url,$p_desc_pt,$p_desc_eng,$p_datanasc,$p_datamorte,$p_websitepessoal,$p_wiki,$p_facebook,$p_twitter,$p_instagram,$p_reddit,$p_tiktok);
            $result = $query->execute();
            $query->close();
            $this->close_db();
            return $result;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $bd_erro;
        }
    }
}
