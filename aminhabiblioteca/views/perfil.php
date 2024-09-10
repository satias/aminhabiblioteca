<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
$utilizador_data = $controlo->get_user_detalhes($_SESSION['user_dados']['id']);
?>
<div id="alert-container" class="fade show position-absolute mt-4 start-50 translate-middle-x top-1 w-auto">
    <?php
    if (!$utilizador_data["success"]) {
        echo '
        <div class="alert alert-warning alert-dismissible position-absolute top-0 start-50 translate-middle-x" role="alert ">
            ' . $utilizador_data["data"] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
         ';
    }
    ?>
</div> <!-- Container para os alerts -->
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary">A Minha Biblioteca / </a>
                    <a href="<?php echo get_link("perfil") ?>" id="identidade-site" class="color-text"><?php echo $perfil ?></a>
                </span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-perfil px-5 pt-3">
            <div class="s-back-2 col-item profile-botoes d-flex">
                <div class="my-auto w-100 mx-5 d-flex align-items-end flex-row subtitulo-font profile-botoes">
                    <button class="profile-link-active color-text px-3" type="button"><?php echo $editarperfil ?></button>
                    <button class="profile-link-inactive color-text px-3" type="button"><?php echo $seguranca ?></button>
                    <div class="w-25 profile-link-inactive h-100">
                    </div>
                </div>
            </div>
            <div class="s-back-1 srcoll-div col-item p-4 d-flex flex-row">
                <div class="w-35 d-flex flex-column justify-content-center align-items-center" id="section-img-perfil">
                    <div class="avatar-ball rounded-circle mb-3" id="imagem-perfil"></div>
                    <style>
                        .avatar-ball {
                            width: 20rem;
                            height: 20rem;
                            background-color: #dadada;
                            overflow: hidden;
                            background-image: url('libs/img/img-perfil/<?php echo $utilizador_data["data"]["photo_url"] ?>');
                            background-repeat: no-repeat;
                            background-position: 50% 50%;
                            background-size: cover;
                        }
                    </style>
                    <input type="file" hidden id="choose-file-btn" />
                    <label for="choose-file-btn" class="btn-back-primary color-back px-2 subtitulo-font cursor-pointer"><?php echo $mudarimagem ?></label>
                </div>
                <div class="w-50 color-text d-flex flex-column justify-content-evenly align-items-center subtitulo-font d-none" id="section-username-atualizar">
                    <div class="mx-3">
                        <label for="log-username" class="form-label"><?php echo $atualpass ?></label>
                        <div class="input-group">
                            <input type="password" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border password-campo" required id="atualizar-username-pass" aria-describedby="pass-show">
                            <button class="btn d-flex btn-pass-input btn-pass-input-red" type="button" id="atualizar-username-pass-show">
                                <span class="material-symbols-rounded my-auto icon-35" id="visibility-icon-atualizar-username-pass">
                                    visibility
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="mx-3">
                        <label for="log-username" class="form-label"><?php echo $novousername ?></label>
                        <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" id="atualizar-username">
                    </div>
                    <button class="btn-back-primary color-back px-2" type="button" id="btn-alterar-username"><?php echo $alterarusername ?></button>
                </div>
                <hr class="borda-red h-75 my-auto">
                <div class="w-65 color-text d-flex flex-column justify-content-evenly align-items-center subtitulo-font" id="section-infornacao">
                    <div class="d-flex flex-column align-items-center">
                        <label for="log-username" class="form-label"><?php echo $numeroconta ?></label>
                        <input type="text" disabled class="form-control subtitulo-font w-75 bg-transparent color-text input-normal-dark-border" id="perfil-numeroconta" value="<?php echo $utilizador_data["data"]["id"] ?>">
                    </div>
                    <div class="d-flex">
                        <div class="mx-3">
                            <label for="log-username" class="form-label"><?php echo $prinome ?></label>
                            <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" id="perfil-prinome" value="<?php echo $utilizador_data["data"]["first_name"] ?>">
                        </div>
                        <div class="mx-3">
                            <label for="log-username" class="form-label"><?php echo $ultnome ?></label>
                            <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" id="perfil-ultnome" value="<?php echo $utilizador_data["data"]["last_name"] ?>">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mx-3">
                            <label for="log-username" class="form-label">Email</label>
                            <input type="email" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" id="perfil-email" value="<?php echo $utilizador_data["data"]["email"] ?>" name="<?php echo $utilizador_data["data"]["email"] ?>">
                        </div>
                        <div class="mx-3">
                            <label for="log-username" class="form-label"><?php echo $numero ?></label>
                            <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" maxlength="9" id="perfil-numero" value="<?php echo $utilizador_data["data"]["number"] ?>">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mx-3">
                            <label for="log-username" class="form-label"><?php echo $morada ?></label>
                            <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" id="perfil-morada" value="<?php echo $utilizador_data["data"]["address"] ?>">
                        </div>
                        <div class="mx-3">
                            <label for="log-username" class="form-label"><?php echo $codigopostal ?></label>
                            <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" maxlength="8" id="perfil-codigopostal" value="<?php echo $utilizador_data["data"]["postal_code"] ?>">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center w-100">
                        <div class="w-25 d-flex flex-column align-items-center">
                            <label for="log-username" class="form-label"><?php echo $membrodesde ?></label>
                            <input type="text" disabled class="form-control text-center w-75 subtitulo-font bg-transparent color-text input-normal-dark-border" id="perfil-membrodesde" value="<?php echo $utilizador_data["data"]["created_at"] ?>">
                        </div>
                        <div class="w-30 d-flex flex-column align-items-center">
                            <label for="log-username" class="form-label"><?php echo $ultatuali ?></label>
                            <input type="text" disabled class="form-control text-center w-85 subtitulo-font bg-transparent color-text input-normal-dark-border" id="perfil-ultatuali" value="<?php echo $utilizador_data["data"]["updated_at"] ?>">
                        </div>
                        <div class="w-20 d-flex flex-column align-items-center">
                            <label for="log-username" class="form-label"><?php echo $statusconta ?></label>
                            <?php
                            $status_text = ($utilizador_data["data"]["status"]) ? $normal : $bloqueado;
                            ?>
                            <input type="text" disabled class="form-control text-center w-75 subtitulo-font bg-transparent color-text input-normal-dark-border" id="perfil-statusconta" value="<?php echo $status_text ?>">
                        </div>
                    </div>
                </div>
                <div class="w-50 color-text d-flex flex-column justify-content-evenly align-items-center subtitulo-font d-none" id="section-password-atualizar">
                    <!-- <div class="mx-3">
                        <label for="log-username" class="form-label"><?php echo $atualpass ?></label>
                        <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" id="atualizar-pass-atual">
                    </div> -->
                    <div class="mx-3">
                        <label for="log-username" class="form-label"><?php echo $atualpass ?></label>
                        <div class="input-group">
                            <input type="password" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border password-campo" required id="atualizar-pass-atual" aria-describedby="pass-show">
                            <button class="btn d-flex btn-pass-input btn-pass-input-red" type="button" id="atualizar-pass-atual-show">
                                <span class="material-symbols-rounded my-auto icon-35" id="visibility-icon-atualizar-pass-atual">
                                    visibility
                                </span>
                            </button>
                        </div>
                    </div>
                    <!-- <div class="mx-3">
                        <label for="log-username" class="form-label"><?php echo $novapass ?></label>
                        <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" id="atualizar-pass-nova">
                    </div> -->
                    <div class="mx-3">
                        <label for="log-username" class="form-label"><?php echo $novapass ?></label>
                        <div class="input-group">
                            <input type="password" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border password-campo" required id="atualizar-pass-nova" aria-describedby="pass-show">
                            <button class="btn d-flex btn-pass-input btn-pass-input-red" type="button" id="atualizar-pass-nova-show">
                                <span class="material-symbols-rounded my-auto icon-35" id="visibility-icon-atualizar-pass-nova">
                                    visibility
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="mx-3">
                        <label for="log-username" class="form-label"><?php echo $confinovapass ?></label>
                        <!-- <input type="text" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border" id="atualizar-pass-confinova"> -->
                        <div class="input-group">
                            <input type="password" class="form-control subtitulo-font bg-transparent color-text input-normal-dark-border password-campo" required id="atualizar-pass-confinova" aria-describedby="pass-show">
                            <button class="btn d-flex btn-pass-input btn-pass-input-red" type="button" id="atualizar-pass-confinova-show">
                                <span class="material-symbols-rounded my-auto icon-35" id="visibility-icon-atualizar-pass-confinova">
                                    visibility
                                </span>
                            </button>
                        </div>
                    </div>
                    <button class="btn-back-primary color-back px-2" type="button" id="btn-alterar-password"><?php echo $alterarpassword ?></button>
                </div>
            </div>
            <div class="d-flex col-item flex-row justify-content-between px-5 align-items-center subtitulo-font">
                <button class="btn-vazio-borda-accent color-text px-2" data-bs-toggle="modal" data-bs-target="#apagarmodal" type="button"><?php echo $apagarconta ?></button>
                <button class="btn-back-primary color-back px-2" type="button" id="perfil_salvar"><?php echo $salvarmudanças ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="apagarmodal" tabindex="-1" aria-labelledby="apagarmodal" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $ph_confi_pass ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body texto-font py-5" id="apagar-corpo">
                <span id="apagarmensagens" class="color-accent"></span>
                <div>
                    <label for="apagar-password" class="form-label color-text"><?php echo $password ?></label>
                    <input type="password" class="form-control w-75 bg-transparent color-text input-normal-dark-border" id="apagar-password">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="botao_apagar" class="btn-vazio-borda-accent color-text px-2"><?php echo $apagarconta ?></button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#atualizar-username-pass").attr("type", "password");
    $("#atualizar-username-pass-show").on("click", function() {
        var passInput = $("#atualizar-username-pass");
        var icon = $("#visibility-icon-atualizar-username-pass");
        if (passInput.attr("type") === "password") {
            passInput.attr("type", "text");
            icon.text("visibility_off");
        } else {
            passInput.attr("type", "password");
            icon.text("visibility");
        }
    });
    $("#atualizar-pass-atual").attr("type", "password");
    $("#atualizar-pass-atual-show").on("click", function() {
        var passInput = $("#atualizar-pass-atual");
        var icon = $("#visibility-icon-atualizar-pass-atual");
        if (passInput.attr("type") === "password") {
            passInput.attr("type", "text");
            icon.text("visibility_off");
        } else {
            passInput.attr("type", "password");
            icon.text("visibility");
        }
    });
    $("#atualizar-pass-nova").attr("type", "password");
    $("#atualizar-pass-nova-show").on("click", function() {
        var passInput = $("#atualizar-pass-nova");
        var icon = $("#visibility-icon-atualizar-pass-nova");
        if (passInput.attr("type") === "password") {
            passInput.attr("type", "text");
            icon.text("visibility_off");
        } else {
            passInput.attr("type", "password");
            icon.text("visibility");
        }
    });
    $("#atualizar-pass-confinova").attr("type", "password");
    $("#atualizar-pass-confinova-show").on("click", function() {
        var passInput = $("#atualizar-pass-confinova");
        var icon = $("#visibility-icon-atualizar-pass-confinova");
        if (passInput.attr("type") === "password") {
            passInput.attr("type", "text");
            icon.text("visibility_off");
        } else {
            passInput.attr("type", "password");
            icon.text("visibility");
        }
    });
</script>