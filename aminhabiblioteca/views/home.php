<?php
include_once "verifica-sessao.php";
$link = 'controlo/controlo.php';
$link1 = 'controlo/user-controlo.php';
require_once $link;
require_once $link1;
$controlo = new controlo();
$user_controlo = new user_controlo();
$n_livros_autores = $controlo->n_livros_autores();
?>
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font">
                <span class="color-primary">A Minha Biblioteca / </span>
                <span><a href="<?php get_link("/") ?>" id="identidade-site" class="color-text"><?php echo $dashboard ?></a></span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-dashboard px-5 pt-3">
            <div class="row1">
                <div class="col-item username-box d-flex flex-column p-3">
                    <span class="subtitulo-font"><?php echo $bemvindo . "," ?></span>
                    <span class="texto-grande-font"><?php echo $_SESSION['user_dados']['username']; ?></span>
                </div>
                <div class="col-item s-back-2 container-fluid" id="col-pesquisa">
                    <div class="row h-100">
                        <div class="col d-flex">
                            <div class="my-auto w-100 position-relative">
                                <label for="log-username" class="form-label color-text subtitulo-font"><?php echo $pesqrap ?></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text material material-symbols-rounded color-accent bg-transparent input-dark-border-icon" id="addon-wrapping">search</span>
                                    <input type="text" id="pesq-rapida" class="texto-font form-control bg-transparent color-text input-dark-border" placeholder="Book/author" aria-label="Username" aria-describedby="addon-wrapping">
                                </div>
                                <div class="position-absolute pesq-rapida-tab shadow-sm rounded-2 d-none mt-1" id="pesq-rapida-tab">
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div class="d-flex align-items-center ms-auto">
                                <span class="subtitulo-font color-text mx-2">
                                    <?php echo $atualcom . ":" ?>
                                </span>
                                <div class="d-flex flex-column mx-5 texto-font">
                                    <div class="dash-count-ret text-center px-4 py-3 mb-1">
                                        <span>
                                            <?php echo $n_livros_autores['nlivros']; ?> <?php echo $livros ?>
                                        </span>
                                    </div>
                                    <div class="dash-count-ret text-center px-4 py-3 mt-1">
                                        <span>
                                            <?php echo $n_livros_autores['nautores']; ?> <?php echo $autores ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row2">
                <div class=" col-item p-3 s-back-1 container-fluid">
                    <div class="titulo-font color-text w-100">
                        <?php echo $novoslivros ?>
                    </div>
                    <div class="row mt-1">
                        <?php
                        $novos_livros = $controlo->novos_livros_home();
                        foreach ($novos_livros as $livro) {
                        ?>
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="libs/img/book-covers/<?php echo $livro['fcover_url'] ?>" alt="imagem" class="livro-lista-img">
                                    <span class="subtitulo-font color-text"><?php echo $livro['title'] ?></span>
                                    <a class="btn-dark" href="<?php echo get_link_completo("livro", $livro['internal_code']); ?>"><?php echo $detalhes ?></a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class=" col-item p-3 s-back-1 container-fluid">
                    <div class="titulo-font color-text w-100">
                        <?php echo $livrospop ?>
                    </div>
                    <div class="row mt-1">
                        <?php
                        $livros_populares = $controlo->livros_populares_home();
                        foreach ($livros_populares as $livro) {
                        ?>
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="libs/img/book-covers/<?php echo $livro['fcover_url'] ?>" alt="imagem" class="livro-lista-img">
                                    <span class="subtitulo-font color-text"><?php echo $livro['title'] ?></span>
                                    <a class="btn-dark" href="<?php echo get_link_completo("livro", $livro['internal_code']); ?>"><?php echo $detalhes ?></a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row3">
                <div class="col-item p-3 s-back-2">
                    <div class="subtitulo-font d-flex align-items-center">
                        <span class="color-text"><?php echo $requisicoes ?></span>
                        <a class="ms-3 icon-25" href="<?php echo get_link("requisicoes") ?>">
                            <span class="color-accent material-symbols-rounded">
                                more_horiz
                            </span>
                        </a>
                    </div>
                    <div class="texto-font px-3 pt-2 pb-5 d-flex flex-column my-auto h-100">
                        <?php
                        $lista_requisiçoes = $user_controlo->listar_requisicoes_livrosautores($_SESSION['user_dados']['id']);
                        $my_auto = (sizeof($lista_requisiçoes) >= 3) ? "my-auto" : "my-2";
                        foreach ($lista_requisiçoes as $requi) {
                            if ($requi['status'] == 1) {
                        ?>
                                <div class="d-flex justify-content-between <?php echo $my_auto ?>">
                                    <span class="color-text"><?php echo $requi["title"]; ?></span>
                                    <span class="color-accent">
                                        <?php
                                        if (!$requi['expired']) {
                                            if ($requi['end_date'] != "--/--/----") {
                                                echo $ativo;
                                            } else {
                                                echo $pendente;
                                            }
                                        } else {
                                            echo $atrasado;
                                        }
                                        ?>
                                    </span>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class=""></div>
                <div class=" col-item p-3 s-back-2">
                    <div class="subtitulo-font d-flex align-items-center">
                        <span class="color-text"><?php echo $topcare ?></span>
                        <a class="ms-3 icon-25" href="<?php echo get_link("reservas") ?>">
                            <span class="color-accent material-symbols-rounded">
                                more_horiz
                            </span>
                        </a>
                    </div>
                    <div class="texto-font px-3 pt-2 pb-5 d-flex flex-column my-auto h-100">
                        <?php
                        $categorias_populares = $controlo->categorias_populares_home();
                        $max_requests = $categorias_populares[0]['num_requests'];
                        foreach ($categorias_populares as $categoria) {
                            $progress_width = ($categoria['num_requests'] / $max_requests) * 100;

                        ?>
                            <div class="my-auto">
                                <span class="color-text"><?php echo $categoria['categoria'] ?></span>
                                <div class="progress progress-bar-dash" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 1px">
                                    <div class="progress-bar" style="width: <?php echo $progress_width; ?>%"></div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>