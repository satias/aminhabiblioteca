<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new controlo();
if (isset($_POST['bookname']) && isset($_POST['authorname']) && isset($_POST['genero']) && isset($_POST['linguagem']) && isset($_POST['editora']) && isset($_POST['dispo']) && isset($_POST['indispo']) && isset($_POST['localcons'])&& isset($_POST['url'])) {
    $bookname = $_POST['bookname'];
    $authorname = $_POST['authorname'];
    $genero = $_POST['genero'];
    $linguagem = $_POST['linguagem'];
    $editora = $_POST['editora'];
    $dispo = $_POST['dispo'];
    $indispo = $_POST['indispo'];
    $localcons = $_POST['localcons'];
    $url = $_POST['url'];

    $livros = $controlo->pequisa_livros($bookname, $authorname, $genero, $linguagem, $editora, $dispo, $indispo, $localcons);
    if ($livros['success']) {
        if (!empty($livros['data'])) {
            $linkautor = get_link("gerirlivros");
            $i = 0;
            foreach ($livros['data'] as $item) {
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
                                    background-image: url('libs/img/book-covers/<?php echo $item['fcover_url'] ?>');
                                    background-repeat: no-repeat;
                                    background-position: 50% 50%;
                                    background-size: cover;
                                }
                            </style>
                        </div>
                        <span class="subtitulo-font color-text"><?php echo $item['title'] ?></span>
                        <?php
                        if (strpos($url, $linkautor) !== false) {
                        ?>
                        <div class="d-flex flex-row flex-nowrap w-100 justify-content-around">
                            <form action="<?php echo get_link("detalheslivro"); ?>" method="post">
                                <input type="hidden" name="livro_codigo" value="<?php echo $item['internal_code'] ?>">
                                <button type="submit" class="btn-back-primary rounded color-back py-0 px-2 text-font" href="<?php echo get_link("detalhesautor"); ?>"><?php echo $detalhes ?></button>
                            </form>
                            <form action="<?php echo get_link("gerirlivro"); ?>" method="post">
                                <input type="hidden" name="livro_codigo" value="<?php echo $item['internal_code'] ?>">
                                <button type="submit" class="btn-vazio-borda-2 py-0 px-3 rounded color-primary text-font" href="<?php echo get_link("detalhesautor"); ?>"><?php echo $editar ?></button>
                            </form>
                        </div>
                        <?php
                        } else {
                        ?>
                            <a class="btn-dark" href="<?php echo get_link_completo("livro", $item['internal_code']); ?>"><?php echo $detalhes ?></a>
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
        echo $livros['data'];
    }
}
