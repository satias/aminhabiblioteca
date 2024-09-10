<?php
require_once 'connection.php';
$dicionario = $rootDirectory . '/setlanguage.php';
include_once $dicionario;
// Definição da classe 'modelo' que herda de 'coneccao'
class modelo extends coneccao
{
    // Propriedade para armazenar mensagens de controle
    public $mensagem_modelo = "";
    function contador_livros()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT COUNT(*) AS count_books
                                            FROM books WHERE deleted = 0;");
            //$query->bind_param("i", $user_id);
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc(); // Obtemos apenas uma linha

            $query->close();
            $this->close_db();
            $nlivros = $row['count_books'];
            return $nlivros;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function contador_autores()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT COUNT(*) AS count_authors
                                            FROM author;");
            //$query->bind_param("i", $user_id); // "i" indica que $user_id é do tipo integer
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc(); // Obtemos apenas uma linha

            $query->close();
            $this->close_db();
            $nautores = $row['count_authors'];
            return $nautores;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    public function novos_livros_home()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT title,internal_code,fcover_url FROM books WHERE deleted = 0 ORDER BY created_at ASC LIMIT 3");
            //$query->bind_param("s", $user_id);
            $query->execute();

            // Obtém o resultado da consulta
            $res = $query->get_result();

            // Fecha a consulta e a conexão com a base de dados
            $query->close();
            $this->close_db();

            // Retorna o resultado da consulta
            return $res;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $bd_erro;
        }
    }
    public function livros_populares_home()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT * FROM popular_books");
            //$query->bind_param("s", $user_id);
            $query->execute();

            // Obtém o resultado da consulta
            $res = $query->get_result();

            // Fecha a consulta e a conexão com a base de dados
            $query->close();
            $this->close_db();

            // Retorna o resultado da consulta
            return $res;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $bd_erro;
        }
    }
    public function categorias_populares_home()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT * FROM popular_genres");
            //$query->bind_param("s", $user_id);
            $query->execute();

            // Obtém o resultado da consulta
            $res = $query->get_result();

            // Fecha a consulta e a conexão com a base de dados
            $query->close();
            $this->close_db();

            // Retorna o resultado da consulta
            return $res;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $bd_erro;
        }
    }
    public function pesquisa_rapida_livro($nome)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("
            SELECT 
                b.id AS id,
                b.title AS name,
                b.fcover_url AS fcover,
                b.bcover_url AS bcover,
                b.internal_code,
                'book' AS type
              FROM 
                books b
              WHERE 
                b.title LIKE CONCAT('%', ?, '%')
                AND
                deleted = 0");
            $query->bind_param("s", $nome);
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
    public function pesquisa_rapida_autor($nome)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("
            SELECT 
                  a.id AS id,
                  CONCAT(a.first_name, ' ', a.last_name) AS name,
                  a.photo_url AS photo,
                  NULL AS fcover,
                  NULL AS bcover,
                  'author' AS type
                FROM 
                  author a
                WHERE 
                  CONCAT(a.first_name, ' ', a.last_name) LIKE CONCAT('%', ?, '%')
                  ");
            $query->bind_param("s", $nome);
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
    public function listar_linguagens()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT DISTINCT language from books WHERE deleted = 0");
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
    public function listar_editoras()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT DISTINCT publisher from books WHERE deleted = 0");
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
    public function listar_generos()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("
            SELECT 
                g.id AS genre_id,
                tt.field_pt AS genre_pt,
                tt.field_eng AS genre_eng
            FROM 
                genres g
            JOIN 
                translation_table tt ON g.name = tt.id
            JOIN 
                translation_fields_name tfn ON tt.field_name_id = tfn.id
            WHERE 
                tfn.id = 5;

            ");
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
    public function listar_livros()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT * FROM books WHERE deleted = 0 ORDER by rand()");
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
    public function pesquisa_livros($bookname, $authorname, $genero, $linguagem, $editora, $disponibilidade)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $comando_base = "
        SELECT DISTINCT
            b.id,
            b.title,
            b.internal_code,
            b.fcover_url
        FROM
            books b
        LEFT JOIN
            author_book ab ON b.id = ab.book_id
        LEFT JOIN
            author a ON ab.author_id = a.id
        LEFT JOIN
            book_genres bg ON b.id = bg.book_id
        LEFT JOIN
            genres g ON bg.genre_id = g.id
        WHERE 1=1
        AND b.deleted = 0"; // Adiciona uma cláusula sempre verdadeira para facilitar a concatenação de cláusulas

            $params = array();
            $tipos = ''; // String para armazenar os tipos dos parâmetros (s = string, i = integer, etc.)

            if (!empty($bookname)) {
                $comando_base .= " AND b.title LIKE ?";
                $params[] = '%' . $bookname . '%';
                $tipos .= 's';
            }

            if (!empty($authorname)) {
                $comando_base .= " AND (a.first_name LIKE ? OR a.last_name LIKE ?)";
                $params[] = '%' . $authorname . '%';
                $params[] = '%' . $authorname . '%';
                $tipos .= 'ss'; // Dois parâmetros tipo string (first_name e last_name)
            }

            if (!empty($linguagem)) {
                $comando_base .= " AND b.language = ?";
                $params[] = $linguagem;
                $tipos .= 's';
            }

            if (!empty($editora)) {
                $comando_base .= " AND b.publisher = ?";
                $params[] = $editora;
                $tipos .= 's';
            }

            if (!empty($genero)) {
                $comando_base .= " AND g.id = ?";
                $params[] = $genero;
                $tipos .= 'i';
            }

            if ($disponibilidade !== null) {
                if ($disponibilidade == 0 || $disponibilidade == 1) {
                    $comando_base .= " AND b.available = ? AND b.available_req = 1";
                    $params[] = $disponibilidade;
                    $tipos .= 'i';
                } elseif ($disponibilidade == 10) {
                    $comando_base .= " AND b.available_req = 0";
                }
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
    public function listar_livro_pag($codigo_livro)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                b.id as book_id,
                                                b.title,
                                                b.internal_code,
                                                b.fcover_url,
                                                b.bcover_url,
                                                b.available,
                                                b.physical_condition,
                                                b.release_date,
                                                b.available_req,
                                                b.language,
                                                b.publisher,
                                                b.isbn,
                                                b.page_number,
                                                b.edition_number,
                                                a.id AS author_id,
                                                a.first_name,
                                                a.last_name,
                                                a.photo_url,
                                                tt.field_pt,
                                                tt.field_eng
                                            FROM 
                                                books b
                                            LEFT JOIN 
                                                author_book ab ON b.id = ab.book_id
                                            LEFT JOIN 
                                                author a ON ab.author_id = a.id
                                            LEFT JOIN 
                                                translation_table tt ON b.description = tt.id
                                            WHERE 
                                                b.internal_code = ?
                                            AND 
                                                b.deleted = 0");
            $query->bind_param("i", $codigo_livro);
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
    public function listar_livro_generos($codigo_livro)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                g.id AS genre_id,
                                                tt.field_pt,
                                                tt.field_eng
                                            FROM 
                                                book_genres bg
                                            JOIN 
                                                genres g ON bg.genre_id = g.id
                                            JOIN 
                                                translation_table tt ON g.name = tt.id
                                            WHERE 
                                                bg.book_id = ? AND
                                                tt.field_name_id = 5;");
            $query->bind_param("i", $codigo_livro);
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
    public function adicionar_favorito($user_id, $book_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("INSERT INTO favorite_books VALUES (?, ?);");
            $query->bind_param("ii", $book_id, $user_id);
            $query->execute();

            //$query->get_result();

            $query->close();
            $this->close_db();

            return true;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    public function verificar_favorito($user_id, $book_id)
    {
        try {
            $this->open_db();

            $query = $this->condb->prepare("SELECT * FROM favorite_books WHERE book_id = ? AND user_id = ?");
            $query->bind_param("ii", $book_id, $user_id);
            $query->execute();

            $res = $query->get_result();
            $num_rows = $res->num_rows;

            $query->close();
            $this->close_db();

            return $num_rows > 0; // Retorna true se houver pelo menos uma linha (livro é favorito), false caso contrário
        } catch (Exception $e) {
            $this->close_db();
            return false; // Retorna false em caso de erro
        }
    }
    public function retirar_favorito($user_id, $book_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("DELETE from favorite_books where book_id = ? and user_id = ?;");
            $query->bind_param("ii", $book_id, $user_id);
            $query->execute();

            //$query->get_result();

            $query->close();
            $this->close_db();

            return true;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function contador_requisicoes($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT COUNT(*) AS count_requests
                                            FROM requests where user_id = ? AND status = 1;");
            $query->bind_param("i", $user_id); // "i" indica que $user_id é do tipo integer
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc(); // Obtemos apenas uma linha

            $query->close();
            $this->close_db();
            $nautores = $row['count_requests'];
            return $nautores;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function verifica_livro_requisitado($book_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT user_id as req_user_id FROM requests where book_id = ? AND status = 1;");
            $query->bind_param("i", $book_id); // "i" indica que $user_id é do tipo integer
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
    function contador_reservas($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT COUNT(*) AS count_reserves
                                            FROM reserves where user_id = ?;");
            $query->bind_param("i", $user_id); // "i" indica que $user_id é do tipo integer
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc(); // Obtemos apenas uma linha

            $query->close();
            $this->close_db();
            $nreservas = $row['count_reserves'];
            return $nreservas;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function contador_reservas_do_livro($book_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT COUNT(*) AS count_reserves_book
                                            FROM reserves where book_id = ?;");
            $query->bind_param("i", $book_id); // "i" indica que $user_id é do tipo integer
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc(); // Obtemos apenas uma linha

            $query->close();
            $this->close_db();
            $nreservas = $row['count_reserves_book'];
            return $nreservas;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function verifica_livro_reservado($book_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT user_id as res_user_id FROM reserves where book_id = ? ;");
            $query->bind_param("i", $book_id); // "i" indica que $user_id é do tipo integer
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
    public function requisitar_livro($user_id, $book_id, $start_at)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("INSERT INTO requests (user_id, book_id,status,start_at) VALUES (?, ?,1,?);");
            $query->bind_param("iis", $user_id, $book_id, $start_at);
            $query->execute();

            //$query->get_result();

            $query->close();
            $this->close_db();

            return true;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    public function reservar_livro($user_id, $book_id, $queue_num)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("INSERT INTO reserves (user_id, book_id,queue_num) VALUES (?, ?,?);");
            $query->bind_param("iii", $user_id, $book_id, $queue_num);
            $query->execute();

            //$query->get_result();

            $query->close();
            $this->close_db();

            return true;
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function adicionar_livro($p_titulo, $p_linguagem, $p_codinter, $p_editora, $p_datalanc, $p_isbn, $p_numedit, $p_numpag, $p_condicao, $p_discbiblio, $p_disreq, $p_desc_pt, $p_desc_eng, $p_photo_url_capa, $p_photo_url_contracapa, $p_generos, $p_autor_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("CALL adicionar_livro(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $query->bind_param(
                "sssissiisissiiisi",
                $p_titulo,          // p_title
                $p_desc_pt,         // p_description_pt
                $p_desc_eng,        // p_description_eng
                $p_codinter,        // p_internal_code
                $p_photo_url_capa,  // p_fcover_url
                $p_photo_url_contracapa, // p_bcover_url
                $p_discbiblio,      // p_available
                $p_condicao,        // p_physical_condition
                $p_datalanc,        // p_release_date
                $p_disreq,          // p_available_req
                $p_linguagem,       // p_language
                $p_editora,         // p_publisher
                $p_isbn,            // p_isbn
                $p_numpag,          // p_page_number
                $p_numedit,         // p_edition_number
                $p_generos,
                $p_autor_id
            );
            $result = $query->execute();
            $query->close();
            $this->close_db();
            return $result;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $e;
        }
    }
    function apagar_livro($livro_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            // Preparar a chamada do procedimento com um parâmetro de saída
            $query = $this->condb->prepare("CALL apagar_livro(?, @success);");
            $query->bind_param("i", $livro_id);
            $result = $query->execute();
            $query->close();

            // Recuperar o valor de @success
            $query = $this->condb->query("SELECT @success AS success;");
            $row = $query->fetch_assoc();
            $success = $row['success'];
            $query->close();

            $this->close_db();

            // Retornar false se o procedimento não passar dos 3 IFs
            if ($success == 0) {
                return false;
            }

            return $result;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $bd_erro;
        }
    }
    function atualizar_livro($p_book_id, $p_titulo, $p_linguagem, $p_codinter, $p_editora, $p_datalanc, $p_isbn, $p_numedit, $p_numpag, $p_condicao, $p_discbiblio, $p_disreq, $p_desc_pt, $p_desc_eng, $p_photo_url_capa, $p_photo_url_contracapa, $p_generos, $p_autor_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("CALL atualizar_livro(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $query->bind_param(
                "isssissiisissiiisi",
                $p_book_id,
                $p_titulo,          // p_title
                $p_desc_pt,         // p_description_pt
                $p_desc_eng,        // p_description_eng
                $p_codinter,        // p_internal_code
                $p_photo_url_capa,  // p_fcover_url
                $p_photo_url_contracapa, // p_bcover_url
                $p_discbiblio,      // p_available
                $p_condicao,        // p_physical_condition
                $p_datalanc,        // p_release_date
                $p_disreq,          // p_available_req
                $p_linguagem,       // p_language
                $p_editora,         // p_publisher
                $p_isbn,            // p_isbn
                $p_numpag,          // p_page_number
                $p_numedit,         // p_edition_number
                $p_generos,
                $p_autor_id
            );
            $result = $query->execute();
            $query->close();
            $this->close_db();
            return $result;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $e;
        }
    }
    public function listar_internalcode_isbn()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT id,internal_code,isbn FROM books");
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
}
