<?php
$rootDirectory = dirname(__DIR__);
$modelo = $rootDirectory . '/modelo/user-modelo.php';
$dicionario = $rootDirectory . '/setlanguage.php';
$funcoes = $rootDirectory . '/funcoes/funcoes.php';
require $modelo;
include_once $dicionario;
include_once $funcoes;

class user_controlo
{
    public $mensagem = "";
    public $control;

    function __construct()
    {
        $this->control =  new user_modelo();
    }
    public function registo($email, $username, $password, $confi_pass)
    {
        global $bd_erro;
        if (!empty($email) && !empty($username) && !empty($password) && !empty($confi_pass)) {
            if ($password == $confi_pass) {
                $verificar_user = $this->verificar_user($username, $email);
                if (!$verificar_user) {
                    $encript_pass = password_hash($password, PASSWORD_BCRYPT);
                    date_default_timezone_set('Europe/Lisbon');
                    $data = date('Y-m-d H:i:s');
                    $registrar = $this->control->registo($email, $username, $encript_pass, $data);
                    if ($registrar) {
                        global $conta_criada;
                        $this->mensagem = $conta_criada;
                        return true;
                    } else {
                        $this->mensagem = $this->control->mensagem_modelo;
                        return false;
                    }
                } else {
                    global $utilizador_existe;
                    $this->mensagem = $utilizador_existe;
                    return false;
                }
            } else {
                global $pass_nao_iguais;
                $this->mensagem = $pass_nao_iguais;
                return false;
            }
        } else {
            global $campos_vazios;
            $this->mensagem = $campos_vazios;
            return false;
        }
    }
    function verificar_user($username, $email)
    {
        $query = $this->control->verificar_user($username, $email);
        return $query;
    }
    function atualizar_informacoes($username)
    {
        $resultado = $this->control->verificar_credenciais($username);
        if ($resultado instanceof mysqli_result) {
            if ($resultado->num_rows == 1) {
                $linha = $resultado->fetch_assoc();
                $user_info = [
                    "type" => $linha['user_type_id'],
                    "status" => $linha['status']
                ];
                return [
                    'success' => true,
                    'data' => $user_info
                ];
            } else {
                return [
                    'success' => false,
                    'data' => "error"
                ];
            }
        }
        return [
            'success' => false,
            'data' => "error"
        ];
    }
    function login($username, $password)
    {
        global $login_falhado;
        $senha_hash = $this->control->obter_hash_senha($username);
        if ($senha_hash && password_verify($password, $senha_hash)) {
            $login = $this->control->verificar_credenciais($username);
            if ($login instanceof mysqli_result) {
                if ($login->num_rows == 1) {
                    $linha = $login->fetch_assoc();
                    $this->control->atualizar_apagar_user($username);
                    $user_info = [
                        "id" => $linha['id'],
                        "type" => $linha['user_type_id'],
                        "status" => $linha['status'],
                        "photo_url" => $linha['photo_url'],
                        "username" => $username
                    ];
                    return [
                        'success' => true,
                        'data' => $user_info
                    ];
                } else {
                    // Credenciais inválidas
                    return [
                        'success' => false,
                        'data' => $login_falhado
                    ];
                }
            } else {
                // Tratar erro de consulta
                return [
                    'success' => false,
                    'data' => $login // $login contém o erro
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $login_falhado
            ];
        }
    }
    function notificacao_tab($user_id)
    {
        global $setlang;
        $notificacoes = "";
        switch ($setlang) {
            case 'pt':
                $notificacoes = $this->control->noticacoes_pt($user_id);
                break;
            case 'eng':
                $notificacoes = $this->control->noticacoes_eng($user_id);
                break;
            default:
                $notificacoes = $this->control->noticacoes_pt($user_id);
                break;
        }
        if ($notificacoes instanceof mysqli_result) {
            $notificacoes_conteudo = [];
            while ($linha = $notificacoes->fetch_assoc()) {
                $notificacoes_conteudo[] = [
                    "title" => $linha['title'],
                    "description" => $linha['description'],
                    "created_at" => $linha['created_at']
                ];
            }
            return [
                'success' => true,
                'data' => $notificacoes_conteudo
            ];
        } else {
            return [
                'success' => false,
                'data' => $notificacoes
            ];
        }
    }
    function get_user_detalhes($user_id)
    {
        $detalhes_slq = $this->control->get_user_detalhes($user_id);

        if ($detalhes_slq instanceof mysqli_result) {
            $utilizador_detalhes = [];
            while ($linha = $detalhes_slq->fetch_assoc()) {
                $form_created_at = date("d-m-Y", strtotime($linha['created_at']));
                $form_updated_at = "";
                if (!empty($linha['updated_at'])) {
                    $form_updated_at = date("d-m-Y H:i:s", strtotime($linha['updated_at']));
                }
                $utilizador_detalhes = [
                    "id" => $linha['id'],
                    "first_name" => $linha['first_name'],
                    "last_name"    => $linha['last_name'],
                    "username" => $linha['username'],
                    "email" => $linha['email'],
                    "photo_url" => $linha['photo_url'],
                    "address" => $linha['address'],
                    "postal_code" => $linha['postal_code'],
                    "created_at" => $form_created_at,
                    "updated_at" => $form_updated_at,
                    "status" => $linha['status'],
                    "number" => $linha['number'],
                    "user_type" => $linha['user_type_id'],
                    "status_del" => $linha['status_del']
                ];
            }
            return [
                'success' => true,
                'data' => $utilizador_detalhes
            ];
        } else {
            return [
                'success' => false,
                'data' => $detalhes_slq
            ];
        }
    }
    public function update_user($user_id, $first_name, $last_name, $email, $oldemail, $photo_url, $address, $postal_code, $number)
    {
        global $emailexiste, $numeroinvalido, $codigopostalinvalido, $campos_vazios;
        if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($address) && !empty($postal_code) && !empty($number)) {
            if (preg_match('/^9\d{8}$/', $number)) {
                if (preg_match('/^\d{4}-\d{3}$/', $postal_code)) {
                    $verifica_email = $this->control->verificar_email($email);
                    if (!$verifica_email || $email == $oldemail) {
                        global $perfilup;
                        $photo = basename($photo_url);
                        $update_user = $this->control->update_user($user_id, $first_name, $last_name, $email, $photo, $address, $postal_code, $number);
                        if ($update_user === true) {
                            return [
                                'success' => true,
                                'data' => $perfilup
                            ];
                        } else {
                            return [
                                'success' => false,
                                'data' => $update_user
                            ];
                        }
                    } else {
                        return [
                            'success' => false,
                            'data' => $emailexiste
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'data' => $codigopostalinvalido
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'data' => $numeroinvalido
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $campos_vazios
            ];
        }
    }
    public function pedir_apagar_user($user_id, $password, $username)
    {
        global $login_falhado, $naopossvielapagar, $mensagemapagarsucessocorpo;
        $senha_hash = $this->control->obter_hash_senha($username);
        if ($senha_hash && password_verify($password, $senha_hash)) {
            $reservas_check = $this->control->verificar_reservas_por_user_id($user_id);
            $requests_check = $this->control->verificar_requests_por_user_id($user_id);
            $multas_check = $this->control->verificar_multas_por_user_id($user_id);
            if ($reservas_check == true && $requests_check == true && $multas_check == true) {
                $apagar = $this->control->pedir_apagar_user($user_id);
                if ($apagar === true) {
                    return [
                        'success' => true,
                        'data' => $mensagemapagarsucessocorpo
                    ];
                } else {
                    return [
                        'success' => false,
                        'data' => $apagar
                    ];
                }
            }
            return [
                'success' => false,
                'data' => $naopossvielapagar
            ];
        } else {
            return [
                'success' => false,
                'data' => $login_falhado
            ];
        }
    }
    public function listar_requisicoes_livrosautores($user_id)
    {
        $requisiçoes =  $this->control->listar_requisicoes_livrosautores($user_id);
        if ($requisiçoes instanceof mysqli_result) {
            $lista_requisiçoes = [];
            while ($linha = $requisiçoes->fetch_assoc()) {
                $req_start_at = null;
                if (!empty($linha['start_at'])) {
                    $req_start_at = date("d-m-Y", strtotime($linha['start_at']));
                }
                $req_end_date = null;
                if (!empty($linha['end_at'])) {
                    $req_end_date = date("d-m-Y", strtotime($linha['end_at']));
                } else {
                    $req_end_date = "--/--/----";
                }
                $lista_requisiçoes[] = [
                    "id" => $linha['request_id'],
                    "status" => $linha['status'],
                    "start_date" => $req_start_at,
                    "end_date" => $req_end_date,
                    "expired" => $linha['expired'],
                    "date_extended" => $linha['date_extended'],
                    "title" => $linha['title'],
                    "internal_code" => $linha['internal_code'],
                    "fcover_url" => $linha['fcover_url'],
                    "bcover_url" => $linha['bcover_url'],
                    "language" => $linha['language'],
                    "publisher" => $linha['publisher'],
                    "page_number" => $linha['page_number'],
                    "author_id" => $linha['author_id'],
                    "first_name" => $linha['first_name'],
                    "last_name" => $linha['last_name'],
                ];
            }
            return $lista_requisiçoes;
        }
    }
    public function atualizar_username($user_id, $password, $username, $username_antigo)
    {
        global $login_falhado, $campos_vazios, $perfilup;
        if (!empty($username) && !empty($password)) {
            $senha_hash = $this->control->obter_hash_senha($username_antigo);
            if ($senha_hash && password_verify($password, $senha_hash)) {
                $atualizar = $this->control->atualizar_username($username, $user_id);
                if ($atualizar === true) {
                    return [
                        'success' => true,
                        'data' => $perfilup
                    ];
                } else {
                    return [
                        'success' => false,
                        'data' => $atualizar
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'data' => $login_falhado
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $campos_vazios
            ];
        }
    }
    public function atualizar_password($user_id, $password, $username, $passnova, $passconfinova)
    {
        global $login_falhado, $campos_vazios, $perfilup, $pass_nao_iguais;
        if (!empty($password) && !empty($passnova) && !empty($passconfinova)) {
            if ($passnova == $passconfinova) {
                $senha_hash = $this->control->obter_hash_senha($username);
                if ($senha_hash && password_verify($password, $senha_hash)) {
                    $hashed_novapassword = password_hash($passnova, PASSWORD_DEFAULT);
                    $atualizar = $this->control->atualizar_password($hashed_novapassword, $user_id);
                    if ($atualizar === true) {
                        return [
                            'success' => true,
                            'data' => $perfilup
                        ];
                    } else {
                        return [
                            'success' => false,
                            'data' => $atualizar
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'data' => $login_falhado
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'data' => $pass_nao_iguais
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $campos_vazios
            ];
        }
    }
    public function listar_favoritos($user_id)
    {
        $favoritos =  $this->control->listar_favoritos($user_id);
        if ($favoritos instanceof mysqli_result) {
            $lista_favoritos = [];
            while ($linha = $favoritos->fetch_assoc()) {
                $lista_favoritos[] = [
                    "book_id" => $linha['book_id'],
                    "title" => $linha['title'],
                    "internal_code" => $linha['internal_code'],
                    "fcover_url" => $linha['fcover_url'],
                    "edition_number" => $linha['edition_number'],
                    "language" => $linha['language'],
                    "publisher" => $linha['publisher'],
                    "first_name" => $linha['first_name'],
                    "last_name" => $linha['last_name']
                ];
            }
            return [
                'sucess'  => true,
                'data' => $lista_favoritos,
            ];
        }
    }
    public function listar_multas($user_id)
    {
        $multas =  $this->control->listar_multas($user_id);
        if ($multas instanceof mysqli_result) {
            $lista_multas = [];
            while ($linha = $multas->fetch_assoc()) {
                $form_start_at = null;
                if (!empty($linha['start_at'])) {
                    $form_start_at = date("d-m-Y", strtotime($linha['start_at']));
                }
                $form_payment_date = null;
                if (!empty($linha['payment_date'])) {
                    $form_payment_date = date("d-m-Y", strtotime($linha['payment_date']));
                } else {
                    $form_payment_date = "--/--/----";
                }
                $lista_multas[] = [
                    "id" => $linha['id'],
                    "amount" => $linha['amount'],
                    "status" => $linha['status'],
                    "start_at" => $form_start_at,
                    "payment_date" => $form_payment_date,
                    "request_id" => $linha['request_id'],
                    "fcover_url" => $linha['fcover_url'],
                    "title" => $linha['title'],
                    "request_status" => $linha['request_status']
                ];
            }
            return [
                'sucess'  => true,
                'data' => $lista_multas,
            ];
        }
    }
    public function listar_ticket_types()
    {
        global $setlang;
        $tipos =  $this->control->listar_ticket_types();
        if ($tipos instanceof mysqli_result) {
            $lista_tipos = [];
            while ($linha = $tipos->fetch_assoc()) {
                $type_name = "";
                switch ($setlang) {
                    case 'pt':
                        $type_name = $linha['field_pt'];
                        break;
                    case 'eng':
                        $type_name = $linha['field_eng'];
                        break;
                    default:
                        $type_name = $linha['field_pt'];
                        break;
                }
                $lista_tipos[] = [
                    "type_id" => $linha['type_id'],
                    "type_name" => $type_name
                ];
            }
            return [
                'sucess'  => true,
                'data' => $lista_tipos,
            ];
        }
    }
    public function listar_tickets($user_id)
    {
        global $setlang;
        $tickets =  $this->control->listar_tickets($user_id);
        if ($tickets instanceof mysqli_result) {
            $lista_tickets = [];
            while ($linha = $tickets->fetch_assoc()) {
                $type_name = "";
                switch ($setlang) {
                    case 'pt':
                        $type_name = $linha['field_pt'];
                        break;
                    case 'eng':
                        $type_name = $linha['field_eng'];
                        break;
                    default:
                        $type_name = $linha['field_pt'];
                        break;
                }
                $partes = explode(" - ", $type_name);
                $parte1 = trim($partes[0]);
                $parte2 = trim($partes[1]);
                $lista_tickets[] = [
                    "ticket_id" => $linha['ticket_id'],
                    "title" => $linha['title'],
                    "description" => $linha['description'],
                    "created_at" => date("d-m-Y", strtotime($linha['created_at'])),
                    "status" => $linha['status'],
                    "tipo1" => $parte1,
                    "tipo2" => $parte2
                ];
            }
            return [
                'sucess'  => true,
                'data' => $lista_tickets,
            ];
        }
    }
    public function listar_ticket_page($user_id, $ticket_id)
    {
        global $setlang;
        $resultado =  $this->control->listar_ticket_page($user_id, $ticket_id);
        if ($resultado instanceof mysqli_result) {
            $ticket = [];
            while ($linha = $resultado->fetch_assoc()) {
                $type_name = "";
                switch ($setlang) {
                    case 'pt':
                        $type_name = $linha['field_pt'];
                        break;
                    case 'eng':
                        $type_name = $linha['field_eng'];
                        break;
                    default:
                        $type_name = $linha['field_pt'];
                        break;
                }
                $ticket = [
                    "ticket_id" => $linha['ticket_id'],
                    "title" => $linha['title'],
                    "description" => $linha['description'],
                    "created_at" => date("d-m-Y", strtotime($linha['created_at'])),
                    "status" => $linha['status'],
                    "type_name" => $type_name,
                    "first_name" => $linha['first_name'],
                    "last_name" => $linha['last_name']

                ];
            }
            return [
                'sucess'  => true,
                'data' => $ticket,
            ];
        }
    }
    public function inserir_ticket($user_id, $tipo, $titulo, $descricao)
    {
        global $ticketcriar, $ticketcriarcamposvazios;
        if ($tipo != "all" && $titulo != null && $titulo != "" && $descricao != null && $descricao != "") {
            $resultado =  $this->control->inserir_ticket($user_id, $tipo, $titulo, $descricao);
            if ($resultado === true) {
                return [
                    'success'  => true,
                    'data' => $ticketcriar,
                ];
            } else {
                return [
                    'success'  => false,
                    'data' => $resultado,
                ];
            }
        } else {
            return [
                'success'  => false,
                'data' => $ticketcriarcamposvazios,
            ];
        }
    }
    public function ticket_fechar_resposta($user_id, $ticket_id, $resposta, $btnaccao)
    {
        global $ticketresposta, $ticketrespostavazio, $ticketrespostafechado;
        if (!$btnaccao) {
            if ($resposta != "" || $resposta != null) {
                $responder = $this->control->inserir_ticket_resposta($user_id, $ticket_id, $resposta);
                if ($responder === true) {
                    return [
                        'success'  => true,
                        'data' => $ticketresposta,
                    ];
                } else {
                    return [
                        'success'  => false,
                        'data' => $responder,
                    ];
                }
            } else {
                return [
                    'success'  => false,
                    'data' => $ticketrespostavazio,
                ];
            }
        } elseif ($btnaccao) {
            $responder = null;
            if ($resposta != "" || $resposta != null) {
                $responder = $this->control->inserir_ticket_resposta($user_id, $ticket_id, $resposta);
            }
            $fechar = $this->control->fechar_ticket($user_id, $ticket_id);
            if ($fechar && $responder == null || $responder === true) {
                return [
                    'success'  => true,
                    'data' => $ticketrespostafechado,
                ];
            } else {
                return [
                    'success'  => false,
                    'data' => $responder,
                ];
            }
        }
    }
    public function listar_ticket_respostas($ticket_id)
    {
        $tipos =  $this->control->listar_ticket_respostas($ticket_id);
        if ($tipos instanceof mysqli_result) {
            $lista_tipos = [];
            while ($linha = $tipos->fetch_assoc()) {
                $lista_tipos[] = [
                    "response" => $linha['response'],
                    "replied_at" => date("d-m-Y H:i", strtotime($linha['replied_at'])),
                    "first_name" => $linha['first_name'],
                    "last_name" => $linha['last_name']

                ];
            }
            return [
                'sucess'  => true,
                'data' => $lista_tipos,
            ];
        }
    }
    public function listar_reservas_user($user_id)
    {
        $reservas =  $this->control->listar_reservas_user($user_id);
        if ($reservas instanceof mysqli_result) {
            $lista_reservas = [];
            while ($linha = $reservas->fetch_assoc()) {
                $data_nova = null;
                if ($linha['queue_num'] == 2) {
                    $data_original = $linha['end_at'];

                    // Converter a data original para um timestamp
                    $timestamp_original = strtotime($data_original);

                    // Adicionar 7 dias ao timestamp
                    $timestamp_novo = strtotime('+7 days', $timestamp_original);

                    // Converter o timestamp atualizado para o formato desejado
                    $data_nova = date('d-m-Y', $timestamp_novo);
                } else {
                    $data_nova = date("d-m-Y", strtotime($linha['end_at']));
                }
                $lista_reservas[] = [
                    "reserve_id" => $linha['reserve_id'],
                    "prolonged" => $linha['prolonged'],
                    "queue_num" => $linha['queue_num'],
                    "fcover_url" => $linha['fcover_url'],
                    "bcover_url" => $linha['bcover_url'],
                    "book_id" => $linha['book_id'],
                    "title" => $linha['title'],
                    "internal_code" => $linha['internal_code'],
                    "edition_number" => $linha['edition_number'],
                    "page_number" => $linha['page_number'],
                    "publisher" => $linha['publisher'],
                    "language" => $linha['language'],
                    "author_id" => $linha['author_id'],
                    "first_name" => $linha['first_name'],
                    "last_name" => $linha['last_name'],
                    "end_at" => $data_nova
                ];
            }
            return [
                'success'  => true,
                'data' => $lista_reservas,
            ];
        } else {
            return [
                'success'  => false,
                'data' => $reservas,
            ];
        }
    }
    public function remover_reserva($reserva_id)
    {
        global $cancelarreservamens;
        $resultado =  $this->control->remover_reserva($reserva_id);
        if ($resultado === true) {
            return [
                'success'  => true,
                'data' => $cancelarreservamens,
            ];
        } else {
            return [
                'success'  => false,
                'data' => $resultado,
            ];
        }
    }
    public function extender_requisicao($requisicao_id, $end_date)
    {
        global $reqdatalimite;
        $new_end_date = date("Y-m-d", strtotime($end_date . ' + 1 week'));
        $resultado =  $this->control->extender_requisicao($requisicao_id, $new_end_date);
        if ($resultado === true) {
            return [
                'success'  => true,
                'data' => $reqdatalimite . ": " . date("d-m-Y", strtotime($new_end_date)),
            ];
        } else {
            return [
                'success'  => false,
                'data' => $resultado,
            ];
        }
    }
    public function cancelar_requisicao($requisicao_id)
    {
        global $cancelarrequi;
        $resultado =  $this->control->cancelar_requisicao($requisicao_id);
        if ($resultado === true) {
            return [
                'success'  => true,
                'data' => $cancelarrequi,
            ];
        } else {
            return [
                'success'  => false,
                'data' => $resultado,
            ];
        }
    }
    public function listar_users_status_del()
    {
        $users =  $this->control->listar_users_status_del();
        if ($users instanceof mysqli_result) {
            $lista_users = [];
            while ($linha = $users->fetch_assoc()) {
                $lista_users[] = [
                    "id" => $linha['id'],
                    "username" => $linha['username'],
                    "email" => $linha['email'],
                    "first_name" => $linha['first_name'],
                    "last_name" => $linha['last_name'],
                    "updated_at" => date("d-m-Y H:i:s", strtotime($linha['updated_at'])),
                ];
            }
            return [
                'success'  => true,
                'data' => $lista_users,
            ];
        } else {
            return [
                'success'  => false,
                'data' => $users,
            ];
        }
    }
    function pesquisar_utilizadores($user)
    {
        $utilizadores = $this->control->pesquisar_utilizadores($user);

        if ($utilizadores instanceof mysqli_result) {
            $utilizadores_lista = [];
            while ($linha = $utilizadores->fetch_assoc()) {
                $utilizadores_lista[] = [
                    "id" => $linha['id'],
                    "first_name" => $linha['first_name'],
                    "last_name"    => $linha['last_name'],
                    "username"    => $linha['username'],
                    "email" => $linha['email'],
                    "photo_url" => $linha['photo_url'],
                ];
            }
            return [
                'success' => true,
                'data' => $utilizadores_lista
            ];
        } else {
            return [
                'success' => false,
                'data' => $utilizadores
            ];
        }
    }
    public function update_user_staff_admin($user_id, $first_name, $last_name, $email, $address, $postal_code, $number)
    {
        global $emailexiste, $numeroinvalido, $codigopostalinvalido, $campos_vazios;
        if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($address) && !empty($postal_code) && !empty($number)) {
            if (preg_match('/^9\d{8}$/', $number)) {
                if (preg_match('/^\d{4}-\d{3}$/', $postal_code)) {
                    //$verifica_email = $this->control->verificar_email($email);
                    //if (!$verifica_email || $email == $oldemail) {
                    global $perfilup;
                    //$photo = basename($photo_url);
                    $update_user = $this->control->update_user_staff_admin($user_id, $first_name, $last_name, $email, $address, $postal_code, $number);
                    if ($update_user === true) {
                        return [
                            'success' => true,
                            'data' => $perfilup
                        ];
                    } else {
                        return [
                            'success' => false,
                            'data' => $update_user
                        ];
                    }
                    // } else {
                    //     return [
                    //         'success' => false,
                    //         'data' => $emailexiste
                    //     ];
                    // }
                } else {
                    return [
                        'success' => false,
                        'data' => $codigopostalinvalido
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'data' => $numeroinvalido
                ];
            }
        } else {
            return [
                'success' => false,
                'data' => $campos_vazios
            ];
        }
    }
    public function alterar_status_user($user_id, $status)
    {
        global $desbloquearuser, $bloquearuser;
        if ($status == $bloquearuser) {
            $resultado = $this->control->bloquear_user($user_id);
            if ($resultado === true) {
                return true;
            } else {
                return false;
            }
        } else if ($status == $desbloquearuser) {
            $resultado = $this->control->desbloquear_user($user_id);
            if ($resultado === true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function pagar_multa_user($user_id, $multa_id, $totalmultas)
    {
        $multa = $this->control->pagar_multa_user($multa_id);
        if ($multa === true) {
            if ($totalmultas == 1) {
                $resultado = $this->control->desbloquear_user($user_id);
            }
            return true;
        } else {
            return false;
        }
    }
    public function entregar_livro($requisicao_id)
    {
        $entrega = $this->control->entregar_livro($requisicao_id);
        if ($entrega === true) {
            return true;
        } else {
            return false;
        }
    }
    public function ativar_requisicao($requisicao_id)
    {
        $ativar = $this->control->ativar_requisicao($requisicao_id);
        if ($ativar === true) {
            return true;
        } else {
            return false;
        }
    }
    public function listar_todos_tickets()
    {
        global $setlang;
        $tickets =  $this->control->listar_todos_tickets();
        if ($tickets instanceof mysqli_result) {
            $lista_tickets = [];
            while ($linha = $tickets->fetch_assoc()) {
                $type_name = "";
                switch ($setlang) {
                    case 'pt':
                        $type_name = $linha['field_pt'];
                        break;
                    case 'eng':
                        $type_name = $linha['field_eng'];
                        break;
                    default:
                        $type_name = $linha['field_pt'];
                        break;
                }
                $partes = explode(" - ", $type_name);
                $parte1 = trim($partes[0]);
                $parte2 = trim($partes[1]);
                $lista_tickets[] = [
                    "ticket_id" => $linha['ticket_id'],
                    "user_id" => $linha['user_id'],
                    "title" => $linha['title'],
                    "description" => $linha['description'],
                    "created_at" => date("d-m-Y", strtotime($linha['created_at'])),
                    "status" => $linha['status'],
                    "tipo1" => $parte1,
                    "tipo2" => $parte2,
                    "admin_response" => $linha['admin_response'],
                    "first_name" => $linha['first_name'],
                    "last_name" => $linha['last_name'],
                    "username" => $linha['username'],
                    "email" => $linha['email']
                ];
            }
            return [
                'sucess'  => true,
                'data' => $lista_tickets,
            ];
        }
    }
    public function pesquisa_ticket($texto, $tipo)
    {
        global $setlang;
        $ticket_tipo = ($tipo === "all") ? "" : $tipo;
        $tickets =  $this->control->pesquisa_ticket($texto, $ticket_tipo);
        if ($tickets instanceof mysqli_result) {
            $lista_tickets = [];
            while ($linha = $tickets->fetch_assoc()) {
                $type_name = "";
                switch ($setlang) {
                    case 'pt':
                        $type_name = $linha['field_pt'];
                        break;
                    case 'eng':
                        $type_name = $linha['field_eng'];
                        break;
                    default:
                        $type_name = $linha['field_pt'];
                        break;
                }
                $partes = explode(" - ", $type_name);
                $parte1 = trim($partes[0]);
                $parte2 = trim($partes[1]);
                $lista_tickets[] = [
                    "ticket_id" => $linha['ticket_id'],
                    "user_id" => $linha['user_id'],
                    "title" => $linha['title'],
                    "description" => $linha['description'],
                    "created_at" => date("d-m-Y", strtotime($linha['created_at'])),
                    "status" => $linha['status'],
                    "tipo1" => $parte1,
                    "tipo2" => $parte2,
                    "admin_response" => $linha['admin_response'],
                    "first_name" => $linha['first_name'],
                    "last_name" => $linha['last_name'],
                    "username" => $linha['username'],
                    "email" => $linha['email']
                ];
            }
            return [
                'success'  => true,
                'data' => $lista_tickets,
            ];
        }
    }
    public function apagar_utlizador_perma($user_id)
    {
        $ativar = $this->control->apagar_utlizador_perma($user_id);
        if ($ativar === true) {
            return true;
        } else {
            return false;
        }
    }
    public function pedir_apagar_user_procconta($user_id)
    {
        $ativar = $this->control->pedir_apagar_user($user_id);
        if ($ativar === true) {
            return true;
        } else {
            return false;
        }
    }
    public function cancelar_apagar_user_id($user_id)
    {
        $ativar = $this->control->cancelar_apagar_user_id($user_id);
        if ($ativar === true) {
            return true;
        } else {
            return false;
        }
    }
    public function listar_todas_requisicoes()
    {
        $requisicoes =  $this->control->listar_todas_requisicoes();
        if ($requisicoes instanceof mysqli_result) {
            $lista_requisicoes = [];
            while ($linha = $requisicoes->fetch_assoc()) {
                $date = ($linha['end_at'] != null) ? date("d-m-Y", strtotime($linha['end_at'])) : $linha['end_at'] ;
                $lista_requisicoes[] = [
                    "id" => $linha['request_id'],
                    "username" => $linha['username'],
                    "title" => $linha['title'],
                    "start_at" => date("d-m-Y", strtotime($linha['start_at'])),
                    "end_at" => $date,
                    "review_status" => $linha['review_status'],
                    "expired" => $linha['expired'],
                    "user_id" => $linha['user_id'],
                    "internal_code" => $linha['internal_code']
                ];
            }
            return [
                'sucess'  => true,
                'data' => $lista_requisicoes,
            ];
        }
    }
    public function listar_todas_multas()
    {
        $multas =  $this->control->listar_todas_multas();
        if ($multas instanceof mysqli_result) {
            $lista_multas = [];
            while ($linha = $multas->fetch_assoc()) {
                $lista_multas[] = [
                    "id" => $linha['fine_id'],
                    "request_id" => $linha['request_id'],
                    "user_id" => $linha['user_id'],
                    "username" => $linha['username'],
                    "title" => $linha['title'],
                    "internal_code" => $linha['internal_code'],
                    "amount" => $linha['amount'],
                    "start_at" => date("d-m-Y", strtotime($linha['start_at']))
                ];
            }
            return [
                'sucess'  => true,
                'data' => $lista_multas,
            ];
        }
    }
}
