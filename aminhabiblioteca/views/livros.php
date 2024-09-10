<?php
include_once "verifica-sessao.php";
$link = 'controlo/controlo.php';
require_once $link;
$controlo = new controlo();
$listar_filtros = $controlo->listar_filtros();

?>
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary">A Minha Biblioteca / </a>
                    <a href="<?php echo get_link("livros") ?>" id="identidade-site" class="color-text"><?php echo $listalivros ?></a>
                </span>
                <span class="color-text"><?php echo $proclivro ?></span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-livros px-5 pt-3">
            <div class="s-back-2 col-item d-flex flex-column position-relative px-4 justify-content-center">
                <div class="position-absolute top-0 subtitulo-font color-back filtros-dark">
                    <?php echo $filtros ?>
                    <span class="material-symbols-rounded icon-25 ps-1">
                        filter_list
                    </span>
                </div>
                <div class="d-flex flex-row mb-2">
                    <div class="input-group flex-nowrap me-5">
                        <span class="input-group-text material material-symbols-rounded color-accent bg-transparent input-dark-border-icon" id="addon-wrapping">search</span>
                        <input type="text" id="pesquisa-booknome" class="pesquisa-form texto-font form-control bg-transparent color-text input-dark-border" placeholder="<?php echo $nomelivro ?>" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap ms-5">
                        <span class="input-group-text material material-symbols-rounded color-accent bg-transparent input-dark-border-icon" id="addon-wrapping">search</span>
                        <input type="text" id="pesquisa-authornome" class="pesquisa-form texto-font form-control bg-transparent color-text input-dark-border" placeholder="<?php echo $nomeautor ?>" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-between mt-2 align-items-center">
                    <div class="form-floating select-input">
                        <select class="pesquisa-form form-select color-text" id="pesquisa-genero" aria-label="Floating label select example">
                            <option selected value="all"><?php echo $todos ?></option>
                            <?php
                            foreach ($listar_filtros['generos'] as $genero) {
                            ?>
                                <option class="texto-font" value="<?php echo $genero['genre_id'] ?>"><?php echo $genero['genre_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <label for="pesquisa-genero" class="texto-pequeno-font color-accent"><?php echo $generos ?></label>
                    </div>
                    <div class="form-floating select-input">
                        <select class="pesquisa-form form-select color-text" id="pesquisa-linguagem" aria-label="Floating label select example">
                            <option selected value="all"><?php echo $todos ?></option>
                            <?php
                            foreach ($listar_filtros['linguagens'] as $ling) {
                            ?>
                                <option class="texto-font" value="<?php echo $ling['language'] ?>"><?php echo $ling['language'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <label for="pesquisa-liguagem" class="texto-pequeno-font color-accent"><?php echo $linguagem ?></label>
                    </div>
                    <div class="form-floating select-input">
                        <select class="pesquisa-form form-select color-text" id="pesquisa-editora" aria-label="Floating label select example">
                            <option selected value="all"><?php echo $todos ?></option>
                            <?php
                            foreach ($listar_filtros['editoras'] as $edit) {
                            ?>
                                <option class="texto-font" value="<?php echo $edit['publisher'] ?>"><?php echo $edit['publisher'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <label for="pesquisa-editora" class="texto-pequeno-font color-accent"><?php echo $editora ?></label>
                    </div>
                    <div class="d-flex flex-row align-items-center">
                        <span class="subtitulo-font color-text"><?php echo $disponibilidade . ": " ?></span>
                        <div class="form-check check-form d-flex flex-row align-items-center mx-2">
                            <input class="pesquisa-form pesquisa-check form-check-input check-form-box me-1 checkbox-20" type="checkbox" value="" id="pesquisa-dispo">
                            <label class="texto-font color-text" for="log-manter-sessao">
                                <?php echo $disponivel ?>
                            </label>
                        </div>
                        <div class="form-check check-form d-flex flex-row align-items-center mx-2">
                            <input class="pesquisa-form pesquisa-check form-check-input check-form-box me-1 checkbox-20" type="checkbox" value="" id="pesquisa-indispo">
                            <label class="texto-font color-text" for="log-manter-sessao">
                                <?php echo $Indisponivel ?>
                            </label>
                        </div>
                        <div class="form-check check-form d-flex flex-row align-items-center mx-2">
                            <input class="pesquisa-form pesquisa-check form-check-input check-form-box me-1 checkbox-20" type="checkbox" value="" id="pesquisa-localcons">
                            <label class="texto-font color-text" for="log-manter-sessao">
                                <?php echo $conslocal ?>
                            </label>
                        </div>
                    </div>
                    <button class="btn-vazio-borda-2 subtitulo-font color-text" id="reload-btn" type="submit"><?php echo "Reset"; ?></button>
                </div>

            </div>
            <div class="s-back-1 srcoll-div col-item">
                <div class="grid-list">
                    <?php
                    $listar_livros = $controlo->listar_livros();
                    $i = 0;
                    foreach ($listar_livros['livros'] as $livroitem) {
                    ?>
                        <div style="height: 300px;">
                            <div class="d-flex flex-column align-items-center h-100">
                                <div class="h-85 d-flex justify-content-center align-items-center w-100">
                                    <div class="autor-img<?php echo $i ?> border-20"></div>
                                    <style>
                                        .autor-img<?php echo $i ?> {
                                            width: 95%;
                                            height: 100%;
                                            overflow: hidden;
                                            background-image: url('libs/img/book-covers/<?php echo $livroitem['fcover_url'] ?>');
                                            background-repeat: no-repeat;
                                            background-position: 50% 50%;
                                            background-size: cover;
                                        }
                                    </style>
                                </div>
                                <span class="subtitulo-font color-text"><?php echo $livroitem['title'] ?></span>
                                <a class="btn-dark" href="<?php echo get_link_completo("livro", $livroitem['internal_code']); ?>"><?php echo $detalhes ?></a>
                            </div>
                        </div>
                    <?php
                        $i++;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>