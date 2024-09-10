<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
$lista_reservas = $controlo->listar_reservas_user($_SESSION['user_dados']['id']);
if ($lista_reservas['success']) {
    $cara = count($lista_reservas['data']);
} else {
    $cara = $lista_reservas['data'];
}

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
                    <a href="<?php echo get_link("reservas") ?>" id="identidade-site" class="color-text"><?php echo $reservas ?></a>
                </span>
            </div>
            <?php include "views/notif-img.php" ?>
        </div>
        <div class="grid-reservas px-5">
            <div class="subtitulo-font color-accent">
                <p class="m-0"><?php echo $nota . ": " . $favmens1 ?></p>
            </div>
            <?php
            if ($cara >= 1) {
            ?>
                <div class="col-item s-back-2 d-flex flex-row p-3">
                    <div class="w-25 position-relative h-100 ">
                        <div class="position-relative h-100 w-75 mx-auto overflow-hidden">
                            <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $lista_reservas['data'][0]['fcover_url'] ?>" alt="<?php echo $lista_reservas['data'][0]['fcover_url'] ?>" id="book-fcover" class="cover-book border-20 book-fcover">
                            <?php
                            if (!empty($lista_reservas['data'][0]['bcover_url'])) {
                            ?>
                                <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $lista_reservas['data'][0]['bcover_url'] ?>" alt="<?php echo $lista_reservas['data'][0]['bcover_url'] ?>" id="book-bcover" class="cover-book border-20 book-bcover cover-hidden">
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        if (!empty($lista_reservas['data'][0]['bcover_url'])) {
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
                    <div class="w-75 py-5 ps-5 d-flex flex-row">
                        <div class="h-100 col d-flex flex-column justify-content-between align-items-center">
                            <span class="titulo-font color-text"><?php echo $lista_reservas['data'][0]['title'] ?></span>
                            <div class="d-flex flex-column text-center">
                                <p class="subtitulo-font color-text"><?php echo $codinter . ": " . $lista_reservas['data'][0]['internal_code'] ?></p>
                                <p class="subtitulo-font color-text"><?php echo $editora . ": " . $lista_reservas['data'][0]['publisher'] ?></p>
                                <?php
                                if (!empty($lista_reservas['data'][0]['page_number'])) {
                                ?>
                                    <p class="subtitulo-font color-text"><?php echo $numpag . ": " . $lista_reservas['data'][0]['page_number'] ?></p>
                                <?php
                                }
                                ?>
                                <p>
                                    <a class="texto-font color-accent d-flex justify-content-center text-decoration-none" href="<?php echo get_link_completo("livro", $lista_reservas['data'][0]['internal_code']); ?>">
                                        <?php echo $irpaglivro ?>
                                        <span class="material-symbols-rounded icon-25">
                                            arrow_right_alt
                                        </span>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <hr class="borda-red">
                        <div class="h-100 col d-flex flex-column justify-content-center align-items-center">
                            <?php
                            if (!empty($lista_reservas['data'][0]['author_id'])) {
                            ?>
                                <p class="subtitulo-font color-text m-0"><?php echo $lista_reservas['data'][0]['first_name'] . " " . $lista_reservas['data'][0]['last_name'] ?></p>
                                <p>
                                    <a class="texto-font color-accent d-flex justify-content-center text-decoration-none" href="<?php echo get_link_completo("autor", $lista_reservas['data'][0]['author_id']); ?>">
                                        <?php echo $irpagautor ?>
                                        <span class="material-symbols-rounded icon-25">
                                            arrow_right_alt
                                        </span>
                                    </a>
                                </p>
                            <?php
                            }
                            ?>
                            <?php
                            if (!empty($lista_reservas['data'][0]['publisher'])) {
                            ?>
                                <p class="subtitulo-font color-text"><?php echo $editora . ": " . $lista_reservas['data'][0]['publisher'] ?></p>
                            <?php
                            }
                            ?>
                            <p class="subtitulo-font color-text"><?php echo $linguagem . ": " . $lista_reservas['data'][0]['language'] ?></p>
                        </div>
                        <hr class="borda-red">
                        <div class="h-100 col d-flex flex-column justify-content-center align-items-center">
                            <p class="texto-font color-accent d-flex">
                                <?php
                                if (!$lista_reservas['data'][0]['prolonged']) {
                                    echo $dataesperada . ": " . $lista_reservas['data'][0]['end_at'];
                                } else {
                                    echo $dataesperada . ": " . $atrasado;
                                }
                                ?>
                            </p>
                            <p class="texto-font color-accent d-flex">
                                <?php
                                echo $numeroqueue . ": " . $lista_reservas['data'][0]['queue_num']
                                ?>
                            </p>
                            <button class="btn-back-primary color-back px-2 btn-remover-reserva" id="<?php echo $lista_reservas['data'][0]['reserve_id'] ?>" type="button"><?php echo $cancelar ?></button>
                        </div>
                    </div>
                </div>
                <div></div>
            <?php
            }
            if ($cara == 2) {
            ?>
                <div class="col-item s-back-2 d-flex flex-row-reverse p-3">
                    <div class="w-25 position-relative h-100 ">
                        <div class="position-relative h-100 w-75 mx-auto overflow-hidden">
                            <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $lista_reservas['data'][1]['fcover_url'] ?>" alt="<?php echo $lista_reservas['data'][1]['fcover_url'] ?>" id="book-fcover-copy" class="cover-book border-20 book-fcover">
                            <?php
                            if (!empty($lista_reservas['data'][1]['bcover_url'])) {
                            ?>
                                <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $lista_reservas['data'][1]['bcover_url'] ?>" alt="<?php echo $lista_reservas['data'][1]['bcover_url'] ?>" id="book-bcover-copy" class="cover-book border-20 book-bcover cover-hidden">
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        if (!empty($lista_reservas['data'][1]['bcover_url'])) {
                        ?>
                            <button type="button" id="book-cover-btn-copy" class="position-absolute bottom-0 end-0 p-0 botao-circ-30 btn-flip">
                                <span class="material-symbols-outlined icon-30 color-accent">
                                    trending_flat
                                </span>
                            </button>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="w-75 py-5 ps-5 d-flex flex-row-reverse">
                        <div class="h-100 col d-flex flex-column justify-content-between align-items-center">
                            <span class="titulo-font color-text"><?php echo $lista_reservas['data'][1]['title'] ?></span>
                            <div class="d-flex flex-column text-center">
                                <p class="subtitulo-font color-text"><?php echo $codinter . ": " . $lista_reservas['data'][1]['internal_code'] ?></p>
                                <p class="subtitulo-font color-text"><?php echo $editora . ": " . $lista_reservas['data'][1]['publisher'] ?></p>
                                <?php
                                if (!empty($lista_reservas['data'][1]['page_number'])) {
                                ?>
                                    <p class="subtitulo-font color-text"><?php echo $numpag . ": " . $lista_reservas['data'][1]['page_number'] ?></p>
                                <?php
                                }
                                ?>
                                <p>
                                    <a class="texto-font color-accent d-flex justify-content-center text-decoration-none" href="<?php echo get_link_completo("livro", $lista_reservas['data'][1]['internal_code']); ?>">
                                        <?php echo $irpaglivro ?>
                                        <span class="material-symbols-rounded icon-25">
                                            arrow_right_alt
                                        </span>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <hr class="borda-red">
                        <div class="h-100 col d-flex flex-column justify-content-center align-items-center">
                            <?php
                            if (!empty($lista_reservas['data'][1]['author_id'])) {
                            ?>
                                <p class="subtitulo-font color-text m-0"><?php echo $lista_reservas['data'][1]['first_name'] . " " . $lista_reservas['data'][1]['last_name'] ?></p>
                                <p>
                                    <a class="texto-font color-accent d-flex justify-content-center text-decoration-none" href="<?php echo get_link_completo("autor", $lista_reservas['data'][1]['author_id']); ?>">
                                        <?php echo $irpagautor ?>
                                        <span class="material-symbols-rounded icon-25">
                                            arrow_right_alt
                                        </span>
                                    </a>
                                </p>
                            <?php
                            }
                            ?>
                            <?php
                            if (!empty($lista_reservas['data'][1]['publisher'])) {
                            ?>
                                <p class="subtitulo-font color-text"><?php echo $editora . ": " . $lista_reservas['data'][1]['publisher'] ?></p>
                            <?php
                            }
                            ?>
                            <p class="subtitulo-font color-text"><?php echo $linguagem . ": " . $lista_reservas['data'][1]['language'] ?></p>
                        </div>
                        <hr class="borda-red">
                        <div class="h-100 col d-flex flex-column justify-content-center align-items-center">
                            <p class="texto-font color-accent d-flex">
                                <?php
                                if (!$lista_reservas['data'][1]['prolonged']) {
                                    echo $dataesperada . ": " . $lista_reservas['data'][1]['end_at'];
                                } else {
                                    echo $dataesperada . ": " . $atrasado;
                                }
                                ?>
                            </p>
                            <p class="texto-font color-accent d-flex">
                                <?php
                                echo $numeroqueue . ": " . $lista_reservas['data'][1]['queue_num']
                                ?>
                            </p>
                            <button class="btn-back-primary color-back px-2 btn-remover-reserva" id="<?php echo $lista_reservas['data'][1]['reserve_id'] ?>" type="button"><?php echo $cancelar ?></button>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>