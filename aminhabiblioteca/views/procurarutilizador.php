<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();

include "verificar_funcionario.php";
$utilizador_id = "";
if (isset($_POST['post_username'])) {
    $utilizador_id = $_POST['post_username'];
    $user_detalhes = $controlo->get_user_detalhes($utilizador_id);
    $lista_multas = $controlo->listar_multas($utilizador_id);
    $lista_requisicoes = $controlo->listar_requisicoes_livrosautores($utilizador_id);
    $lista_reservas = $controlo->listar_reservas_user($utilizador_id);
}
echo '<script>';
echo 'console.log("user_id: ' . $utilizador_id . '");';
echo '</script>';
?>
<div id="alert-container" class="fade show position-absolute mt-4 start-50 translate-middle-x top-1 w-auto">
</div> <!-- Container para os alerts -->
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary"><?php echo $staffpages ?> / </a>
                    <a href="<?php echo get_link("procurarutilizador") ?>" id="identidade-site" class="color-text"><?php echo $procuti ?></a>
                </span>
                <span class="color-text"><?php echo $procuti ?></span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-procurarconta px-5">
            <div class="grid-utilizador-info">
                <div class="col-item s-back-1 p-3 d-flex flex-row align-items-center justify content-between" id="col-pesquisa">
                    <div class="position-relative w-100">
                        <div class="input-group flex-nowrap me-5 w-75">
                            <span class="input-group-text material material-symbols-rounded color-accent bg-transparent input-dark-border-icon" id="addon-wrapping">search</span>
                            <input type="text" id="procurarconta-input" class="texto-font form-control bg-transparent color-text input-dark-border" placeholder="<?php echo $procnomeemailcodigo ?>" aria-label="procurarurilizador" aria-describedby="addon-wrapping">
                        </div>
                        <div class="position-absolute procurarconta-input-tab shadow-sm rounded-2 d-none mt-2 tab-pesq-conta" id="procurarconta-input-tab">
                        </div>
                    </div>
                </div>
                <div class="col-item s-back-1 p-3 d-flex flex-row flex-wrap">
                    <div class="col-6 h-50 d-flex">
                        <div class="avatar-ball rounded-circle m-auto" id="imagem-perfil"></div>
                        <?php
                        $back_img = "";
                        if (!empty($user_detalhes['data']) && !empty($user_detalhes['data']['photo_url'])) {
                            $back_img = "background-image: url('libs/img/img-perfil/" . $user_detalhes['data']['photo_url'] . "');";
                        }
                        ?>
                        <style>
                            .avatar-ball {
                                width: 18rem;
                                height: 18rem;
                                background-color: #dadada;
                                overflow: hidden;
                                background-repeat: no-repeat;
                                background-position: 50% 50%;
                                background-size: cover;
                                <?php echo $back_img; ?>
                            }
                        </style>
                    </div>
                    <div class="col-6 h-50 d-flex flex-column justify-content-around subtitulo-font">
                        <div class="mx-3">
                            <label for="log-username color-primary" class="form-label"><?php echo $numeroconta ?></label>
                            <input type="text" disabled id="procurar-editar-utilizador-id" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" value="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['id'] : ''; ?>">
                        </div>
                        <div class="mx-3">
                            <label for="log-username color-primary" class="form-label"><?php echo $username ?></label>
                            <input type="text" disabled class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" value="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['username'] : ''; ?>">
                        </div>
                        <div class="mx-3">
                            <label for="log-username color-primary" class="form-label"><?php echo $nome ?></label>
                            <div class="w-100 d-flex flex-row flex-nowrap gap-1">
                                <input type="text" id="procurar-editar-utilizador-prinome" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" value="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['first_name'] : ''; ?>">
                                <input type="text" id="procurar-editar-utilizador-ultnome" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" value="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['last_name'] : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 h-50 d-flex flex-row flex-wrap">
                        <div class="col-6 subtitulo-font d-flex flex-column justify-content-around">
                            <div class="mx-3">
                                <label for="log-username color-primary" class="form-label"><?php echo "Email" ?></label>
                                <input type="text" id="procurar-editar-utilizador-email" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" value="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['email'] : ''; ?>">
                            </div>
                            <div class="mx-3">
                                <label for="log-username color-primary" class="form-label"><?php echo $morada ?></label>
                                <input type="text" id="procurar-editar-utilizador-morada" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" value="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['address'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-6 subtitulo-font d-flex flex-column justify-content-around">
                            <div class="mx-3">
                                <label for="log-username color-primary" class="form-label"><?php echo $numero ?></label>
                                <input type="text" id="procurar-editar-utilizador-numero" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" maxlength="9" value="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['number'] : ''; ?>">
                            </div>
                            <div class="mx-3">
                                <label for="log-username color-primary" class="form-label"><?php echo $codigopostal ?></label>
                                <input type="text" id="procurar-editar-utilizador-codigopostal" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" maxlength="8" id="perfil-codigopostal" value="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['postal_code'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-row align-items-end pb-1">
                            <div class="w-30 d-flex flex-column align-items-center subtitulo-font">
                                <label for="log-username" class="form-label subtitulo-font color-primary"><?php echo $ultatuali ?></label>
                                <input type="text" disabled class="form-control text-font text-center w-85 bg-transparent color-text input-normal-dark-border" placeholder="<?php echo (!empty($user_detalhes['data'])) ? $user_detalhes['data']['updated_at'] : ''; ?>">
                            </div>
                            <div class="w-25 d-flex flex-column align-items-center subtitulo-font">
                                <label for="log-username" class="form-label color-primary"><?php echo $statusconta ?></label>
                                <?php
                                $status_mensagem = "";
                                if (!empty($user_detalhes['data'])) {
                                    if ($user_detalhes['data']['status']) {
                                        $status_mensagem = $normal;
                                    } else {
                                        $status_mensagem = $bloqueado;
                                    }
                                }
                                ?>
                                <input type="text" disabled class="form-control text-center w-75 subtitulo-font bg-transparent color-text input-normal-dark-border" value="<?php echo $status_mensagem  ?>">
                            </div>
                            <?php
                            if (!empty($user_detalhes['data'])) {
                                if ($user_detalhes['data']['status_del'] == 0) {
                            ?>
                                    <button type="button" id="procurar-botao-status-utilizador" class="btn-back-primary color-back py-2 px-4 subtitulo-font rounded-pill"><?php echo (!empty($user_detalhes['data']) && $user_detalhes['data']['status']) ? $bloquearuser : $desbloquearuser; ?></button>
                                    <button type="button" id="procurar-botao-editar-utilizador" class="btn-back-primary color-back p-2 rounded-circle ms-auto d-flex">
                                        <span class="material-symbols-rounded icon-30 no-fill my-auto">
                                            edit
                                        </span>
                                    </button>
                                    <?php
                                    $atual_id = $_SESSION['user_dados']['id'];
                                    if ($atual_id != $utilizador_id && $user_detalhes['data']['status'] == 1) {
                                    ?>
                                        <button type="button" id="procurar-botao-apagar-utilizador" class="btn-back-primary color-back p-2 rounded-circle d-flex ms-2">
                                            <span class="material-symbols-rounded icon-30 no-fill my-auto">
                                                delete
                                            </span>
                                        </button>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <div class="d-flex flex-column ms-auto align-items-end">
                                        <span class="subtitulo-font color-text">
                                            <?php echo $estado_conta_del; ?>
                                        </span>
                                        <div>
                                            <button type="button" id="procurar-botao-cancelar-apagar-utilizador" class="btn-back-primary color-back p-2 rounded-pill d-flex">
                                                <span class="subtitulo-font color-back">
                                                    <?php echo $cancelarapagar; ?>
                                                </span>
                                            </button>
                                        </div>

                                    </div>

                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid-utilizador-finesrequests">
                <div class="col-item p-3 s-back-2">
                    <div class="d-flex align-items-center">
                        <span class="color-text titulo-font"><?php echo $multas ?></span>
                        <?php
                        if (!empty($lista_multas['data'])) {
                        ?>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="ms-auto border-1-primary color-primary subtitulo-font rounded-pill">
                                View all
                            </button>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="texto-font px-3 pt-2 pb-5 d-flex flex-column my-auto h-100">
                        <?php
                        if (!empty($lista_multas['data'])) {
                            $my_auto = (sizeof($lista_multas) >= 3) ? "my-auto" : "my-2";
                            foreach ($lista_multas['data'] as $item) {
                                if ($item['status'] == 1) {
                        ?>
                                    <div class="d-flex <?php echo $my_auto ?>">
                                        <div class="d-flex flex-column">
                                            <span class="color-text">
                                                <?php
                                                echo $requisicao . ": " . $item['request_id'];
                                                echo " / ";
                                                echo $titulo . ": " . $item['title']
                                                ?>
                                            </span>
                                            <span class="color-primary">
                                                <?php
                                                echo $dataemissao . ": " . $item['start_at'];
                                                ?>
                                            </span>
                                        </div>
                                        <div class="ms-auto my-auto">
                                            <span class="color-primary  px-2"><?php echo $item['amount'] . "€" ?></span>
                                            <?php
                                            $disabled = ($item['request_status'] == 0) ? "" : "disabled";
                                            ?>
                                            <button type="button" <?php echo $disabled; ?> name="<?php echo $item['id'] ?>" class="color-text px-2 text-decoration-underline border-0 procurar-botao-pagar-multa">
                                                <?php echo $pagar ?>
                                            </button>
                                        </div>

                                    </div>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-item p-3 s-back-2">
                    <div class="titulo-font d-flex align-items-center">
                        <span class="color-text"><?php echo $requisicoes ?></span>
                    </div>
                    <div class="texto-font px-3 pt-2 pb-5 d-flex flex-column my-auto h-100">
                        <?php
                        if (!empty($lista_requisicoes)) {
                            $status_count = count(array_filter($lista_requisicoes, function ($requisicaostatus) {
                                return $requisicaostatus['status'] == 1;
                            }));
                            $my_auto = ($status_count >= 3) ? "my-auto" : "my-2";
                            foreach ($lista_requisicoes as $item) {
                                if ($item['status'] == 1) {
                        ?>
                                    <div class="d-flex <?php echo $my_auto ?>">
                                        <div class="d-flex flex-column">
                                            <span class="color-text">
                                                <?php
                                                echo $requisicao . ": " . $item['id'];
                                                echo " / ";
                                                echo $titulo . ": " . $item['title']
                                                ?>
                                            </span>
                                            <span class="color-primary">
                                                <?php
                                                echo $item['start_date'] . " - " . $item['end_date'];
                                                ?>
                                            </span>
                                        </div>
                                        <div class="ms-auto my-auto">
                                            <?php
                                            if (!$item['expired']) {
                                                if ($item['end_date'] != "--/--/----") {
                                            ?>
                                                    <span class="color-primary px-2"><?php echo $ativo ?></span>
                                                    <button type="button" name="<?php echo $item['id'] ?>" class="color-text px-2 text-decoration-underline border-0 procurar-botao-entregar-livro"><?php echo $entregar ?></button>
                                                <?php
                                                } else {
                                                ?>
                                                    <span class="color-primary px-2"><?php echo $pendente ?></span>
                                                    <button type="button" name="<?php echo $item['id'] ?>" class="color-text px-2 text-decoration-underline border-0 procurar-botao-ativar-requisicao"><?php echo $ativar ?></button>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <span class="color-primary px-2"><?php echo $atrasado ?></span>
                                                <button type="button" name="<?php echo $item['id'] ?>" class="color-text px-2 text-decoration-underline border-0 procurar-botao-entregar-livro"><?php echo $entregar ?></button>
                                            <?php
                                            }
                                            ?>

                                        </div>
                                    </div>
                        <?php
                                }
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- modal para lista de multas -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><?php echo $historicocompleto ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body table-box">
                        <div class="h-100 table-box">
                            <table class="mt-2 text-center">
                                <tr class="subtitulo-font color-text opacidade-60">
                                    <td><?php echo $requisicao ?></td>
                                    <td colspan="2"><?php echo $titulo ?></td>
                                    <td class="text-center"><?php echo $dataemissao ?></td>
                                    <td class="text-center"><?php echo $datapagamento ?></td>
                                    <td class="text-center"><?php echo $valor ?></td>
                                </tr>
                                <?php
                                foreach ($lista_multas['data'] as $item) {
                                ?>
                                    <tr class="subtitulo-font color-text">
                                        <td class="text-center"><?php echo $item['request_id'] ?></td>
                                        <td colspan="2">
                                            <img src="libs/img/book-covers/<?php echo $item['fcover_url'] ?>" alt="<?php echo $item['fcover_url'] ?>">
                                            <span class="ms-2"><?php echo $item['title'] ?></span>
                                        </td>
                                        <td class="text-center"><?php echo $item['start_at'] ?></td>
                                        <td class="text-center"><?php echo $item['payment_date'] ?></td>
                                        <td class="text-center"><?php echo $item['amount'] . '€' ?></td>

                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>