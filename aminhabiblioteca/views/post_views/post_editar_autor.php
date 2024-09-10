<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/author-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new author_controlo();

if (
    isset($_POST['autor_id']) && isset($_POST['prinome']) && isset($_POST['ultnome']) &&
    isset($_POST['datanasc']) && isset($_POST['datamorte']) &&
    isset($_POST['nacionalidade']) && isset($_POST['websitepessoal']) &&
    isset($_POST['wiki']) && isset($_POST['facebook']) &&
    isset($_POST['twitter']) && isset($_POST['instagram']) &&
    isset($_POST['reddit']) && isset($_POST['tiktok']) &&
    isset($_POST['desc_pt']) && isset($_POST['desc_eng']) &&
    isset($_POST['photo_url'])
) {
    $autor_id = $_POST['autor_id'];
    $prinome = $_POST['prinome'];
    $ultnome = $_POST['ultnome'];
    $datanasc = $_POST['datanasc'];
    $datamorte = $_POST['datamorte'];
    $nacionalidade = $_POST['nacionalidade'];
    $websitepessoal = $_POST['websitepessoal'];
    $wiki = $_POST['wiki'];
    $facebook = $_POST['facebook'];
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];
    $reddit = $_POST['reddit'];
    $tiktok = $_POST['tiktok'];
    $desc_pt = $_POST['desc_pt'];
    $desc_eng = $_POST['desc_eng'];
    $photo_url = $_POST['photo_url'];

    $adicionar = $controlo->atualizar_autor(
        $autor_id,
        $prinome,
        $ultnome,
        $datanasc,
        $datamorte,
        $nacionalidade,
        $websitepessoal,
        $wiki,
        $facebook,
        $twitter,
        $instagram,
        $reddit,
        $tiktok,
        $desc_pt,
        $desc_eng,
        $photo_url
    );

    if ($adicionar["success"]) {
        echo json_encode(['success' => true, 'message' => $adicionar["data"]]);
    } else {
        echo json_encode(['success' => false, 'message' => $adicionar["data"]]);
    }
    exit;
}
