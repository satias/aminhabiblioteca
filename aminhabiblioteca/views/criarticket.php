<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$user_controlo = new user_controlo();
$listar_ticket_types = $user_controlo->listar_ticket_types();

?>
<div id="alert-container" class="fade show position-absolute mt-4 start-50 translate-middle-x top-1 w-auto"></div>
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
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-criar-ticket px-5 pt-3">
            <div></div>
            <div class="s-back-1 col-item p-3 d-flex flex-column srcoll-div criar-ticket-form">
                <div class="my-3 d-flex align-items-center flex-row">
                    <div class="select-input">
                        <label class="form-label color-primary subtitulo-font"><?php echo $tipostickets ?></label>
                        <select class="pesquisa-form form-select color-text w-auto" id="ticket-criar-tipo" aria-label="Floating label select example">
                            <option selected value="all"><?php echo $escolher ?></option>
                            <?php
                            foreach ($listar_ticket_types['data'] as $item) {
                            ?>
                                <option value="<?php echo $item['type_id'] ?>"><?php echo $item['type_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <button type="button" id="ticket-criar-botao" class="ms-auto me-2 btn-back-primary color-back subtitulo-font text-decoration-none"><?php echo $confirmar; ?></button>
                </div>
                <div class="my-3">
                    <label for="exampleFormControlTextarea1" class="form-label color-primary subtitulo-font"><?php echo $titulo ?></label>
                    <textarea class="form-control color-text texto-font titulo-desc" id="ticket-criar-titulo" maxlength="255" rows="2" placeholder="<?php echo $titulotexto; ?>"></textarea>
                </div>
                <div class="my-3">
                    <label for="exampleFormControlTextarea1" class="form-label color-primary subtitulo-font"><?php echo $descricao ?></label>
                    <textarea class="form-control color-text texto-font altura-desc" id="ticket-criar-descricao" rows="15" placeholder="<?php echo $desctexto; ?>"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>