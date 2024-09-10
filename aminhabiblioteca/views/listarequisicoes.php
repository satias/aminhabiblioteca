<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
include "verificar_funcionario.php";
$lista_requisicoes = $controlo->listar_todas_requisicoes();
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
                    <a href="<?php echo get_link("listarequisicoes") ?>" id="identidade-site" class="color-text"><?php echo $listarequisicoes ?></a>
                </span>
                <span class="color-text"><?php echo $listarequisicoes ?></span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-lista-requisicoes">
            <div class="s-back-2 col-item p-4">
                <span class="titulo-font color-text"><?php echo $listarequisicoesativas ?></span>
                <div class="h-100 table-box srcoll-div">
                    <table class="mt-2 text-center">
                        <tr class="subtitulo-font color-text opacidade-60">
                            <td><?php echo $requisicao ?></td>
                            <td><?php echo $username ?></td>
                            <td class="text-center"><?php echo $titulo ?></td>
                            <td class="text-center"><?php echo $datacomeço ?></td>
                            <td class="text-center"><?php echo $datalimite ?></td>
                            <td><?php echo $atrasado ?></td>
                        </tr>
                        <?php
                        foreach ($lista_requisicoes['data'] as $item) {
                            if ($item['end_at'] != null) {
                        ?>
                                <tr class="subtitulo-font color-text">
                                    <td class="text-center"><?php echo $item['id'] ?></td>
                                    <td>
                                        <form action="<?php echo get_link("procurarutilizador") ?>" method="post" target="_blank">
                                            <input type="hidden" name="post_username" value="<?php echo $item['user_id'] ?>">
                                            <button type="submit" class="text-decoration-none border-0 bg-transparent color-text subtitulo-font" href="<?php echo get_link("procurarutilizador") ?>">
                                                <?php echo $item['username'] ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <form action="<?php echo get_link("detalheslivro") ?>" method="post" target="_blank">
                                            <input type="hidden" name="livro_codigo" value="<?php echo $item['internal_code'] ?>">
                                            <button type="submit" class="text-decoration-none border-0 bg-transparent color-text subtitulo-font" href="<?php echo get_link("detalheslivro") ?>">
                                                <?php echo $item['title'] ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center"><?php echo $item['start_at'] ?></td>
                                    <td class="text-center"><?php echo $item['end_at'] ?></td>
                                    <td class="text-center">
                                        <?php
                                        if ($item['expired']) {
                                            echo $sim;
                                        } else {
                                            echo $nao;
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="s-back-2 col-item p-4">
                <span class="titulo-font color-text"><?php echo $listarequisicoespendentes ?></span>
                <div class="h-100 table-box srcoll-div">
                    <table class="mt-2 text-center">
                        <tr class="subtitulo-font color-text opacidade-60">
                            <td><?php echo $requisicao ?></td>
                            <td><?php echo $username ?></td>
                            <td class="text-center"><?php echo $titulo ?></td>
                            <td class="text-center"><?php echo $datacomeço ?></td>
                        </tr>
                        <?php
                        foreach ($lista_requisicoes['data'] as $item) {
                            if ($item['end_at'] == null) {
                        ?>
                                <tr class="subtitulo-font color-text">
                                    <td class="text-center"><?php echo $item['id'] ?></td>
                                    <td>
                                        <form action="<?php echo get_link("procurarutilizador") ?>" method="post" target="_blank">
                                            <input type="hidden" name="post_username" value="<?php echo $item['user_id'] ?>">
                                            <button type="submit" class="text-decoration-none border-0 bg-transparent color-text subtitulo-font" href="<?php echo get_link("procurarutilizador") ?>">
                                                <?php echo $item['username'] ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <form action="<?php echo get_link("detalheslivro") ?>" method="post" target="_blank">
                                            <input type="hidden" name="livro_codigo" value="<?php echo $item['internal_code'] ?>">
                                            <button type="submit" class="text-decoration-none border-0 bg-transparent color-text subtitulo-font" href="<?php echo get_link("detalheslivro") ?>">
                                                <?php echo $item['title'] ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center"><?php echo $item['start_at'] ?></td>
                            <?php
                            }
                        }
                            ?>
                    </table>
                </div>
            </div>
        </div>
    </div>