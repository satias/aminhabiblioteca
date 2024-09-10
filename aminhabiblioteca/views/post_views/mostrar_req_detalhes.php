<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user_id = $_SESSION['user_dados']['id'];
    $lista_requisicoes = $controlo->listar_requisicoes_livrosautores($_SESSION['user_dados']['id']);

    foreach ($lista_requisicoes as $linha) {
        if ($linha['id'] == $req_id) {
?>
            <div class="w-25 position-relative h-100 ">
                <div class="position-relative h-100 w-75 mx-auto overflow-hidden">
                    <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $linha['fcover_url'] ?>" alt="<?php echo $linha['fcover_url'] ?>" id="book-fcover<?php echo $linha['id'] ?>" class="cover-book border-20 book-fcover">
                    <?php
                    if (!empty($linha['bcover_url'])) {
                    ?>
                        <img src="<?php echo get_link("") ?>libs/img/book-covers/<?php echo $linha['bcover_url'] ?>" alt="<?php echo $linha['bcover_url'] ?>" id="book-bcover<?php echo $linha['id'] ?>" class="cover-book border-20 book-bcover cover-hidden">
                    <?php
                    }
                    ?>
                </div>
                <?php
                if (!empty($linha['bcover_url'])) {
                ?>
                    <button type="button" id="book-cover-btn<?php echo $linha['id'] ?>" class="position-absolute bottom-0 end-0 p-0 botao-circ-30 btn-flip">
                        <span class="material-symbols-outlined icon-30 color-accent">
                            trending_flat
                        </span>
                    </button>
                    <script>
                        $("#book-cover-btn<?php echo $linha['id'] ?>").click(function() {
                            $("#book-fcover<?php echo $linha['id'] ?>").toggleClass("cover-hidden");
                            $("#book-bcover<?php echo $linha['id'] ?>").toggleClass("cover-hidden");
                            $("#book-cover-btn<?php echo $linha['id'] ?>").toggleClass("flip");
                        });
                    </script>
                <?php
                }
                ?>
            </div>
            <div class="w-75 py-5 ps-5 d-flex flex-row">
                <div class="h-100 col d-flex flex-column justify-content-between align-items-center">
                    <span class="titulo-font color-text"><?php echo $linha['title'] ?></span>
                    <div class="d-flex flex-column text-center">
                        <p class="subtitulo-font color-text"><?php echo $codinter . ": " . $linha['internal_code'] ?></p>
                        <p class="subtitulo-font color-text"><?php echo $editora . ": " . $linha['publisher'] ?></p>
                        <?php
                        if (!empty($linha['page_number'])) {
                        ?>
                            <p class="subtitulo-font color-text"><?php echo $numpag . ": " . $linha['page_number'] ?></p>
                        <?php
                        }
                        ?>
                        <p>
                            <a class="texto-font color-accent d-flex justify-content-center text-decoration-none" href="<?php echo get_link_completo("livro", $linha['internal_code']); ?>">
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
                    if (!empty($linha['author_id'])) {
                    ?>
                        <p class="subtitulo-font color-text m-0"><?php echo $linha['first_name'] . " " . $linha['last_name'] ?></p>
                        <p>
                            <a class="texto-font color-accent d-flex justify-content-center text-decoration-none" href="<?php echo get_link_completo("autor", $linha['author_id']); ?>">
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
                    if (!empty($linha['publisher'])) {
                    ?>
                        <p class="subtitulo-font color-text"><?php echo $editora . ": " . $linha['publisher'] ?></p>
                    <?php
                    }
                    ?>
                    <p class="subtitulo-font color-text"><?php echo $linguagem . ": " . $linha['language'] ?></p>
                </div>
                <hr class="borda-red">
                <div class="h-100 col d-flex flex-column justify-content-center align-items-center texto-font color-accent">
                    <p><?php echo $requisicao . ": " .  $linha['id'] ?></p>
                    <p>
                        <?php
                        echo $estado . ": ";
                        if (!$linha['expired']) {
                            if ($linha['end_date'] != "--/--/----") {
                                echo $ativo;
                            } else {
                                echo $pendente;
                            }
                        } else {
                            echo $atrasado;
                        }
                        ?>
                    </p>
                    <p><?php echo $comecaa . ": " . $linha['start_date'] ?></p>
                    <p id="data_entrega"><?php echo $acabaa . ": " . $linha['end_date'] ?></p>
                    <?php
                    if ($linha['end_date'] == "--/--/----") {
                    ?>

                        <?php
                    } else {
                    }
                    if (!$linha['expired']) {
                        if ($linha['end_date'] != "--/--/----") {
                            if (!$linha['date_extended']) {
                        ?>
                                <button class="btn-back-primary color-back px-2 btn-extender-requisicao" id="<?php echo $linha['id'] ?>" type="button"><?php echo "extender" ?></button>
                            <?php
                            } else {
                            ?>
                                <p><?php echo $datalimitemensagem ?></p>
                            <?php
                            }
                        } else {
                            ?>
                            <button class="btn-back-primary color-back px-2 btn-cancelar-requisicao" id="<?php echo $linha['id'] ?>" type="button"><?php echo $cancelar ?></button>
                        <?php
                        }
                    } else {
                        ?>
                        <button class="btn-back-primary color-back px-2" disabled type="button"><?php echo "extender" ?></button>
                    <?php
                    }
                    ?>

                </div>
            </div>
            <script>
                $(".btn-extender-requisicao").click(function() {
                    var req_id = $(this).attr("id");
                    var string_date = $("#data_entrega").html();
                    var end_date = string_date.replace("<?php echo $acabaa ?>: ", "");
                    //alert(end_date);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo get_link("") ?>/views/post_views/post_extenter_requisicao.php",
                        data: {
                            req_id: req_id,
                            end_date: end_date,
                        },
                        dataType: "html",
                        success: function(response) {
                            if (response) {
                                $("#alert-container").html(response);
                            }
                        }
                    });
                });
                $(".btn-cancelar-requisicao").click(function() {
                    var req_id = $(this).attr("id");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo get_link("") ?>/views/post_views/post_cancelar_requisicao.php",
                        data: {
                            req_id: req_id
                        },
                        dataType: "html",
                        success: function(response) {
                            if (response) {
                                $("#alert-container").html(response);
                            }
                        }
                    });
                });
            </script>
<?php
            break;
        }
    }
    exit;
}
?>