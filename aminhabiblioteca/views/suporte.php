<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
$listar_tickets = $controlo->listar_tickets($_SESSION['user_dados']['id']);

?>
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary">A Minha Biblioteca / </a>
                    <a href="<?php echo get_link("suporte") ?>" id="identidade-site" class="color-text"><?php echo $suporte ?></a>
                </span>
                <span class="color-text"><?php echo $listatickets ?></span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-suporte px-5 pt-3">
            <div></div>
            <div class="s-back-1 srcoll-div col-item table-box p-3">
                <table class="color-text tabela-suporte" id="myTable">
                    <thead>
                        <tr class="subtitulo-font text-uppercase">
                            <td><?php echo $titulodescricao ?></td>
                            <td class="coluna-200"><?php echo $tipo ?></td>
                            <td class="text-center coluna-200"><?php echo $estado ?></td>
                            <td class="text-center coluna-200">
                                <button class="d-flex flex-row align-items-center w-100 justify-content-center border-0 bg-transparent" type="button" id="invertRows">
                                    <span class="color-text text-uppercase"><?php echo $data ?></span>
                                    <div class="d-flex flex-column color-accent">
                                        <span class="material material-symbols-rounded icon-25 mb-neg5 opacidade-60">arrow_drop_up</span>
                                        <span class="material material-symbols-rounded icon-25 mt-neg5">arrow_drop_down</span>
                                    </div>
                                </button>
                            </td>
                            <td class="text-center">
                                <a class="btn-back-primary color-back subtitulo-font text-decoration-none" href="<?php echo get_link("criarticket") ?>"><?php echo $criar; ?></a>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($listar_tickets['data'] as $item) {
                        ?>
                            <tr class="texto-font">
                                <td class="coluna-max-10">
                                    <p class="m-0 truncate-texto"><?php echo $item['title'] ?></p>
                                    <p class="m-0 truncate-texto opacidade-60"><?php echo $item['description'] ?></p>
                                </td>
                                <td class="coluna-200">
                                    <p class="m-0"><?php echo $item['tipo1'] ?></p>
                                    <p class="m-0 opacidade-60"><?php echo $item['tipo2'] ?></p>
                                </td>
                                <td class="text-center coluna-200">
                                    <?php
                                    if ($item['status']) {
                                    ?>
                                        <span class="btn-vazio-borda-2 color-primary">
                                            <?php echo $aberto ?>
                                        </span>
                                    <?php
                                    } else {
                                    ?>
                                        <span class="btn-back-primary color-back">
                                            <?php echo $fechado ?>
                                        </span>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-center coluna-200"><?php echo $item['created_at'] ?></td>
                                <td class="text-center coluna-200">
                                    <form action="<?php echo get_link("detalhesticket") ?>" method="post" target="_blank">
                                        <input type="hidden" name="ticket_id" value="<?php echo $item['ticket_id'] ?>">
                                        <button type="submit" class="text-decoration-none texto-font color-text border-0 bg-transparent" href="<?php echo get_link("procurarconta") ?>">
                                            <?php
                                            if ($item['status']) {
                                                echo $respoticket;
                                            } else {
                                                echo $detalhes;
                                            }

                                            ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>