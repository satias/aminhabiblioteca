<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/author-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new author_controlo();
if (isset($_POST['authorname']) && isset($_POST['nacionalidade']) && isset($_POST['url'])) {
    $authorname = $_POST['authorname'];
    $nacionalidade = $_POST['nacionalidade'];
    $url = $_POST['url'];

    $autores = $controlo->pesquisa_livros($authorname, $nacionalidade);
    if ($autores['success']) {
        if (!empty($autores['data'])) {
            $linkautor = get_link("gerirautores");
            $i = 0;
            foreach ($autores['data'] as $item) {
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
                                    background-image: url('libs/img/author-pics/<?php echo $item['photo_url'] ?>');
                                    background-repeat: no-repeat;
                                    background-position: 50% 50%;
                                    background-size: cover;
                                }
                            </style>
                        </div>
                        <span class="subtitulo-font color-text"><?php echo $item['first_name'] . " " . $item['last_name'] ?></span>
                        <?php
                        if (strpos($url, $linkautor) !== false) {
                        ?>
                            <div class="d-flex flex-row flex-nowrap w-100 justify-content-around">
                                <form action="<?php echo get_link("detalhesautor"); ?>" method="post">
                                    <input type="hidden" name="autor_id" value="<?php echo $item['id'] ?>">
                                    <button type="submit" class="btn-back-primary rounded color-back py-0 px-2 text-font" href="<?php echo get_link("detalhesautor"); ?>"><?php echo $detalhes ?></button>
                                </form>
                                <form action="<?php echo get_link("gerirautor"); ?>" method="post">
                                    <input type="hidden" name="autor_id" value="<?php echo $item['id'] ?>">
                                    <button type="submit" class="btn-vazio-borda-2 py-0 px-3 rounded color-primary text-font" href="<?php echo get_link("detalhesautor"); ?>"><?php echo $editar ?></button>
                                </form>
                            </div>
                        <?php
                        } else {
                        ?>
                            <a class="btn-dark" href="<?php echo get_link_completo("autor", $item['id']); ?>"><?php echo $detalhes ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
                $i++;
            }
        } else {
            ?>
            <span class="texto-font color-text"><?php echo $pesqrapivazio; ?></span>
<?php
        }
    } else {
        echo $autores['data'];
    }
}
