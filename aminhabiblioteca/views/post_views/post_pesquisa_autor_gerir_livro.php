<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/author-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new author_controlo();
if (isset($_POST['author_id'])) {
    $author_id = $_POST['author_id'];

    $autores = $controlo->listar_autor_pag($author_id);
    if ($autores['success']) {

?>
        <input type="hidden" id="gerir-livro-autor" value="<?php echo $autores['data']['id'] ?>">
        <div class="w-50 h-100 py-1">
            <div class="autor-img border-20"></div>
            <style>
                .autor-img {
                    width: 100%;
                    height: 100%;
                    overflow: hidden;
                    background-image: url('libs/img/author-pics/<?php echo $autores['data']['photo_url'] ?>');
                    background-repeat: no-repeat;
                    background-position: 50% 50%;
                    background-size: cover;
                }
            </style>
        </div>
        <div class="w-50 h-100 position-relative color-text p-1 info-autores">
            <div class="h-50 d-flex flex-column align-items-center justify-content-evenly">
                <span class="subtitulo-font"><?php echo $autores['data']['first_name'] . $autores['data']['last_name'] ?></span>
                <span class="texto-font"><?php echo $autores['data']['nacionality'] ?></span>
                <span class="texto-font"><?php echo $autores['data']['birth_date'] ?></span>
            </div>
            <div class="h-45" style="margin: 0 !important;margin-top: auto !important;">
                <textarea disabled class="form-control text-font h-100 esconder-texto-extra" placeholder="<?php echo $descricao ?>"><?php echo $autores['data']['descricao'] ?></textarea>
            </div>
            <span class="material-symbols-rounded color-accent cursor-pointer position-absolute top-0 end-0 mt-2" id="retirar-autor">close</span>
        </div>


<?php

    } else {
        echo $autores['data'];
    }
}
