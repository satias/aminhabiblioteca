<?php
require_once 'connection.php'; // Inclui o ficheiro de conexão à base de dados
$dicionario = $rootDirectory . '/setlanguage.php'; // Define o caminho para o ficheiro de linguagem
include_once $dicionario; // Inclui o ficheiro de linguagem
// Definição da classe 'user_modelo' que herda de 'coneccao'
class user_modelo extends coneccao
{
    // Propriedade para armazenar mensagens de controle
    public $mensagem_modelo = "";

    // Método para verificar se um utilizador já existe na base de dados
    function verificar_user($username, $email)
    {
        try {
            // Abre a conexão com a base de dados
            $this->open_db();

            // Prepara a consulta SQL para selecionar utilizadors com o mesmo nome de utilizador ou email
            $query = $this->condb->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $query->bind_param("ss", $username, $email);
            $query->execute();
            $query->store_result();

            // Obtém o número de linhas retornadas pela consulta
            $count = $query->num_rows;

            // Fecha a consulta e a conexão com a base de dados
            $query->close();
            $this->close_db();

            // Retorna true se o utilizador existir, caso contrário, retorna false
            return $count > 0;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e lança a exceção
            $this->close_db();
            throw $e;
        }
    }

    // Método para registar um novo utilizador na base de dados
    public function registo($email, $username, $encript_pass, $data)
    {
        global $bd_erro;
        try {
            $this->open_db();

            // Prepara a consulta SQL para inserir um novo utilizador
            $query = $this->condb->prepare("INSERT INTO users (email, username, password, created_at, user_type_id, status) VALUES (?, ?, ?, ?, 3, 1)");
            $query->bind_param("ssss", $email, $username, $encript_pass, $data);

            // Executa a consulta
            $result = $query->execute();

            // Fecha a consulta e a conexão com a base de dados
            $query->close();
            $this->close_db();

            // Verifica se a notificação para o novo utilizador pode ser enviada
            $notif = $this->new_user_notif($email, $username, $data);

            // Se a notificação foi adicionada com sucesso, retorna o resultado da inserção do utilizador
            // Caso contrário, exclui o utilizador recém-inserido e retorna false
            if ($notif) {
                return $result;
            } else {
                $this->delete_user($email, $username, $data);
                $this->mensagem_modelo = $bd_erro;
                return false;
            }
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão, define a mensagem de erro e retorna false
            $this->close_db();
            $this->mensagem_modelo = $bd_erro;
            return false;
        }
    }

    // Método para obter o ID do novo utilizador inserido
    public function get_new_user_id($email, $username, $data)
    {
        global $bd_erro;
        try {
            $this->open_db();

            // Prepara a consulta SQL para selecionar o ID do novo utilizador
            $query = $this->condb->prepare("SELECT id FROM users WHERE email = ? AND username = ? AND created_at = ?");
            $query->bind_param("sss", $email, $username, $data);
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

    // Método para enviar uma notificação para o novo utilizador registrado
    public function new_user_notif($email, $username, $data)
    {
        global $bd_erro;
        // Obtém o novo utilizador
        $new_user = $this->get_new_user_id($email, $username, $data);

        // Verifica se o resultado é uma instância de mysqli_result
        if ($new_user instanceof mysqli_result) {
            $row = $new_user->fetch_assoc();

            // Se existir um novo utilizador
            if ($row) {
                $user_id = $row['id'];
                try {
                    $this->open_db();

                    // Prepara a consulta SQL para inserir uma nova notificação para o utilizador
                    $query = $this->condb->prepare("INSERT INTO notifications (type_id, user_id, title, description, status, created_at) VALUES (1, ?, 1, 2, 1, ?);");
                    $query->bind_param("is", $user_id, $data);

                    // Executa a consulta
                    $result = $query->execute();

                    // Fecha a consulta e a conexão com a base de dados
                    $query->close();
                    $this->close_db();

                    // Retorna o resultado da execução da consulta
                    return $result;
                } catch (Exception $e) {
                    // Em caso de erro, fecha a conexão e retorna false
                    $this->close_db();
                    return false;
                }
            } else {
                // Se não houver um novo utilizador, retorna a mensagem de erro
                return $bd_erro;
            }
        } else {
            // Se não for possível obter o novo utilizador, retorna a mensagem de erro
            return $bd_erro;
        }
    }

    // Método privado para excluir um utilizador da base de dados
    private function delete_user($email, $username, $data)
    {
        global $bd_erro;
        try {
            $this->open_db();

            // Prepara a consulta SQL para excluir o utilizador
            $query = $this->condb->prepare("DELETE FROM users WHERE email = ? AND username = ? AND created_at = ?");
            $query->bind_param("sss", $email, $username, $data);

            // Executa a consulta
            $result = $query->execute();

            // Fecha a consulta e a conexão com a base de dados
            $query->close();
            $this->close_db();

            // Retorna o resultado da execução da consulta
            return $result;
        } catch (Exception $e) {
            // Em caso de erro, fecha a conexão e retorna a mensagem de erro
            $this->close_db();
            return $bd_erro;
        }
    }
    function verificar_credenciais($username)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT id,user_type_id,status,photo_url FROM users WHERE username = ?");
            $query->bind_param("s", $username);
            $query->execute();
            $result = $query->get_result();
            $query->close();
            $this->close_db();
            return $result; // Retorna o resultado da consulta
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function obter_hash_senha($username)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT password FROM users WHERE username = ?");
            $query->bind_param("s", $username);
            $query->execute();
            $result = $query->get_result();
            $query->close();
            $this->close_db();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['password']; // Retorna a senha hash
            } else {
                // Usuário não encontrado
                return null;
            }
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function noticacoes_pt($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("
            SELECT 
                tt_title.field_pt AS title,
                tt_description.field_pt AS description,
                created_at
            FROM 
                notifications n
            JOIN 
                translation_table tt_title ON n.title = tt_title.id
            JOIN 
                translation_table tt_description ON n.description = tt_description.id
            WHERE 
                n.status = 1 AND 
                n.user_id = ?
            ORDER BY 
                n.created_at DESC;
            ");
            $query->bind_param("s", $user_id);
            $query->execute();
            $result = $query->get_result();
            $query->close();
            $this->close_db();
            return $result; // Retorna o resultado da consulta
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function noticacoes_eng($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("
            SELECT 
                tt_title.field_eng AS title,
                tt_description.field_eng AS description,
                created_at
            FROM 
                notifications n
            JOIN 
                translation_table tt_title ON n.title = tt_title.id
            JOIN 
                translation_table tt_description ON n.description = tt_description.id
            WHERE 
                n.status = 1 AND 
                n.user_id = ?
            ORDER BY 
                n.created_at DESC;
            ");
            $query->bind_param("s", $user_id);
            $query->execute();
            $result = $query->get_result();
            $query->close();
            $this->close_db();
            return $result; // Retorna o resultado da consulta
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }

    public function get_user_detalhes($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            // Prepara a consulta SQL para selecionar os detalhes do utilizador pelo ID
            $query = $this->condb->prepare("SELECT id,user_type_id,first_name,last_name,username, email,photo_url, address, postal_code, created_at,updated_at,status,number,status_del FROM users WHERE id = ?");
            $query->bind_param("s", $user_id);
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
    function update_user($user_id, $first_name, $last_name, $email, $photo_url, $address, $postal_code, $number)
    {
        global $bd_erro;
        try {
            $this->open_db();

            // Prepara a consulta SQL para atualizadar as informaçoes do utilizador
            $query = $this->condb->prepare("UPDATE users
                SET 
                    first_name = ?, 
                    last_name = ?, 
                    email = ?, 
                    photo_url = ?, 
                    address = ?, 
                    postal_code = ?, 
                    number = ?, 
                    updated_at = NOW() 
                WHERE id = ?");
            $query->bind_param("ssssssss", $first_name, $last_name, $email, $photo_url, $address, $postal_code, $number, $user_id);
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
    function verificar_email($email)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT email FROM users WHERE email = ?");
            $query->bind_param("s", $email);
            $query->execute();
            $result = $query->get_result();
            $query->close();
            $this->close_db();

            if ($result->num_rows == 0) {
                //email não encontrado
                return false;
            } else {
                // email encontrado
                return true;
            }
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function verificar_reservas_por_user_id($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT COUNT(*) AS count_reserves FROM reserves WHERE user_id = ?");
            $query->bind_param("i", $user_id); // "i" indica que $user_id é do tipo integer
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc(); // Obtemos apenas uma linha

            $query->close();
            $this->close_db();

            // Verifica o número de reservas encontradas para o user_id
            if ($row['count_reserves'] == 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function verificar_requests_por_user_id($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT COUNT(*) AS count_requests FROM requests WHERE user_id = ? AND status = 1");
            $query->bind_param("i", $user_id); // "i" indica que $user_id é do tipo integer
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc(); // Obtemos apenas uma linha

            $query->close();
            $this->close_db();

            // Verifica o número de requesições encontradas para o user_id
            if ($row['count_requests'] == 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function verificar_multas_por_user_id($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT COUNT(*) AS count_fines
                                            FROM fines f
                                            INNER JOIN requests r ON f.request_id = r.id
                                            WHERE r.user_id = ?
                                            AND f.status = 1;");
            $query->bind_param("i", $user_id); // "i" indica que $user_id é do tipo integer
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc(); // Obtemos apenas uma linha

            $query->close();
            $this->close_db();

            // Verifica o número de multas encontradas para o user_id
            if ($row['count_fines'] == 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->close_db();
            return $bd_erro;
        }
    }
    function pedir_apagar_user($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            // Prepara a consulta SQL para atualizadar as informaçoes do utilizador
            $query = $this->condb->prepare("UPDATE users
                SET 
                    status_del = 1,
                    updated_at = NOW() 
                WHERE id = ?");
            $query->bind_param("s", $user_id);
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
    function atualizar_apagar_user($username)
    {
        global $bd_erro;
        try {
            $this->open_db();
            // Prepara a consulta SQL para atualizadar as informaçoes do utilizador
            $query = $this->condb->prepare("UPDATE users
                SET 
                    status_del = 0 
                WHERE username = ?");
            $query->bind_param("s", $username);
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
    public function listar_requisicoes_livrosautores($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            // Prepara a consulta SQL para selecionar os detalhes do utilizador pelo ID
            $query = $this->condb->prepare("CALL Listar_Requisicoes_LivrosAutores(?);");
            $query->bind_param("i", $user_id);
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
    // Método para verificar se um username já existe na base de dados
    function verificar_username($username)
    {
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT * FROM users WHERE username = ?");
            $query->bind_param("s", $username);
            $query->execute();
            $query->store_result();
            $count = $query->num_rows;
            $query->close();
            $this->close_db();
            return $count > 0;
        } catch (Exception $e) {
            $this->close_db();
            throw $e;
        }
    }
    function atualizar_username($username, $user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            // Prepara a consulta SQL para atualizadar as informaçoes do utilizador
            $query = $this->condb->prepare("UPDATE users
                SET 
                    username = ?,
                    updated_at =  NOW()
                WHERE id = ?");
            $query->bind_param("si", $username, $user_id);
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
    function atualizar_password($password, $user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            // Prepara a consulta SQL para atualizadar as informaçoes do utilizador
            $query = $this->condb->prepare("UPDATE users
                SET 
                    password = ?,
                    updated_at =  NOW()
                WHERE id = ?");
            $query->bind_param("si", $password, $user_id);
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
    public function listar_favoritos($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                            b.id as book_id,
                                            b.title,
                                            b.internal_code,
                                            b.fcover_url,
                                            b.language,
                                            b.publisher,
                                            b.edition_number,
                                            a.first_name,
                                            a.last_name
                                        FROM 
                                            books b
                                        LEFT JOIN 
                                            author_book ab ON b.id = ab.book_id
                                        LEFT JOIN 
                                            author a ON ab.author_id = a.id
                                        INNER JOIN 
                                            favorite_books fb ON b.id = fb.book_id
                                        WHERE 
                                            fb.user_id = ?");
            $query->bind_param("i", $user_id);
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
    public function listar_multas($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                f.id,
                                                f.amount,
                                                f.status,
                                                f.start_at,
                                                f.payment_date,
                                                f.request_id,
                                                r.status as request_status,
                                                b.fcover_url,
                                                b.title
                                            FROM 
                                                fines f
                                            INNER JOIN 
                                                requests r ON f.request_id = r.id
                                            INNER JOIN 
                                                books b ON r.book_id = b.id
                                            WHERE 
                                                r.user_id = ?;
                                            ");
            $query->bind_param("i", $user_id);
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
    public function listar_ticket_types()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                tt.id as type_id,
                                                trt.field_pt,
                                                trt.field_eng
                                            FROM 
                                                ticket_types tt
                                            INNER JOIN 
                                                translation_table trt ON trt.id = tt.type_name
                                            WHERE 
                                                trt.field_name_id = 6;
                                            ");
            //$query->bind_param("i", $user_id);
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
    public function listar_tickets($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                t.id as ticket_id,
                                                t.title,
                                                t.description,
                                                t.created_at,
                                                t.status,
                                                trt.field_pt,
                                                trt.field_eng
                                            FROM 
                                                tickets t
                                            INNER JOIN 
                                                ticket_types tt ON t.type_id = tt.id
                                            INNER JOIN 
                                                translation_table trt ON tt.type_name = trt.id
                                            WHERE 
                                                t.user_id = ?
                                            ORDER BY 
                                                created_at DESC;
                                            ");
            $query->bind_param("i", $user_id);
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
    public function listar_ticket_page($user_id, $ticket_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                t.id as ticket_id,
                                                t.title,
                                                t.description,
                                                t.created_at,
                                                t.status,
                                                trt.field_pt,
                                                trt.field_eng,
                                                u.first_name,
                                                u.last_name
                                            FROM 
                                                tickets t
                                            INNER JOIN 
                                                ticket_types tt ON t.type_id = tt.id
                                            INNER JOIN 
                                                translation_table trt ON tt.type_name = trt.id
                                            INNER JOIN
                                                users u on t.user_id = u.id
                                            WHERE 
                                                t.user_id = ?
                                            AND
                                                t.id = ?;
                                            ");
            $query->bind_param("ii", $user_id, $ticket_id);
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
    public function inserir_ticket($user_id, $tipo, $titulo, $descricao)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("INSERT INTO tickets (user_id,type_id,title,description,created_at,status) VALUES (?, ?,?,?,NOW(),1);");
            $query->bind_param("iiss", $user_id, $tipo, $titulo, $descricao);
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
    public function inserir_ticket_resposta($user_id, $ticket_id, $resposta)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("INSERT INTO ticket_replies (ticket_id,user_id,response,replied_at) VALUES (?, ?,?,NOW());");
            $query->bind_param("iis", $ticket_id, $user_id, $resposta);
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
    function fechar_ticket($user_id, $ticket_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            // Prepara a consulta SQL para atualizadar as informaçoes do utilizador
            $query = $this->condb->prepare("UPDATE 
                                                    tickets
                                                SET 
                                                    status = 0 
                                                WHERE 
                                                    user_id = ?
                                                AND
                                                    id = ?
                                                ");
            $query->bind_param("ii", $user_id, $ticket_id);
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
    public function listar_ticket_respostas($ticket_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                tr.response, 
                                                tr.replied_at, 
                                                u.first_name, 
                                                u.last_name
                                            FROM 
                                                ticket_replies tr
                                            INNER JOIN 
                                                users u ON tr.user_id = u.id
                                            WHERE 
                                                tr.ticket_id = ?
                                            ORDER BY 
                                                tr.replied_at ASC;
                                            ");
            $query->bind_param("i", $ticket_id);
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
    public function listar_reservas_user($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                r.id as reserve_id, 
                                                r.prolonged, 
                                                r.queue_num,
                                                b.fcover_url,
                                                b.bcover_url,
                                                b.id as book_id,
                                                b.title,
                                                b.internal_code,
                                                b.edition_number,
                                                b.page_number,
                                                b.publisher,
                                                b.language,
                                                a.id as author_id,
                                                a.first_name,
                                                a.last_name,
                                                re.end_at
                                            FROM 
                                                reserves r
                                            INNER JOIN
                                                books b ON r.book_id = b.id
                                            LEFT JOIN
                                                author_book ab ON b.id = ab.book_id
                                            LEFT JOIN
                                                author a ON ab.author_id = a.id
                                            INNER JOIN
                                            	requests re on b.id = re.book_id
                                            WHERE 
                                                r.user_id = ?
                                            AND
                                            	re.status = 1
                                           	AND
                                            	re.book_id = b.id
                                                ;
                                            ");
            $query->bind_param("i", $user_id);
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
    function remover_reserva($reserva_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("CALL Remover_Reserva(?);");
            $query->bind_param("i", $reserva_id);
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
    function extender_requisicao($requisicao_id, $end_date)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("UPDATE requests
                SET 
                    end_at = ?, 
                    date_extended = 1 
                WHERE id = ?");
            $query->bind_param("si", $end_date, $requisicao_id);
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
    function cancelar_requisicao($requisicao_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("CALL Remover_Requisicao(?)");
            $query->bind_param("i", $requisicao_id);
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
    public function listar_users_status_del()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                id,
                                                username, 
                                                email,
                                                first_name,
                                                last_name,
                                                updated_at
                                            FROM 
                                                users
                                            WHERE 
                                                status_del = 1
                                            ORDER BY
                                                updated_at ASC
                                                ;
                                            ");
            //$query->bind_param("i", $user_id);
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
    public function pesquisar_utilizadores($user)
    {
        global $bd_erro;
        try {
            $this->open_db();

            // Prepara a consulta SQL para selecionar os detalhes do utilizador
            // Verifica se o valor pode corresponder ao id, username, email ou nome completo (first_name + last_name) usando LIKE
            $query = $this->condb->prepare("
            SELECT DISTINCT id, user_type_id, first_name, last_name, username, email, photo_url, address, postal_code, created_at, updated_at, status, number 
            FROM users 
            WHERE id LIKE ? 
            OR username LIKE ? 
            OR email LIKE ? 
            OR CONCAT(first_name, ' ', last_name) LIKE ?");

            // Prepara a string de pesquisa para o operador LIKE
            $search_term = "%" . $user . "%";

            // Liga os parâmetros ao valor pesquisado
            $query->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
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
    function update_user_staff_admin($user_id, $first_name, $last_name, $email, $address, $postal_code, $number)
    {
        global $bd_erro;
        try {
            $this->open_db();

            // Prepara a consulta SQL para atualizadar as informaçoes do utilizador
            $query = $this->condb->prepare("UPDATE users
                SET 
                    first_name = ?, 
                    last_name = ?, 
                    email = ?,
                    address = ?, 
                    postal_code = ?, 
                    number = ?, 
                    updated_at = NOW() 
                WHERE id = ?");
            $query->bind_param("ssssssi", $first_name, $last_name, $email, $address, $postal_code, $number, $user_id);
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
    function bloquear_user($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("UPDATE users
                SET 
                    status = 0 
                WHERE id = ?");
            $query->bind_param("i", $user_id);
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
    function desbloquear_user($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("UPDATE users
                SET 
                    status = 1 
                WHERE id = ?");
            $query->bind_param("i", $user_id);
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
    function pagar_multa_user($multa_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("UPDATE fines
                SET 
                    status = 0,
                    payment_date = NOW() 
                WHERE id = ?");
            $query->bind_param("i", $multa_id);
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
    function entregar_livro($requisicao_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("UPDATE requests
                SET 
                    status = 0,
                    end_at = NOW() 
                WHERE id = ?");
            $query->bind_param("i", $requisicao_id);
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
    function ativar_requisicao($requisicao_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("UPDATE requests
                SET 
                    end_at = NOW() + INTERVAL 7 DAY
                WHERE id = ?");
            $query->bind_param("i", $requisicao_id);
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
    public function listar_todos_tickets()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                t.id as ticket_id,
                                                t.user_id,
                                                t.title,
                                                t.description,
                                                t.created_at,
                                                t.status,
                                                trt.field_pt,
                                                trt.field_eng,
                                                tt.admin_response,
                                                u.first_name,
                                                u.last_name,
                                                u.username,
                                                u.email
                                            FROM 
                                                tickets t
                                            INNER JOIN 
                                                ticket_types tt ON t.type_id = tt.id
                                            INNER JOIN 
                                                translation_table trt ON tt.type_name = trt.id
                                            INNER JOIN
                                                users u ON t.user_id = u.id
                                            ORDER BY 
                                                created_at DESC;
                                            ");
            //$query->bind_param("i", $user_id);
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
    public function pesquisa_ticket($texto, $tipo)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $comando_base = "
                            SELECT DISTINCT
                                t.id as ticket_id,
                                t.user_id,
                                t.title,
                                t.description,
                                t.created_at,
                                t.status,
                                trt.field_pt,
                                trt.field_eng,
                                tt.admin_response,
                                u.first_name,
                                u.last_name,
                                u.username,
                                u.email
                                FROM 
                                    tickets t
                                INNER JOIN 
                                    ticket_types tt ON t.type_id = tt.id
                                INNER JOIN 
                                    translation_table trt ON tt.type_name = trt.id
                                INNER JOIN
                                    users u ON t.user_id = u.id
                                WHERE 
                                    1=1"; // Adiciona uma cláusula sempre verdadeira para facilitar a concatenação de cláusulas

            $params = array();
            $tipos = ''; // String para armazenar os tipos dos parâmetros (s = string, i = integer, etc.)

            if (!empty($texto)) {
                $comando_base .= " AND u.id LIKE ? 
                                    OR u.username LIKE ? 
                                    OR u.email LIKE ? 
                                    OR CONCAT(u.first_name, ' ', u.last_name) LIKE ?";
                $params[] = '%' . $texto . '%';
                $params[] = '%' . $texto . '%';
                $params[] = '%' . $texto . '%';
                $params[] = '%' . $texto . '%';
                $tipos .= 'ssss';
            }

            if (!empty($tipo)) {
                $comando_base .= " AND tt.id = ?";
                $params[] = $tipo;
                $tipos .= 'i';
            }

            // Adiciona a cláusula ORDER BY após WHERE
            $comando_base .= " ORDER BY t.created_at DESC";

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
    function apagar_utlizador_perma($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();

            $query = $this->condb->prepare("CALL apagar_utilizador(?);");
            $query->bind_param("i", $user_id);
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
    function cancelar_apagar_user_id($user_id)
    {
        global $bd_erro;
        try {
            $this->open_db();
            // Prepara a consulta SQL para atualizadar as informaçoes do utilizador
            $query = $this->condb->prepare("UPDATE users
                SET 
                    status_del = 0 
                WHERE id = ?");
            $query->bind_param("i", $user_id);
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
    public function listar_todas_requisicoes()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                r.id AS request_id,
                                                u.id AS user_id,
                                                u.username,
                                                b.title,
                                                b.internal_code,
                                                r.start_at,
                                                r.end_at,
                                                r.review_status,
                                                r.expired
                                            FROM 
                                                requests r
                                            JOIN 
                                                users u ON r.user_id = u.id
                                            JOIN 
                                                books b ON r.book_id = b.id
                                            WHERE 
                                                r.status = 1;
                                            ");
            //$query->bind_param("i", $user_id);
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
    public function listar_todas_multas()
    {
        global $bd_erro;
        try {
            $this->open_db();
            $query = $this->condb->prepare("SELECT 
                                                f.id AS fine_id,
                                                r.id AS request_id,
                                                u.id AS user_id,
                                                u.username,
                                                b.title,
                                                b.internal_code,
                                                f.amount,
                                                f.start_at
                                            FROM 
                                                fines f
                                            JOIN 
                                                requests r ON f.request_id = r.id
                                            JOIN 
                                                users u ON r.user_id = u.id
                                            JOIN 
                                                books b ON r.book_id = b.id
                                            WHERE 
                                                f.status = 1;

                                            ");
            //$query->bind_param("i", $user_id);
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
