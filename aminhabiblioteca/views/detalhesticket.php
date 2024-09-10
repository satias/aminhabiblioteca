<?php
include_once "verifica-sessao.php";

if(isset($_POST['ticket_id'])){
    $ticket_id = $_POST['ticket_id'];
}else{
    header('Location: ' . get_link("suporte"));
}
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
$ticket = $controlo->listar_ticket_page($_SESSION['user_dados']['id'], $ticket_id);
$respostas = $controlo->listar_ticket_respostas($ticket_id);
?>
<div id="alert-container" class="fade show position-absolute mt-4 start-50 translate-middle-x top-1 w-auto" style="z-index: 1;"></div> <!-- Container para os alerts -->
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary">A Minha Biblioteca / </a>
                    <a href="<?php echo get_link("suporte") ?>" id="identidade-site" class="color-text"><?php echo $suporte ?></a>
                </span>
                <span class="color-text"><?php echo "Ticket" ?></span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-detalhes-ticket px-5 pt-3">
            <div class="col-item s-back-1 d-flex flex-row justify-content-between px-5 align-items-center subtitulo-font">
                <div class="d-flex flex-column mx-3">
                    <span class="color-primary"><?php echo $numeroticket ?></span>
                    <span class="color-text"><?php echo $ticket['data']['ticket_id'] ?></span>
                </div>
                <div class="d-flex flex-column mx-3">
                    <span class="color-primary"><?php echo $atualizadopor ?></span>
                    <span class="color-text">
                        <?php
                        if (!empty($respostas['data'])) {
                            $ultimaLinha = end($respostas['data']);
                            echo $ultimaLinha['first_name'] . " " . $ultimaLinha['last_name'];
                        } else {
                            echo $ticket['data']['first_name'] . " " . $ticket['data']['last_name'];
                        }

                        ?>
                    </span>
                </div>
                <div class="d-flex flex-column mx-3">
                    <span class="color-primary"><?php echo $tipo ?></span>
                    <span class="color-text"><?php echo $ticket['data']['type_name'] ?></span>
                </div>
                <div class="d-flex flex-column mx-3">
                    <span class="color-primary"><?php echo $estado ?></span>
                    <span class="color-text">
                        <?php
                        if ($ticket['data']['status']) {
                            echo $aberto;
                        } else {
                            echo $fechado;
                        }
                        ?>
                    </span>
                </div>
                <div class="d-flex flex-column mx-3">
                    <span class="color-primary"><?php echo $criadoa ?></span>
                    <span class="color-text"><?php echo $ticket['data']['created_at'] ?></span>
                </div>
            </div>
            <div class=""></div>
            <div class="grid-ticket-conteudo criar-ticket-form">
                <div class="col-item s-back-1 srcoll-div p-3">
                    <label class="color-primary subtitulo-font"><?php echo $titulo ?></label>
                    <p class="color-text texto-font text-justify">
                        <?php
                        echo nl2br(htmlspecialchars($ticket['data']['title']));
                        ?>
                    </p>
                    <label class="color-primary subtitulo-font"><?php echo $descricao ?></label>
                    <p class="color-text texto-font text-justify">
                        <?php
                        echo nl2br(htmlspecialchars($ticket['data']['description']));
                        ?>
                    </p>
                    <?php
                    if (!empty($respostas['data'])) {
                        foreach ($respostas['data'] as $item) {
                    ?>
                            <label class="color-primary subtitulo-font"><?php echo $respostade . " ". $item['first_name'] . " " . $item['last_name'] . " ". $respostaa ." " . $item['replied_at']?></label>
                            <p class="color-text texto-font text-justify">
                                <?php
                                echo nl2br(htmlspecialchars($item['response']));
                                ?>
                            </p>
                    <?php
                        }
                    }
                    ?>
                </div>
                <div class="col-item s-back-1 srcoll-div p-3">
                    <label for="exampleFormControlTextarea1" class="form-label color-primary subtitulo-font"><?php echo $respoticket ?></label>
                    <textarea class="form-control color-text texto-font altura-respo" id="respo-ticket-texto" rows="19" placeholder="<?php echo $respotickettextarea ?>" <?php echo (!$ticket['data']['status']) ? 'disabled' : ''; ?>></textarea>
                </div>
            </div>
            <div></div>
            <div class="d-flex my-auto ms-auto">
                <div class="d-flex flex-row w-100 justify-content-center subtitulo-font">
                    <button type="button" class="p-2 btn-open color-text" id="botao-repo-close-ticket" name="<?php echo $ticket['data']['ticket_id'] ?>" <?php echo (!$ticket['data']['status']) ? 'disabled' : ''; ?>>
                        <?php echo $respofechar ?>
                    </button>
                    <button type="button" class="p-2 btn-closed color-text " id="botao-repo-apenas-ticket" name="<?php echo $ticket['data']['ticket_id'] ?>" <?php echo (!$ticket['data']['status']) ? 'disabled' : ''; ?>>
                        <?php echo $respoapenas ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".btn-open, .btn-closed").on("click", function() {
        var ticketidname = $(this).attr("name");
        var ticketidbtn = $(this).attr("id");
        var validTicketIds = [
            "botao-repo-close-ticket",
            "botao-repo-apenas-ticket",
        ];
        // Verifique se o ticketId é válido
        if (validTicketIds.includes(ticketidbtn)) {
            var btnaccao = $(this).hasClass("btn-open") ? 1 : 0;
            var respo = $("#respo-ticket-texto").val();
            // Enviar dados via AJAX
            //alert(btnaccao);
            $.ajax({
                type: "POST",
                url: "<?php echo get_link(''); ?>views/post_views/post_responder_ticket.php",
                data: {
                    ticketidname: ticketidname,
                    btnaccao: btnaccao,
                    respo: respo,
                },
                dataType: "html",
                success: function(response) {
                    if (response) {
                        $("#alert-container").html(response);
                    }
                },
            });
        }
    });
</script>