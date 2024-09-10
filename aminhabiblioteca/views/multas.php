<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
$lista_multas = $controlo->listar_multas($_SESSION['user_dados']['id']);
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
                    <a href="<?php echo get_link("multas") ?>" id="identidade-site" class="color-text"><?php echo $multas ?></a>
                </span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-multas">
            <div class="subtitulo-font color-accent">
                <p class="m-0"><?php echo $nota . ": " . $favmens1 ?></p>
                <p class="m-0"><?php echo $favmens2 . " " . $favmens3 ?></p>
            </div>
            <div class="s-back-2 col-item p-4">
                <span class="titulo-font color-text"><?php echo $multas ?></span>
                <div class="h-100 table-box">
                    <table class="mt-2 text-center">
                        <tr class="subtitulo-font color-text opacidade-60">
                            <td><?php echo $requisicao ?></td>
                            <td colspan="2"><?php echo $titulo ?></td>
                            <td class="text-center"><?php echo $dataemissao ?></td>
                            <td class="text-center"><?php echo $datapagamento ?></td>
                            <td class="text-center"><?php echo $valor ?></td>
                            <td></td>
                        </tr>
                        <?php
                        foreach ($lista_multas['data'] as $item) {
                        ?>
                            <tr class="subtitulo-font color-text">
                                <td class="text-center"><?php echo $item['request_id'] ?></td>
                                <td colspan="2">
                                    <img src="libs/img/book-covers/<?php echo $item['fcover_url'] ?>" alt="<?php echo $item['fcover_url'] ?>">
                                    <span class="ms-2"><?php echo $item['title'] ?></span>
                                </td>
                                <td class="text-center"><?php echo $item['start_at'] ?></td>
                                <td class="text-center"><?php echo $item['payment_date'] ?></td>
                                <td class="text-center"><?php echo $item['amount'] . '€' ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($item['status']) {
                                    ?>
                                        <button class="btn-back-primary color-back px-2 remover-favorito" type="button" disabled><?php echo $pagar ?></button>
                                    <?php
                                    }
                                    ?>
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