<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
include "verificar_admin.php";
$listar_users = $controlo->listar_users_status_del();

?>
<div id="alert-container" class="fade show position-absolute mt-4 start-50 translate-middle-x top-1 w-auto">
</div> <!-- Container para os alerts -->
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary"><?php echo $adminpages ?> / </a>
                    <a href="<?php echo get_link("gerirtickets") ?>" id="identidade-site" class="color-text"><?php echo $listdel ?></a>
                </span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-suporte px-5 pt-3">
            <div></div>
            <div class="s-back-1 srcoll-div col-item table-box p-3">
                <table class="color-text tabela-suporte" id="myTable">
                    <thead>
                        <tr class="subtitulo-font text-uppercase">
                            <td><?php echo $user ?></td>
                            <td class=""><?php echo $nome ?></td>
                            <td class="text-center">
                                <button class="d-flex flex-row align-items-center w-100 justify-content-center border-0 bg-transparent" type="button" id="invertRows">
                                    <span class="color-text text-uppercase"><?php echo $data ?></span>
                                    <div class="d-flex flex-column color-accent">
                                        <span class="material material-symbols-rounded icon-25 mb-neg5 opacidade-60">arrow_drop_up</span>
                                        <span class="material material-symbols-rounded icon-25 mt-neg5">arrow_drop_down</span>
                                    </div>
                                </button>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($listar_users['success'] == true) {
                            if (!empty($listar_users['data'])) {
                                foreach ($listar_users['data'] as $item) {
                        ?>
                                    <tr class="texto-font">
                                        <td class="">
                                            <p class="m-0 truncate-texto"><?php echo $item['username'] ?></p>
                                            <p class="m-0 truncate-texto opacidade-60"><?php echo $item['email'] ?></p>
                                        </td>
                                        <td>
                                            <p class="m-0"><?php echo $item['first_name'] . " " . $item['last_name'] ?></p>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $item['updated_at'] ?>
                                        </td>
                                        <td class="text-center">
                                            <form action="<?php echo get_link("procurarconta") ?>" method="post" target="_blank">
                                                <input type="" class="d-none" name="post_username" value="<?php echo $item['id'] ?>">
                                                <button type="submit" class="text-decoration-none texto-font color-text border-0 bg-transparent" href="<?php echo get_link("procurarconta") ?>">
                                                    <?php echo $detalhes ?>
                                                </button>
                                            </form>

                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="text-decoration-none texto-font color-text border-0 bg-transparent gerir-ticket-apagar-utilizador" name="<?php echo $item['id'] ?>" href="<?php echo get_link("procurarconta") ?>">
                                                <?php echo $apagaruser ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td class="color-texto texto-font">
                                        <?php echo $pesqrapivazio ?>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td class="color-texto texto-font">
                                    <?php echo $listar_users['data'] ?>
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