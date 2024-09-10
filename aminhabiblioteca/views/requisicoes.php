<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
$lista_requisicoes = $controlo->listar_requisicoes_livrosautores($_SESSION['user_dados']['id']);
$req_registradas = 0;
$req_ativas = 0;
$req_pendentes = 0;
$n_reservas = 0;
foreach ($lista_requisicoes as $linha) {
    if ($linha['status'] == 1) {
        $req_registradas++;
        if ($linha['end_date'] != "--/--/----") {
            $req_ativas++;
        }
        if ($linha['end_date'] == "--/--/----") {
            $req_pendentes++;
        }
    }
}
$req_permitidas = 5;
$lista_reservas = $controlo->listar_reservas_user($_SESSION['user_dados']['id']);
$n_reservas = count($lista_reservas['data']);
$reserva_mensagem = null;
if ($n_reservas != 0) {
    $req_permitidas = $req_permitidas - $n_reservas;
    $reserva_mensagem = " " . $com . " " . $n_reservas . " " . "<span class='text-lowercase'>" ;
    if($n_reservas == 1){
        $reserva_mensagem .= $reserva  . "</span>";
    }else{
        $reserva_mensagem .= $reservas  . "</span>";
    }
}
// echo '<script>';
// echo 'console.log("' . $n_reservas . '");';
// echo '</script>';
?>
<div id="alert-container" class="fade show position-absolute mt-4 start-50 translate-middle-x top-1 w-auto">
</div> <!-- Container para os alerts -->
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary">A Minha Biblioteca / </a>
                    <a href="<?php echo get_link("requisicoes") ?>" id="identidade-site" class="color-text"><?php echo $requisicoes ?></a>
                </span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-requisicoes px-5">
            <div class="grid-details-livro">
                <div class="d-flex flex-row align-items-start">
                    <div>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="d-flex color-primary border-bottom-primary me-5 bg-transparent">
                            <span class="subtitulo-font text-lowercase"><?php echo $ver . " " . $historicocompleto ?></span>
                            <span class="material-symbols-rounded icon-25">
                                add
                            </span>
                        </button>
                    </div>
                    <div class="subtitulo-font color-accent ms-5">
                        <span>
                            <?php
                            echo $requisicoes . " " . $req_registradas . "/" . $req_permitidas;
                            echo " - " . $req_ativas . " " . $ativas . " / " . $req_pendentes . " " . $pendentes . " ";
                            echo $reserva_mensagem;
                            ?>
                        </span>
                    </div>
                    <!-- Modal historico completo -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel"><?php echo $historicocompleto ?></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body table-box">
                                    <table>
                                        <tr class="subtitulo-font color-text opacidade-60">
                                            <td class="text-center"><?php echo $requisicao ?></td>
                                            <td><?php echo $titulo ?></td>
                                            <td class="text-center"><?php echo $datacomeço ?></td>
                                            <td class="text-center"><?php echo $datalimite ?></td>
                                            <td class="text-center"><?php echo $entregaatrasada ?></td>
                                        </tr>
                                        <?php
                                        foreach ($lista_requisicoes as $linha) {
                                            if ($linha['status'] == 0) {
                                        ?>
                                                <tr class="subtitulo-font color-text">
                                                    <td class="text-center"><?php echo $linha['id'] ?></td>
                                                    <td><?php echo $linha['title'] ?></td>
                                                    <td class="text-center"><?php echo $linha['start_date'] ?></td>
                                                    <td class="text-center"><?php echo $linha['end_date'] ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        if (!$linha['expired']) {
                                                            echo $sim;
                                                        } else {
                                                            echo $nao;
                                                        }

                                                        ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-item s-back-2 d-flex flex-row p-3" id="req-detalhes">
                    <div class="color-text subtitulo-font">
                        <span><?php echo $requisicaomensagem ?></span>
                    </div>
                </div>
            </div>
            <div></div>
            <div class="grid-req">
                <?php
                foreach ($lista_requisicoes as $linha) {
                    if ($linha['status'] == 1) {
                ?>
                        <div class="s-back-1 mx-1 col-item d-flex flex-column justify-content-evenly align-items-center position-relative" id="req-caixa-<?php echo $linha['id'] ?>" name="req-caixa">
                            <div class="color-text text-center">
                                <p class="subtitulo-font"><?php echo $linha['title'] ?></p>
                                <p class="texto-font"><?php echo $codinter . ": " . $linha['internal_code'] ?></p>
                            </div>
                            <div class="texto-font color-accent text-center">
                                <p>
                                    <?php
                                    echo $estado . ": ";
                                    if (!$linha['expired']) {
                                        if ($linha['end_date'] != "--/--/----") {
                                            echo $ativo;
                                        } else {
                                            echo $pendente;
                                        }
                                    } else {
                                        echo $atrasado;
                                    }
                                    ?>
                                </p>
                                <p><?php echo $comecaa . ": " . $linha['start_date'] ?></p>
                                <p><?php echo $acabaa . ": " . $linha['end_date'] ?></p>
                            </div>
                            <button class="btn-back-primary color-back px-2 btn-requisicao-detalhes" id="<?php echo $linha['id'] ?>" type="button"><?php echo $detalhes ?></button>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

    </div>
</div>