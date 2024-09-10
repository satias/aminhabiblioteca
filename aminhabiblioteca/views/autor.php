<?php
include_once "verifica-sessao.php";
$request = $_SERVER["REQUEST_URI"];

$codigo_autor = substr($request, strlen(get_link("autor") . "/"));
$link = 'controlo/author-controlo.php';
require_once $link;
$controlo = new author_controlo();
$autor_detalhes = $controlo->listar_autor_pag($codigo_autor);
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
                <span class="color-text"><?php echo $detautor ?></span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-template-autor px-5 pt-3">
            <div class="grid-autor-info">
                <div class="col-item s-back-2 p-4">
                    <img src="<?php echo get_link('') ?>libs/img/author-pics/<?php echo $autor_detalhes['data']['photo_url'] ?>" alt="imagem" class="border-20 w-100 h-100">
                </div>
                <div class="col-item s-back-1 p-4 d-flex flex-column">
                    <div class="titulo-grande-font color-text"><?php echo $autor_detalhes['data']['first_name'] . ' ' . $autor_detalhes['data']['last_name']; ?></div>
                    <div class="d-flex flex-row h-100">
                        <div class="w-35 subtitulo-font color-text d-flex flex-column justify-content-evenly align-items-center">
                            <?php
                            if (!empty($autor_detalhes['data']['nacionality'])) {
                                echo '<span>';
                                echo $nacio . ': ' . $autor_detalhes['data']['nacionality'];
                                echo '</span>';
                            }
                            if (!empty($autor_detalhes['data']['birth_date'])) {
                                echo '<span>';
                                echo $birth . ': ' . $autor_detalhes['data']['birth_date'];
                                echo '</span>';
                            }
                            if (!empty($autor_detalhes['data']['death_date'])) {
                                echo '<span>';
                                echo $death . ': ' . $autor_detalhes['data']['death_date'];
                                echo '</span>';
                            }
                            if (!empty($autor_detalhes['data']['personal_site'])) {
                                echo '<span>';
                                echo '<a class="color-text" href="' . $autor_detalhes['data']['personal_site'] . '" target="_blank">';
                                echo $sitepessoal;
                                echo '</a>';
                                echo '</span>';
                            }
                            if (!empty($autor_detalhes['data']['wiki_page'])) {
                                echo '<span>';
                                echo '<a class="color-text" href="' . $autor_detalhes['data']['wiki_page'] . '" target="_blank">';
                                echo $paginawiki;
                                echo '</a>';
                                echo '</span>';
                            }
                            ?>
                            <div class="w-75 d-flex justify-content-evenly align-items-center flex-wrap">
                                <?php
                                if (!empty($autor_detalhes['data']['facebook_link'])) {
                                    echo '<a class="color-text" href="' . $autor_detalhes['data']['facebook_link'] . '" target="_blank">';
                                    echo '<img src="' . get_link("") . 'libs/img/social/facebook.svg">';
                                    echo '</a>';
                                }
                                if (!empty($autor_detalhes['data']['twitter_link'])) {
                                    echo '<a class="color-text" href="' . $autor_detalhes['data']['twitter_link'] . '" target="_blank">';
                                    echo '<img src="' . get_link("") . 'libs/img/social/twitter.svg">';
                                    echo '</a>';
                                }
                                if (!empty($autor_detalhes['data']['instagram_link'])) {
                                    echo '<a class="color-text" href="' . $autor_detalhes['data']['instagram_link'] . '" target="_blank">';
                                    echo '<img src="' . get_link("") . 'libs/img/social/instagram.svg">';
                                    echo '</a>';
                                }
                                if (!empty($autor_detalhes['data']['reddit_link'])) {
                                    echo '<a class="color-text" href="' . $autor_detalhes['data']['reddit_link'] . '" target="_blank">';
                                    echo '<img src="' . get_link("") . 'libs/img/social/reddit.svg">';
                                    echo '</a>';
                                }
                                if (!empty($autor_detalhes['data']['tiktok_link'])) {
                                    echo '<a class="color-text" href="' . $autor_detalhes['data']['tiktok_link'] . '" target="_blank">';
                                    echo '<img src="' . get_link("") . 'libs/img/social/tiktok.svg">';
                                    echo '</a>';
                                }
                                ?>
                            </div>
                        </div>
                        <hr class="borda-red">
                        <div class="w-65 color-text d-flex flex-column justify-content-center align-items-start px-4">
                            <span class="subtitulo-font"><?php echo $descricao ?></span>
                            <span class="texto-font">
                                <?php
                                if (!empty($autor_detalhes['data']['descricao'])) {
                                    echo $autor_detalhes['data']['descricao'];
                                } else {
                                    echo $nodescricao;
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-item s-back-1 py-3 px-4 color-text">
                <span class="titulo-font"><?php echo $trabalhodele ?></span>
                <div class="mt-2 autor-livro-list">
                    <?php
                    $listar_autor_work = $controlo->listar_autor_work($codigo_autor);
                    foreach ($listar_autor_work['data'] as $item) {
                    ?>
                    <a href="<?php echo get_link_completo("livro", $item['internal_code']); ?>">
                        <div class="text-center">
                            <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $item['fcover_url'] ?>" alt="imagem" class="autor-livro-img mx-auto">
                            <span class="texto-font color-text"><?php echo $item['title'] ?></span>
                        </div>
                    </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>