<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
$listar_favoritos = $controlo->listar_favoritos($_SESSION['user_dados']['id']);
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
                    <a href="<?php echo get_link("favoritos") ?>" id="identidade-site" class="color-text"><?php echo $favoritos ?></a>
                </span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-favoritos">
            <div class="s-back-2 col-item p-4">
                <span class="titulo-font color-text"><?php echo $favoritos ?></span>
                <div class="h-100 table-box">
                    <table class="mt-2">
                        <tr class="subtitulo-font color-text opacidade-60">
                            <td></td>
                            <td><?php echo $titulo ?></td>
                            <td class="text-center"><?php echo $autor ?></td>
                            <td class="text-center"><?php echo $edicao ?></td>
                            <td class="text-center"><?php echo $linguagem ?></td>
                            <td class="text-center"><?php echo $editora ?></td>
                            <td></td>
                        </tr>
                        <?php
                        foreach ($listar_favoritos['data'] as $item) {
                        ?>
                            <tr class="subtitulo-font color-text">
                                <td colspan="2" data-href="<?php echo get_link_completo("livro", $item['internal_code']) ?>" class="cursor-pointer">
                                    <a href="<?php echo get_link_completo("livro", $item['internal_code']) ?>" class="color-text text-decoration-none">
                                        <img src="libs/img/book-covers/<?php echo $item['fcover_url']; ?>" alt="<?php echo $item['title']; ?>">
                                        <span class="ms-2"><?php echo $item['title'] ?></span>
                                    </a>
                                </td>
                                <td class="text-center cursor-pointer" data-href="<?php echo get_link_completo("livro", $item['internal_code']) ?>">
                                    <a href="<?php echo get_link_completo("livro", $item['internal_code']) ?>" class="color-text text-decoration-none">
                                        <?php echo $item['first_name'] . ' ' .  $item['last_name'] ?>
                                    </a>
                                </td>
                                <td class="text-center cursor-pointer" data-href="<?php echo get_link_completo("livro", $item['internal_code']) ?>">
                                    <a href="<?php echo get_link_completo("livro", $item['internal_code']) ?>" class="color-text text-decoration-none">
                                        <?php echo $item['edition_number'] ?>
                                    </a>
                                </td>
                                <td class="text-center cursor-pointer" data-href="<?php echo get_link_completo("livro", $item['internal_code']) ?>">
                                    <a href="<?php echo get_link_completo("livro", $item['internal_code']) ?>" class="color-text text-decoration-none">
                                        <?php echo $item['language'] ?>
                                    </a>
                                </td>
                                <td class="text-center cursor-pointer" data-href="<?php echo get_link_completo("livro", $item['internal_code']) ?>">
                                    <a href="<?php echo get_link_completo("livro", $item['internal_code']) ?>" class="color-text text-decoration-none">
                                        <?php echo $item['publisher'] ?>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <button class="btn-back-primary color-back px-2 remover-favorito" type="button" id="<?php echo $item['book_id'] ?>"><?php echo $remover ?></button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>