<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new controlo();

if (
    isset($_POST['book_id']) &&
    isset($_POST['titulo']) && isset($_POST['linguagem']) &&
    isset($_POST['codinter']) && isset($_POST['editora']) &&
    isset($_POST['datalanc']) && isset($_POST['isbn']) &&
    isset($_POST['numedit']) && isset($_POST['numpag']) &&
    isset($_POST['condicao']) && isset($_POST['discbiblio']) &&
    isset($_POST['disreq']) && isset($_POST['desc_pt']) &&
    isset($_POST['desc_eng']) && isset($_POST['photo_url_capa']) &&
    isset($_POST['photo_url_contracapa']) 
    && isset($_POST['generos'])&& isset($_POST['autor_id'])
) {
    $book_id = $_POST['book_id'];
    $titulo = $_POST['titulo'];
    $linguagem = $_POST['linguagem'];
    $codinter = $_POST['codinter'];
    $editora = $_POST['editora'];
    $datalanc = $_POST['datalanc'];
    $isbn = $_POST['isbn'];
    $numedit = $_POST['numedit'];
    $numpag = $_POST['numpag'];
    $condicao = $_POST['condicao'];
    $discbiblio = $_POST['discbiblio'];
    $disreq = $_POST['disreq'];
    $desc_pt = $_POST['desc_pt'];
    $desc_eng = $_POST['desc_eng'];
    $photo_url_capa = $_POST['photo_url_capa'];
    $photo_url_contracapa = $_POST['photo_url_contracapa'];
    $generos = $_POST['generos'];
    $autor_id = $_POST['autor_id'];

    $adicionar = $controlo->atualizar_livro(
        $book_id,
        $titulo,
        $linguagem,
        $codinter,
        $editora,
        $datalanc,
        $isbn,
        $numedit,
        $numpag,
        $condicao,
        $discbiblio,
        $disreq,
        $desc_pt,
        $desc_eng,
        $photo_url_capa,
        $photo_url_contracapa,
        $generos,
        $autor_id
    );

    if ($adicionar["success"]) {
        echo json_encode(['success' => true, 'message' => $adicionar["data"]]);
    } else {
       echo json_encode(['success' => false, 'message' => $adicionar["data"]]);
    }
    //echo json_encode(['success' => true, 'message' => 'entrou']);
    exit;
}
