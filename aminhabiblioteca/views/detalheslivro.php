<?php
include_once "verifica-sessao.php";

if (isset($_POST['livro_codigo'])) {
    $livro_codigo = $_POST['livro_codigo'];
} else {
    header('Location: ' . get_link("gerirlivros"));
}
include "verificar_funcionario.php";
$link = 'controlo/controlo.php';
require_once $link;
$controlo = new controlo();
$listar_livro = $controlo->listar_livro_pag($livro_codigo);
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
// $user_id = $_SESSION['user_dados']['id'];
// $user_status =  $_SESSION['user_dados']['status'];
$req_user_id = $controlo->verifica_livro_requisitado($listar_livro['data']['id']);
$res_user_id = $controlo->verifica_livro_reservado($listar_livro['data']['id']);
?>
<div id="alert-container" class="fade show position-absolute mt-4 start-50 translate-middle-x top-1 w-auto" style="z-index: 1;"></div> <!-- Container para os alerts -->
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary">A Minha Biblioteca / </a>
                    <a href="<?php echo get_link("gerirlivros") ?>" id="identidade-site" class="color-text"><?php echo $listalivros ?></a>
                </span>
                <span class="color-text"><?php echo $detlivro ?></span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-template-livro px-5 pt-3">
            <div class="grid-book-info">
                <div class="grid-book-cover-details ">
                    <div class="col-item p-4 s-back-1 position-relative">
                        <div class="position-relative h-100">
                            <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $listar_livro['data']['fcover_url'] ?>" id="book-fcover" class="cover-book border-20 book-fcover">
                            <?php
                            if (!empty($listar_livro['data']['bcover_url'])) {
                            ?>
                                <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $listar_livro['data']['bcover_url'] ?>" id="book-bcover" class="cover-book border-20 book-bcover cover-hidden">
                            <?php
                            }
                            ?>

                        </div>
                        <?php
                        if (!empty($listar_livro['data']['bcover_url'])) {
                        ?>
                            <button type="button" id="book-cover-btn" class="position-absolute bottom-0 end-0 p-0 botao-circ-30 btn-flip">
                                <span class="material-symbols-outlined icon-30 color-accent">
                                    trending_flat
                                </span>
                            </button>
                        <?php
                        }
                        ?>

                    </div>
                    <div class="col-item s-back-2 p-4 d-flex flex-column">
                        <div class="text-center w-100 titulo-grande-font color-text"><?php echo $listar_livro['data']['title'] ?></div>
                        <div class="d-flex flex-row h-100">
                            <div class="w-50 subtitulo-font color-text d-flex flex-column justify-content-evenly align-items-center">
                                <?php
                                if (!empty($listar_livro['data']['release_date'])) {
                                    echo '<span>';
                                    echo $datalanc . ': ' . $listar_livro['data']['release_date'];
                                    echo '</span>';
                                }
                                ?>
                                <span>
                                    <?php echo $numedit . ': ' . $listar_livro['data']['edition_number'] ?>
                                </span>
                                <?php
                                if (!empty($listar_livro['data']['publisher'])) {
                                    echo '<span>';
                                    echo $editora . ': ' . $listar_livro['data']['publisher'];
                                    echo '</span>';
                                }
                                ?>
                                <span>
                                    <?php echo $linguagem . ': ' . $listar_livro['data']['language'] ?>
                                </span>
                                <?php
                                if (!empty($listar_livro['data']['page_number'])) {
                                    echo '<span>';
                                    echo $numpag . ': ' . $listar_livro['data']['page_number'];
                                    echo '</span>';
                                }
                                ?>
                            </div>
                            <hr class="borda-red">
                            <div class="w-50 subtitulo-font color-text d-flex flex-column justify-content-evenly align-items-center">
                                <?php
                                if (!empty($listar_livro['data']['isbn'])) {
                                    echo '<span>';
                                    echo 'ISBN: ' . $listar_livro['data']['isbn'];
                                    echo '</span>';
                                }
                                ?>
                                <span>
                                    <?php echo $codinter . ': ' . $listar_livro['data']['internal_code'] ?>
                                </span>
                                <span>
                                    <?php
                                    $condic = $condifisi . ":";
                                    if ($listar_livro['data']['physical_condition'] == 5) {
                                        $condic .= " " . $condinovo;
                                    }
                                    if ($listar_livro['data']['physical_condition'] == 4) {
                                        $condic .= " " . $condibomest;
                                    }
                                    if ($listar_livro['data']['physical_condition'] == 3) {
                                        $condic .= " " . $condiaceitavel;
                                    }
                                    if ($listar_livro['data']['physical_condition'] == 2) {
                                        $condic .= " " . $condidesgastado;
                                    }
                                    if ($listar_livro['data']['physical_condition'] == 1) {
                                        $condic .= " " . $condimuitodesgas;
                                    }
                                    if ($listar_livro['data']['physical_condition'] == 0) {
                                        $condic .= " " . $condimauestado;
                                    }
                                    echo $condic;
                                    ?>
                                </span>
                                <span>
                                    <?php
                                    if ($listar_livro['data']['available'] == 1 && !$req_user_id) {
                                        echo $discbiblio;
                                    }
                                    if ($listar_livro['data']['available'] == 0 || $req_user_id) {
                                        echo $indiscbiblio;
                                    }
                                    ?>
                                </span>
                                <span>
                                    <?php
                                    if ($listar_livro['data']['available_req'] == 1) {
                                        echo $disreq;
                                    }
                                    if ($listar_livro['data']['available_req'] == 0) {
                                        echo $disclocal;
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div></div>
                <div class="col-item s-back-1 w-100 py-3 px-4 color-text d-flex flex-column">
                    <span class="titulo-font"><?php echo $descricao ?></span>
                    <div class="w-100 texto-font srcoll-div mt-2">
                        <?php echo $listar_livro['data']['descricao'] ?>
                    </div>
                </div>
            </div>
            <div class="grid-book-extras">
                <div class="col-item s-back-1 color-text p-3">
                    <?php
                    if (!empty($listar_livro['data']['author_id'])) {
                    ?>
                        <a class="text-decoration-none" href="<?php echo get_link("ola") ?>">
                            <div class="h-100 d-flex flex-column align-items-center justify-content-evenly color-text text-decoration-none">
                                <span class="titulo-font"><?php echo $oautor ?></span>
                                <img class="img-book-author border-20" src="<?php echo get_link("") ?>libs/img/author-pics/<?php echo $listar_livro['data']['photo_url'] ?>" alt="img">
                                <span class="subtitulo-font"><?php echo $listar_livro['data']['first_name'] . " " . $listar_livro['data']['last_name']; ?></span>
                            </div>
                        </a>
                    <?php
                    } else {
                    ?>
                        <div class="h-100 d-flex flex-column color-text text-decoration-none">
                            <span class="titulo-font"><?php echo $oautor ?></span>
                            <span class="texto-font"><?php echo $nenhumautor ?></span>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-item color-text d-flex flex-column s-back-2 p-4">
                    <span class="titulo-font"><?php echo $generos ?></span>
                    <div class="d-flex srcoll-div mt-2 texto-font flex-wrap justify-content-evenly row-gap-2">
                        <?php
                        $listar_generos = $controlo->listar_livro_generos($listar_livro['data']['id']);
                        if (!empty($listar_generos['data'])) {
                            foreach ($listar_generos['data'] as $genero) {
                        ?>
                                <div class="book-genres-box px-2 border-20">
                                    <?php echo $genero['genero'] ?>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <span><?php echo $nenhumgereno ?></span>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="d-flex justify-content-evenly align-items-center">
                    <!-- <button class="rounded-circle btn-back-primary color-back botao-circ-50 p-0" id="btn-favorito" type="button">
                        <?php

                        $is_favorite = $controlo->verificar_favorito($user_id, $listar_livro['data']['id']);

                        if ($is_favorite) {
                        ?>
                            <span class="icon-30 material-symbols-rounded" id="<?php echo $listar_livro['data']['id'] ?>">
                                bookmark_added
                            </span>
                        <?php
                        } else {
                        ?>
                            <span class="icon-30 material-symbols-rounded no-fill" id="<?php echo $listar_livro['data']['id'] ?>">
                                bookmark
                            </span>
                        <?php
                        }
                        ?>
                    </button>
                    <?php
                    if (!$req_user_id || $req_user_id == $user_id) {
                    ?>
                        <button id="btn-request" <?php echo ($req_user_id == $user_id) ? 'disabled' : ''; ?> class=" btn-back-primary subtitulo-font color-back" type="button">
                            <span id="<?php echo $listar_livro['data']['id'] ?>"><?php echo $requisitar; ?></span>
                        </button>
                    <?php
                    } elseif ($req_user_id) {
                        $disabled = "";
                        if ($res_user_id !== null) {
                            foreach ($res_user_id as $linha) {
                                // Verifica se a chave 'res_user_id' existe em cada $linha
                                if (isset($linha['res_user_id']) && $linha['res_user_id'] == $user_id) {
                                    $disabled = "disabled";
                                    break; // Se encontrado, não há necessidade de continuar a iteração
                                }
                            }
                        }
                    ?>
                        <button id="btn-reserve" <?php echo $disabled ?> class=" btn-back-primary subtitulo-font color-back" type="button">
                            <span id="<?php echo $listar_livro['data']['id'] ?>"><?php echo $reservar ?></span>
                        </button>
                    <?php
                    }
                    ?> -->
                    <form action="<?php echo get_link("gerirlivro"); ?>" method="post">
                        <input type="hidden" name="livro_id" value="<?php echo $listar_livro['data']['id'] ?>">
                        <input type="hidden" name="livro_codigo" value="<?php echo $listar_livro['data']['internal_code'] ?>">
                        <button type="submit" class="btn-back-primary color-back p-2 rounded-circle ms-auto d-flex">
                            <span class="material-symbols-rounded icon-30 no-fill my-auto">
                                edit
                            </span>
                        </button>
                    </form>
                    <button type="button" id="detalhes_livro_botao_apagar" class="btn-back-primary color-back p-2 rounded-circle d-flex ms-2">
                        <span class="material-symbols-rounded icon-30 no-fill my-auto">
                            delete
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#detalhes_livro_botao_apagar").click(function() {
            var livro_id = $('input[name="livro_id"]').val();
            $.ajax({
                type: "POST",
                url: "views/post_views/post_apagar_livro.php",
                data: {
                    livro_id: livro_id,
                },
                dataType: "json", // A resposta agora é JSON
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
    });
</script>
<!-- <script>
    $("#btn-favorito").on("click", function() {


        var btn_span = $(this).find("span");
        var bookid = btn_span.attr("id");
        var span_content = btn_span.text().trim(); // Conteúdo do span (trim() para remover espaços em branco)

        var url;
        var postData;

        // Verifica o conteúdo do span
        if (span_content === "bookmark") {
            url = "<?php echo get_link(''); ?>views/post_views/post_adicionar_favorito.php";
            postData = {
                bookid: bookid
            };
        } else if (span_content === "bookmark_added") {
            url = "<?php echo get_link(''); ?>views/post_views/post_retirar_favorito.php";
            postData = {
                bookid: bookid
            }; // Incluir dados adicionais se necessário para 'post_diferente.php'
        } else {
            // Se não corresponder a nenhum dos casos esperados
            console.error("Conteúdo do span não reconhecido:", span_content);
            return; // Saída da função, pois não há ação definida
        }

        // Realiza o AJAX conforme a URL e dados determinados
        $.ajax({
            type: "POST",
            url: url,
            data: postData,
            dataType: "html",
            success: function(response) {
                // Atualiza o conteúdo do botão com a resposta recebida
                $("#btn-favorito").html(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });
    $("#btn-request").on("click", function() {
        var btn_span = $(this).find("span");
        var bookid = btn_span.attr("id");
        var available = <?php echo $listar_livro['data']['available'] ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo get_link(''); ?>views/post_views/post_requisitar_livro.php",
            data: {
                bookid: bookid,
                available: available
            },
            dataType: "html",
            success: function(response) {
                $("#alert-container").html(response);
            },
        });
    });
    $("#btn-reserve").on("click", function() {
        var btn_span = $(this).find("span");
        var bookid = btn_span.attr("id");
        $.ajax({
            type: "POST",
            url: "<?php echo get_link(''); ?>views/post_views/post_reservar_livro.php",
            data: {
                bookid: bookid,
            },
            dataType: "html",
            success: function(response) {
                $("#alert-container").html(response);
            },
        });
    });
</script> -->