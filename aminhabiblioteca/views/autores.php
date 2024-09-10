<?php
include_once "verifica-sessao.php";
$link = 'controlo/author-controlo.php';
require_once $link;
$controlo = new author_controlo();
$listar_nacionalidades = $controlo->listar_nacionalidades();
?>
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary">A Minha Biblioteca / </a>
                    <a href="<?php echo get_link("autores") ?>" id="identidade-site" class="color-text"><?php echo $listaautores ?></a>
                </span>
                <span class="color-text"><?php echo $procautor ?></span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-autores px-5 pt-3">
            <div class="s-back-2 col-item">
                <div class="container h-100">
                    <div class="row h-100 align-items-center justify-content-around">
                        <div class="col-7">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text material material-symbols-rounded color-accent bg-transparent input-dark-border-icon" id="addon-wrapping">search</span>
                                <input type="text" class="pesquisa-author-form texto-font form-control bg-transparent color-text input-dark-border" id="pesquisa-authorname" placeholder="<?php echo $nomeautor ?>" aria-label="Username" aria-describedby="addon-wrapping">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-floating select-input">
                                <select class="pesquisa-author-form form-select color-text" id="pesquisa-nacionalidade" aria-label="Floating label select example">
                                    <option selected value="all"><?php echo $todos ?></option>
                                    <?php
                                    foreach ($listar_nacionalidades['nacionalidades'] as $nacionalidade) {
                                    ?>
                                        <option class="texto-font" value="<?php echo $nacionalidade['nacionality'] ?>"><?php echo $nacionalidade['nacionality'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <label for="pesquisa-genero" class="texto-pequeno-font color-accent"><?php echo $nacio ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-back-1 srcoll-div col-item">
                <div class="grid-list">
                    <?php
                    $listar_autores = $controlo->listar_autores();
                    $i = 0;
                    foreach ($listar_autores['autores'] as $autor) {
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
                                            background-image: url('libs/img/author-pics/<?php echo $autor['photo_url'] ?>');
                                            background-repeat: no-repeat;
                                            background-position: 50% 50%;
                                            background-size: cover;
                                        }
                                    </style>
                                </div>
                                <span class="subtitulo-font color-text"><?php echo $autor['first_name'] . " " . $autor['last_name'] ?></span>
                                <a class="btn-dark" href="<?php echo get_link_completo("autor", $autor['id']); ?>"><?php echo $detalhes ?></a>
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