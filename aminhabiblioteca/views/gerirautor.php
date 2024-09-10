<?php
include_once "verifica-sessao.php";
$link = 'controlo/author-controlo.php';
require_once $link;
$controlo = new author_controlo();
if (isset($_POST['autor_id'])) {
    $autor_id = $_POST['autor_id'];
    $autor_detalhes = $controlo->listar_autor_pag_gerir($autor_id);
}
include "verificar_funcionario.php";
$listar_nacionalidades = $controlo->listar_nacionalidades();
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
                    <a href="<?php echo get_link("gerirautores") ?>" id="identidade-site" class="color-text"><?php echo $gerauto ?></a>
                </span>
                <span class="color-text"><?php echo $detautor ?></span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-gerir-autor px-5 pt-3">
            <div class="imagem-informacao">
                <div class="col-item s-back-1 p-3">
                    <div class="h-85 d-flex justify-content-center align-items-center">
                        <div class="autor-img border-20" id="imagem-perfil"></div>
                        <style>
                            .autor-img {
                                width: 95%;
                                height: 100%;
                                overflow: hidden;
                                background-image: url('libs/img/author-pics/<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['photo_url'] : "" ?>');
                                background-repeat: no-repeat;
                                background-position: 50% 50%;
                                background-size: cover;
                            }
                        </style>
                    </div>
                    <div class="h-15 d-flex justify-content-center align-items-center">
                        <input type="file" hidden id="choose-file-btn-autor" />
                        <label for="choose-file-btn-autor" class="btn-back-primary color-back px-2 subtitulo-font cursor-pointer">
                            <?php echo (!empty($autor_detalhes)) ? $mudarimagem : $adicionarimagem ?>
                        </label>
                    </div>
                </div>
                <div class="col-item s-back-1 p-3">
                    <span class="color-text titulo-font"><?php echo $informacao ?></span>
                    <input type="hidden" id="gerir-autor-id" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['id'] : "" ?>">
                    <div class="d-flex flex-row flex-wrap gap-2 info-autores">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-autor-prinome" placeholder="<?php echo $prinome ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['first_name'] : "" ?>">
                            <label for="floatingInput"><?php echo $prinome ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-autor-ultnome" placeholder="<?php echo $ultnome ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['last_name'] : "" ?>">
                            <label for="floatingInput"><?php echo $ultnome ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control" id="gerir-autor-datanasc" placeholder="<?php echo $datanasc ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['birth_date'] : "" ?>">
                            <label for="floatingInput"><?php echo $datanasc ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control" id="gerir-autor-datamorte" placeholder="<?php echo $datamorte ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['death_date'] : "" ?>">
                            <label for="floatingInput"><?php echo $datamorte ?></label>
                        </div>
                        <div class="form-floating custom-select position-relative input-group">
                            <input type="text" class="form-control formato-select cursor-pointer" id="gerir-autor-nacionalidade" placeholder="Selecione ou digite..." value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['nacionality'] : "" ?>">
                            <span class="input-group-text cursor-pointer" id="select-seta"><span class="material-symbols-rounded icon-30 color-text">keyboard_arrow_down</span></span>
                            <div id="options-container" class="options-container">
                                <?php
                                foreach ($listar_nacionalidades['nacionalidades'] as $nacionalidade) {
                                ?>
                                    <div class="option"><?php echo $nacionalidade['nacionality'] ?></div>
                                <?php
                                }
                                ?>
                            </div>
                            <label for="floatingInput"><?php echo $nacio ?></label>
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-autor-websitepessoal" placeholder="<?php echo $sitepessoallink ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['personal_site'] : "" ?>">
                            <label for="floatingInput"><?php echo $sitepessoallink ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-autor-wiki" placeholder="<?php echo $paginawikilink ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['wiki_page'] : "" ?>">
                            <label for="floatingInput"><?php echo $paginawikilink ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-autor-facebook" placeholder="<?php echo $facebooklink ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['facebook_link'] : "" ?>">
                            <label for="floatingInput"><?php echo $facebooklink ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-autor-twitter" placeholder="<?php echo $twitterlink ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['twitter_link'] : "" ?>">
                            <label for="floatingInput"><?php echo $twitterlink ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="" class="form-control" id="gerir-autor-instagram" placeholder="<?php echo $instagramlink ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['instagram_link'] : "" ?>">
                            <label for="floatingInput"><?php echo $instagramlink ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-autor-reddit" placeholder="<?php echo $redditlink ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['reddit_link'] : "" ?>">
                            <label for="floatingInput"><?php echo $redditlink ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-autor-tiktok" placeholder="<?php echo $tiktoklink ?>" value="<?php echo (!empty($autor_detalhes['data'])) ? $autor_detalhes['data']['tiktok_link'] : "" ?>">
                            <label for="floatingInput"><?php echo $tiktoklink ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="comentarios-autor">
                <div></div>
                <div class="col-item s-back-1 p-3">
                    <div class="h-15">
                        <span class="color-text titulo-font"><?php echo $descricao ?></span>
                    </div>
                    <div class="d-flex flex-row flex-wrap gapx-2 info-autores h-85">
                        <div class=" h-100">
                            <textarea class="form-control h-100" id="gerir-autor-desc_pt" placeholder="<?php echo $descpt ?>" id=""><?php echo (!empty($autor_detalhes['data'])) ? htmlspecialchars($autor_detalhes['data']['descricao_pt']) : "" ?></textarea>
                        </div>
                        <div class="h-100">
                            <textarea class="form-control h-100" id="gerir-autor-desc_eng" placeholder="<?php echo $desceng ?>" id=""><?php echo (!empty($autor_detalhes['data'])) ? htmlspecialchars($autor_detalhes['data']['descricao_eng']) : "" ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-end">
                    <?php
                    if (!empty($autor_detalhes)) {
                    ?>
                        <button type="button" id="gerir-autor-botao-editar" class="btn-back-primary color-back p-2 rounded-circle ms-auto d-flex">
                            <span class="material-symbols-rounded icon-30 no-fill my-auto">
                                edit
                            </span>
                        </button>
                    <?php
                    } else {
                    ?>
                        <button type="button" id="gerir-autor-botao-adicionar" class="btn-back-primary color-back p-2 rounded-circle ms-auto d-flex">
                            <span class="material-symbols-rounded icon-30 no-fill my-auto">
                                save
                            </span>
                        </button>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('input').addClass('texto-font');
        $('select').addClass('texto-font');
        $('input').addClass('color-text');
        $('select').addClass('color-text');
        $('textarea').addClass('color-text');
        $('label').addClass('color-text');
        $('.info-autores div').addClass('m-auto');
        var $input = $('#gerir-autor-nacionalidade');
        var $optionsContainer = $('#options-container');
        var $options = $('.option');

        // Mostrar as opções ao focar no input
        $input.on('focus', function() {
            $optionsContainer.show();
        });

        // Preencher o input com a opção clicada
        $options.on('click', function() {
            var optionText = $(this).text();
            var currentText = $input.val();

            // Inserir o texto da opção no input
            $input.val(optionText);

            // Fechar o menu de opções
            $optionsContainer.hide();
        });

        // Esconder as opções ao clicar fora
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.custom-select').length) {
                $optionsContainer.hide();
            }
        });
        $('#select-seta').on('click', function() {
            $('#gerir-autor-nacionalidade').focus();
        });
        $("#gerir-autor-botao-adicionar").click(function() {
            var prinome = $("#gerir-autor-prinome").val();
            var ultnome = $("#gerir-autor-ultnome").val();
            var datanasc = $("#gerir-autor-datanasc").val();
            var datamorte = $("#gerir-autor-datamorte").val();
            var nacionalidade = $("#gerir-autor-nacionalidade").val();
            var websitepessoal = $("#gerir-autor-websitepessoal").val();
            var wiki = $("#gerir-autor-wiki").val();
            var facebook = $("#gerir-autor-facebook").val();
            var twitter = $("#gerir-autor-twitter").val();
            var instagram = $("#gerir-autor-instagram").val();
            var reddit = $("#gerir-autor-reddit").val();
            var tiktok = $("#gerir-autor-tiktok").val();
            var desc_pt = $("#gerir-autor-desc_pt").val();
            var desc_eng = $("#gerir-autor-desc_eng").val();

            var imagem_fundo = $("#imagem-perfil").css("background-image");
            var caminho = imagem_fundo.match(/^url\(["']?(.+?)["']?\)$/);
            if (caminho && caminho[1]) {
                var url = caminho[1];
                var urlObj = new URL(url);
                var photo_url = urlObj.pathname.split("/").pop();
                //alert("Nome do Arquivo da Imagem de Fundo: " + photo_url);
            }
            $.ajax({
                type: "POST",
                url: "views/post_views/post_adicionar_autor.php",
                data: {
                    prinome: prinome,
                    ultnome: ultnome,
                    datanasc: datanasc,
                    datamorte: datamorte,
                    nacionalidade: nacionalidade,
                    websitepessoal: websitepessoal,
                    wiki: wiki,
                    facebook: facebook,
                    twitter: twitter,
                    instagram: instagram,
                    reddit: reddit,
                    tiktok: tiktok,
                    desc_pt: desc_pt,
                    desc_eng: desc_eng,
                    photo_url: photo_url,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Cria um formulário dinamicamente
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': '<?php echo get_link("gerirautores") ?>'
                        });

                        // Adiciona um campo hidden para a mensagem
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': 'mensagem_post',
                            'value': response.message
                        }));

                        // Adiciona o formulário ao body e envia
                        $('body').append(form);
                        form.submit();
                    } else {
                        // Se não for sucesso, podes mostrar uma mensagem de erro
                        $("#alert-container").html(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                    }
                },
            });
        });
    });
</script>