<?php
include_once "verifica-sessao.php";
$link = 'controlo/controlo.php';
require_once $link;
$controlo = new controlo();
if (isset($_POST['livro_codigo'])) {
    $livro_codigo = $_POST['livro_codigo'];
    $livro_detalhes = $controlo->listar_livro_pag($livro_codigo);
}
include "verificar_funcionario.php";
$listar_filtros = $controlo->listar_filtros();
$link2 = 'controlo/author-controlo.php';
require_once $link2;
$controlo2 = new author_controlo();
$listar_autores = $controlo2->listar_autores();
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
                    <a href="<?php echo get_link("gerirlivros") ?>" id="identidade-site" class="color-text"><?php echo $gerlivr ?></a>
                </span>
                <span class="color-text"><?php echo $detlivro ?></span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-gerir-livro px-4 pt-3">
            <div class="imagem-descricao">
                <div class="col-item s-back-1 p-3">
                    <div class="h-15">
                        <span class="titulo-font color-text"><?php echo $capa ?></span>
                    </div>
                    <div class="h-85 d-flex fex-row justify-content-around">
                        <div class="w-40 d-flex flex-column justify-content-center">
                            <div class="d-flex justify-content-center">
                                <span class="color-text subtitulo-font"><?php echo $frontal ?></span>
                                <button type="button" id="btn-remover-capa" class="color-accent d-flex border-0 d-none">
                                    <span class="material-symbols-rounded icon-25 no-fill my-auto">
                                        close
                                    </span>
                                </button>
                            </div>
                            <div class="h-95">
                                <div class="livro-f-img border-20 d-flex border-1-accent" id="imagem-capa">
                                    <input type="file" hidden id="choose-file-capa" />
                                    <label for="choose-file-capa" class="icon-40 m-auto cursor-pointer">
                                        <span class="material-symbols-rounded icon-40 color-accent no-fill" id="choose-file-capa-icon">
                                            add_circle
                                        </span>
                                    </label>
                                </div>
                                <style>
                                    <?php
                                    if (!empty($livro_detalhes['data']) && !empty($livro_detalhes['data']['fcover_url'])) {
                                        $fcover_url = $livro_detalhes['data']['fcover_url'];
                                        echo ".livro-f-img { background-image: url('libs/img/book-covers/$fcover_url'); }";
                                    }
                                    ?>.livro-f-img {
                                        width: 100%;
                                        height: 100%;
                                        overflow: hidden;
                                        background-repeat: no-repeat;
                                        background-position: 50% 50%;
                                        background-size: cover;
                                    }
                                </style>
                                <?php
                                if (!empty($livro_detalhes['data']) && !empty($livro_detalhes['data']['fcover_url'])) {
                                ?>
                                    <script>
                                        $(document).ready(function() {
                                            $("#imagem-capa").toggleClass("border-1-accent");
                                            $("#choose-file-capa-icon").toggleClass("d-none");
                                            $("#btn-remover-capa").toggleClass("d-none");
                                        });
                                    </script>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="w-40 d-flex flex-column justify-content-center">
                            <div class="d-flex justify-content-center">
                                <span class="color-text subtitulo-font"><?php echo $traseira ?></span>
                                <button type="button" id="btn-remover-contracapa" class="color-accent d-flex border-0 d-none">
                                    <span class="material-symbols-rounded icon-25 no-fill my-auto">
                                        close
                                    </span>
                                </button>
                            </div>
                            <div class="h-95">
                                <div class="livro-b-img border-20 d-flex border-1-accent" id="imagem-contracapa">
                                    <input type="file" hidden id="choose-file-contracapa" />
                                    <label for="choose-file-contracapa" class="icon-40 m-auto cursor-pointer">
                                        <span class="material-symbols-rounded icon-40 color-accent no-fill" id="choose-file-contracapa-icon">
                                            add_circle
                                        </span>
                                    </label>
                                </div>
                                <style>
                                    <?php
                                    if (!empty($livro_detalhes['data']) && !empty($livro_detalhes['data']['bcover_url'])) {
                                        $bcover_url = $livro_detalhes['data']['bcover_url'];
                                        echo ".livro-b-img { background-image: url('libs/img/book-covers/$bcover_url'); }";
                                    }
                                    ?>.livro-b-img {
                                        width: 100%;
                                        height: 100%;
                                        overflow: hidden;
                                        background-repeat: no-repeat;
                                        background-position: 50% 50%;
                                        background-size: cover;
                                    }
                                </style>
                                <?php
                                if (!empty($livro_detalhes['data']) && !empty($livro_detalhes['data']['bcover_url'])) {
                                ?>
                                    <script>
                                        $(document).ready(function() {

                                            $("#imagem-contracapa").toggleClass("border-1-accent");
                                            $("#choose-file-contracapa-icon").toggleClass("d-none");
                                            $("#btn-remover-contracapa").toggleClass("d-none");

                                        });
                                    </script>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-item s-back-1 p-3 info-autores">
                    <div class="w-100 h-15 d-flex align-items-center">
                        <span class="titulo-font color-text"><?php echo $descricao ?></span>
                        <label class="switch ms-auto">
                            <input type="checkbox" checked name="1" id="desc-textarea-change">
                            <span class="slider"></span>
                            <span class="pt-text texto-font color-back">pt</span>
                            <span class="eng-text texto-font color-back">eng</span>
                        </label>
                    </div>
                    <div class="w-100 h-85">
                        <textarea id="gerir-livro-desc_pt" class="form-control w-100 h-100 texto-font" placeholder="<?php echo $descpt ?>"><?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['descricao_pt'] : "" ?></textarea>
                        <textarea id="gerir-livro-desc_eng" class="form-control w-100 h-100 d-none texto-font" placeholder="<?php echo $desceng ?>"><?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['descricao_eng'] : "" ?></textarea>
                    </div>
                </div>
            </div>
            <div class="informacao-condicao-autor-genero">
                <div class="col-item s-back-1 p-3 info-autores">
                    <span class="color-text titulo-font"><?php echo $informacao ?></span>
                    <input type="hidden" id="gerir-livro-id" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['id'] : "" ?>">
                    <div class="d-flex flex-row flex-wrap gap-2 info-autores">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="gerir-livro-titulo" placeholder="<?php echo $titulo ?>" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['title'] : "" ?>">
                            <label for="floatingInput"><?php echo $nomelivro ?></label>
                        </div>
                        <div class="form-floating custom-select position-relative input-group" id="custom-selectlang">
                            <input type="text" class="form-control formato-select cursor-pointer" id="gerir-livro-linguagem" placeholder="<?php echo $linguagem ?>" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['language'] : "" ?>">
                            <span class="input-group-text cursor-pointer" id="select-setalang"><span class="material-symbols-rounded icon-30 color-text">keyboard_arrow_down</span></span>
                            <div id="options-containerlang" class="options-container">
                                <?php
                                foreach ($listar_filtros['linguagens'] as $lang) {
                                ?>
                                    <div class="option lang"><?php echo $lang['language'] ?></div>
                                <?php
                                }
                                ?>
                            </div>
                            <label for="floatingInput"><?php echo $linguagem ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control verificar_numero_input" id="gerir-livro-codinter" placeholder="<?php echo $codinter ?>" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['internal_code'] : "" ?>">
                            <label for="floatingInput"><?php echo $codinter ?></label>
                        </div>
                        <div class="form-floating custom-select position-relative input-group" id="custom-selecteditora">
                            <input type="text" class="form-control formato-select cursor-pointer" id="gerir-livro-editora" placeholder="<?php echo $editora ?>" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['publisher'] : "" ?>">
                            <span class="input-group-text cursor-pointer" id="select-setaeditora"><span class="material-symbols-rounded icon-30 color-text">keyboard_arrow_down</span></span>
                            <div id="options-containereditora" class="options-container">
                                <?php
                                foreach ($listar_filtros['editoras'] as $editoras) {
                                ?>
                                    <div class="option editora"><?php echo $editoras['publisher'] ?></div>
                                <?php
                                }
                                ?>
                            </div>
                            <label for="floatingInput"><?php echo $editora ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control" id="gerir-livro-datalanc" placeholder="<?php echo $datalanc ?>" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['datalanc'] : "" ?>">
                            <label for="floatingInput"><?php echo $datalanc ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control verificar_numero_input" id="gerir-livro-isbn" placeholder="<?php echo 'ISBN' ?>" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['isbn'] : "" ?>">
                            <label for="floatingInput"><?php echo 'ISBN' ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control verificar_numero_input" id="gerir-livro-numedit" placeholder="<?php echo $numedit ?>" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['edition_number'] : "" ?>">
                            <label for="floatingInput"><?php echo $numedit ?></label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control verificar_numero_input" id="gerir-livro-numpag" placeholder="<?php echo $numpag ?>" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['page_number'] : "" ?>">
                            <label for="floatingInput"><?php echo $numpag ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-item s-back-1 p-3 d-flex flex-row">
                    <div class="w-50 d-flex flex-column flex-nowrap justify-content-center align-items-center px-5">
                        <span class="color-text subtitulo-font"><?php echo $condifisi; ?></span>
                        <div class="range-slider">
                            <div class="track"></div>
                            <div class="fill"></div>
                            <input type="range" min="1" max="5" value="<?php echo (!empty($livro_detalhes['data'])) ? $livro_detalhes['data']['physical_condition'] : "3" ?>" step="1" id="gerir-livro-condicao" placeholder="<?php echo $condifisi; ?>">
                            <div class="range-labels">
                                <span>1</span>
                                <span>2</span>
                                <span>3</span>
                                <span>4</span>
                                <span>5</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-50 d-flex flex-column flex-nowrap justify-content-around align-items-center px-5">
                        <div class="color-text subtitulo-font d-flex justify-content-between w-100">
                            <?php echo $discbiblio ?>
                            <label class="switch switch-pequeno ms-auto">
                                <?php
                                $check_discbiblio = "";
                                if (!empty($livro_detalhes['data'])) {
                                    if ($livro_detalhes['data']['available'] == 1) {
                                        $check_discbiblio = "checked";
                                    }
                                }
                                ?>
                                <input type="checkbox" name="2" id="gerir-livro-discbiblio" placeholder="<?php echo $discbiblio; ?>" <?php echo $check_discbiblio ?>>
                                <span class="slider slider-pequeno"></span>
                            </label>
                        </div>
                        <div class="color-text subtitulo-font d-flex justify-content-between w-100">
                            <?php echo $disreq ?>
                            <label class="switch switch-pequeno ms-auto">
                                <?php
                                $check_disreq = "";
                                if (!empty($livro_detalhes['data'])) {
                                    if ($livro_detalhes['data']['available_req'] == 1) {
                                        $check_disreq = "checked";
                                    }
                                }
                                ?>
                                <input type="checkbox" name="3" id="gerir-livro-disreq" placeholder="<?php echo $disreq; ?>" <?php echo $check_disreq ?>>
                                <span class="slider slider-pequeno"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="autor-genero">
                    <div class="col-item s-back-1 p-3 d-flex flex-column">
                        <div class="h-10 w-100">
                            <span class="color-text titulo-font"><?php echo $autor ?></span>
                        </div>
                        <div class="input-group flex-nowrap h-10 custom-select position-relative" id="custom-selectautor">
                            <span class="input-group-text material material-symbols-rounded color-accent bg-transparent input-dark-border-icon" id="addon-wrapping">search</span>
                            <input type="text" id="gerir-livro-pesq-autor" class="pesquisa-form texto-font form-control bg-transparent color-text input-dark-border" placeholder="<?php echo $nomeautor ?>" aria-label="Username" aria-describedby="addon-wrapping">
                            <div id="options-containerautor" class="options-container">
                                <?php
                                foreach ($listar_autores['autores'] as $autor) {
                                ?>
                                    <div class="option autor" id="<?php echo $autor['id'] ?>"><?php echo $autor['first_name'] . " " . $autor['last_name'] ?></div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="h-80 d-flex flex-row flex-nowrap" id="detalhes-autor">
                            <?php
                            if (!empty($livro_detalhes['data']) && !empty($livro_detalhes['data']['author_id'])) {
                            ?>
                                <script>
                                    $(document).ready(function() {

                                        // Executar a requisição AJAX ao carregar a página
                                        $.ajax({
                                            type: "POST",
                                            url: "views/post_views/post_pesquisa_autor_gerir_livro.php",
                                            data: {
                                                author_id: <?php echo $livro_detalhes['data']['author_id'] ?>
                                            },
                                            dataType: "html",
                                            success: function(response) {
                                                // Atualizar o conteúdo da div #detalhes-autor com o resultado da requisição
                                                $("#detalhes-autor").html(response);
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                // Registrar no console qualquer erro na requisição AJAX
                                                console.log("AJAX error: " + textStatus + " : " + errorThrown);
                                            }
                                        });
                                    });
                                </script>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-item s-back-1 p-3 d-flex flex-column">
                        <div class="h-10 w-100">
                            <span class="color-text titulo-font"><?php echo $generos ?></span>
                        </div>
                        <div class="input-group flex-nowrap h-10 custom-select position-relative" id="custom-selectgenero">
                            <span class="input-group-text material material-symbols-rounded color-accent bg-transparent input-dark-border-icon" id="addon-wrapping">search</span>
                            <input type="text" id="gerir-livro-pesq-genero" class="pesquisa-form texto-font form-control bg-transparent color-text input-dark-border" placeholder="<?php echo $genero ?>" aria-label="Username" aria-describedby="addon-wrapping">
                            <div id="options-containergenero" class="options-container">
                                <?php
                                foreach ($listar_filtros['generos'] as $generos) {
                                ?>
                                    <div class="option genero" id="<?php echo $generos['genre_id'] ?>"><?php echo $generos['genre_name'] ?></div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="d-flex srcoll-div mt-2 texto-font flex-wrap justify-content-evenly row-gap-2 h-100 align-content-start" id="lista-generos">
                            <?php
                            if (!empty($livro_detalhes['data'])) {
                                $listar_generos = $controlo->listar_livro_generos($livro_detalhes['data']['id']);
                                if (!empty($listar_generos['data'])) {
                                    foreach ($listar_generos['data'] as $genero) {
                            ?>
                                        <div class="book-genres-box px-2 border-20 d-flex flex-row align-items-center" id="<?php echo $genero['genero_id'] ?>"><?php echo $genero['genero'] ?><span class="material-symbols-rounded color-accent cursor-pointer" id="retirar-genero">close</span>
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
            <div class="d-flex justify-content-end align-items-end flex-column">
                <?php
                if (empty($livro_detalhes)) {
                ?>
                    <button type="button" id="gerir-livro-botao-adicionar" class="btn-back-primary color-back p-2 rounded-circle ms-auto d-flex">
                        <span class="material-symbols-rounded icon-30 no-fill my-auto">
                            save
                        </span>
                    </button>
                <?php
                } else {
                ?>
                    <button type="button" id="gerir-livro-botao-editar" class="btn-back-primary color-back p-2 rounded-circle ms-auto d-flex">
                        <span class="material-symbols-rounded icon-30 no-fill my-auto">
                            edit
                        </span>
                    </button>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function updateSliderBackground(slider) {
            var value = $(slider).val();
            var percentage = ((value - slider.min) / (slider.max - slider.min)) * 100;
            $(slider).siblings('.fill').css('width', percentage + '%');
        }
        $('input[type="range"]').each(function() {
            updateSliderBackground(this);
        });
        $('input[type="range"]').on('input', function() {
            updateSliderBackground(this);
        });

        $('input[type="file"]').on("change", function() {
            var fileInput = this;
            var fileInputId = $(fileInput).attr('id');
            var file = fileInput.files[0];

            // Verifica se um arquivo foi selecionado
            if (file) {
                var formData = new FormData();
                formData.append("file", file);

                // Envia o arquivo para o servidor usando AJAX
                $.ajax({
                    url: "views/post_views/img_livro.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Verifica se a resposta não está vazia
                        response = response.trim();
                        if (response) {
                            // Atualiza o background-image após o upload bem-sucedido
                            var img = response;
                            if (fileInputId === 'choose-file-capa') {
                                $("#imagem-capa").css("background-image", "url('" + img + "')");
                                $("#imagem-capa").toggleClass("border-1-accent");
                                $("#choose-file-capa-icon").toggleClass("d-none");
                                $("#btn-remover-capa").toggleClass("d-none");
                            } else if (fileInputId === 'choose-file-contracapa') {
                                $("#imagem-contracapa").css("background-image", "url('" + img + "')");
                                $("#imagem-contracapa").toggleClass("border-1-accent");
                                $("#choose-file-contracapa-icon").toggleClass("d-none");
                                $("#btn-remover-contracapa").toggleClass("d-none");
                            }
                            //alert(response);
                        } else {
                            console.error("Erro ao carregar a imagem");
                        }
                    },
                    error: function() {
                        console.error("Erro na requisição AJAX");
                    },
                });
            }
        });
        $('#btn-remover-capa').on('click', function(e) {
            $("#imagem-capa").css("background-image", "");
            $("#imagem-capa").toggleClass("border-1-accent");
            $("#choose-file-capa-icon").toggleClass("d-none");
            $("#btn-remover-capa").toggleClass("d-none");
        });
        $('#btn-remover-contracapa').on('click', function(e) {
            $("#imagem-contracapa").css("background-image", "");
            $("#imagem-contracapa").toggleClass("border-1-accent");
            $("#choose-file-contracapa-icon").toggleClass("d-none");
            $("#btn-remover-contracapa").toggleClass("d-none");
        });
        $('#desc-textarea-change').change(function() {
            $("#gerir-livro-desc_pt").toggleClass("d-none");
            $("#gerir-livro-desc_eng").toggleClass("d-none");
        });

        $('input').addClass('texto-font');
        $('select').addClass('texto-font');
        $('input').addClass('color-text');
        $('select').addClass('color-text');
        $('textarea').addClass('color-text');
        $('label').addClass('color-text');
        $('.info-autores div').addClass('m-auto');

        var $input = $('#gerir-livro-linguagem');
        var $optionsContainer = $('#options-containerlang');
        var $options = $('.lang');

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
            if (!$(event.target).closest('#custom-selectlang').length) {
                $optionsContainer.hide();
            }
        });
        $('#select-setalang').on('click', function() {
            $('#gerir-livro-linguagem').focus();
        });

        var $inputeditora = $('#gerir-livro-editora');
        var $optionsContainereditora = $('#options-containereditora');
        var $optionseditora = $('.editora');

        // Mostrar as opções ao focar no input
        $inputeditora.on('focus', function() {
            $optionsContainereditora.show();
        });

        // Preencher o input com a opção clicada
        $optionseditora.on('click', function() {
            var optionTexteditora = $(this).text();
            var currentTexteditora = $inputeditora.val();

            // Inserir o texto da opção no input
            $inputeditora.val(optionTexteditora);

            // Fechar o menu de opções
            $optionsContainereditora.hide();
        });

        // Esconder as opções ao clicar fora
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#custom-selecteditora').length) {
                $optionsContainereditora.hide();
            }
        });
        $('#select-setaeditora').on('click', function() {
            $('#gerir-livro-editora').focus();
        });
        $("#gerir-livro-botao-adicionar").click(function() {
            var titulo = $("#gerir-livro-titulo").val();
            var linguagem = $("#gerir-livro-linguagem").val();
            var codinter = $("#gerir-livro-codinter").val();
            var editora = $("#gerir-livro-editora").val();
            var datalanc = $("#gerir-livro-datalanc").val();
            var isbn = $("#gerir-livro-isbn").val();
            var numedit = $("#gerir-livro-numedit").val();
            var numpag = $("#gerir-livro-numpag").val();
            var condicao = $("#gerir-livro-condicao").val();
            var discbiblio = $("#gerir-livro-discbiblio").is(":checked") ? 1 : 0;
            var disreq = $("#gerir-livro-disreq").is(":checked") ? 1 : 0;
            var desc_pt = $("#gerir-livro-desc_pt").val();
            var desc_eng = $("#gerir-livro-desc_eng").val();

            var imagem_capa = $("#imagem-capa").css("background-image");
            var imagem_contracapa = $("#imagem-contracapa").css("background-image");
            var caminho_capa = imagem_capa.match(/^url\(["']?(.+?)["']?\)$/);
            var caminho_contracapa = imagem_contracapa.match(/^url\(["']?(.+?)["']?\)$/);

            var photo_url_capa = "";
            var photo_url_contracapa = "";

            if (caminho_capa && caminho_capa[1]) {
                var url = caminho_capa[1];
                var urlObj = new URL(url);
                photo_url_capa = urlObj.pathname.split("/").pop();
            }
            if (caminho_contracapa && caminho_contracapa[1]) {
                var url = caminho_contracapa[1];
                var urlObj = new URL(url);
                photo_url_contracapa = urlObj.pathname.split("/").pop();
            }

            // Coletar IDs das divs dentro de #lista-generos e transformar em JSON
            var idsArray = [];
            $('.book-genres-box').each(function() {
                var id = parseInt($(this).attr('id'), 10); // Pega o valor do atributo id e converte para número
                if (!isNaN(id)) {
                    idsArray.push(id); // Adiciona o ID ao array, se for um número válido
                }
            });

            var autor_id = $("#gerir-livro-autor").length ? $("#gerir-livro-autor").val() : null;

            console.log(idsArray); // Verifique o conteúdo do array antes de converter
            var idsJson = JSON.stringify(idsArray);
            console.log(idsJson); // Verifique o JSON resultante
            $.ajax({
                type: "POST",
                url: "views/post_views/post_adicionar_livro.php",
                data: {
                    titulo: titulo,
                    linguagem: linguagem,
                    codinter: codinter,
                    editora: editora,
                    datalanc: datalanc,
                    isbn: isbn,
                    numedit: numedit,
                    numpag: numpag,
                    condicao: condicao,
                    discbiblio: discbiblio,
                    disreq: disreq,
                    desc_pt: desc_pt,
                    desc_eng: desc_eng,
                    photo_url_capa: photo_url_capa,
                    photo_url_contracapa: photo_url_contracapa,
                    generos: idsJson,
                    autor_id: autor_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Cria um formulário dinamicamente
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': '<?php echo get_link("gerirlivros") ?>'
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
        $(document).ready(function() {
            var $inputgenero = $('#gerir-livro-pesq-genero');
            var $optionsContainergenero = $('#options-containergenero');
            var $optionsgenero = $('.genero');

            // Mostrar as opções ao focar no input
            $inputgenero.on('focus', function() {
                $optionsContainergenero.show();
            });

            // Filtrar opções com base no que está sendo digitado
            $inputgenero.on('input', function() {
                var filtergenero = $(this).val().toLowerCase();
                $optionsgenero.each(function() {
                    if ($(this).text().toLowerCase().includes(filtergenero)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Função para adicionar gêneros e evitar duplicados
            function adicionarGenero(optionTextgenero, generoId) {
                var exists = false;

                // Verificar se já existe uma div com o mesmo ID
                $('.book-genres-box').each(function() {
                    if ($(this).attr('id') === generoId) {
                        exists = true;
                        return false; // Interrompe o loop
                    }
                });

                // Se não existir, adicionar a nova div
                if (!exists) {
                    $('#lista-generos').append('<div class="book-genres-box px-2 border-20 d-flex flex-row align-items-center" id="' + generoId + '">' + optionTextgenero + '<span class="material-symbols-rounded color-accent cursor-pointer notranslate" id="retirar-genero">close</span></div>');
                }
            }

            // Preencher o input com a opção clicada
            $optionsgenero.on('click', function() {
                var optionTextgenero = $(this).text().trim();
                var generoId = $(this).attr('id'); // Pega o ID do elemento clicado

                adicionarGenero(optionTextgenero, generoId);

                // Fechar o menu de opções
                $optionsContainergenero.hide();
            });

            // Adicionar funcionalidade ao botão 'retirar-genero' já existente
            $(document).on('click', '#retirar-genero', function() {
                // Remove a div pai (a div com a classe 'book-genres-box')
                $(this).closest('.book-genres-box').remove();
            });

            // Esconder as opções ao clicar fora
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#custom-selectgenero').length) {
                    $optionsContainergenero.hide();
                }
            });

            // Evitar duplicação para divs carregadas ao iniciar a página
            $('.book-genres-box').each(function() {
                var optionTextgenero = $(this).text().replace('close', '').trim();
                var generoId = $(this).attr('id');
                adicionarGenero(optionTextgenero, generoId);
            });
        });
        var $inputautor = $('#gerir-livro-pesq-autor');
        var $optionsContainerautor = $('#options-containerautor');
        var $optionsautor = $('.autor');

        // Mostrar as opções ao focar no input
        $inputautor.on('focus', function() {
            $optionsContainerautor.show();
        });

        // Filtrar opções com base no que está sendo digitado
        $inputautor.on('input', function() {
            var filterautor = $(this).val().toLowerCase();
            $optionsautor.each(function() {
                if ($(this).text().toLowerCase().includes(filterautor)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        $optionsautor.on('click', function() {
            var optionTextautor = $(this).text();
            var author_id = $(this).attr('id'); // Pega o ID do elemento clicado
            var exists = false;

            $.ajax({
                type: "POST",
                url: "views/post_views/post_pesquisa_autor_gerir_livro.php",
                data: {
                    author_id: author_id,
                },
                dataType: "html",
                success: function(response) {
                    $("#detalhes-autor").html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("AJAX error: " + textStatus + " : " + errorThrown);
                },
            });

            // Fechar o menu de opções
            $optionsContainerautor.hide();
        });
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#custom-selectautor').length) {
                $optionsContainerautor.hide();
            }
        });
        $(document).on('click', '#retirar-autor', function() {
            // Remove a div pai (a div com a classe 'book-genres-box')
            $('#detalhes-autor').empty();
        });
    });
</script>
<script>


</script>