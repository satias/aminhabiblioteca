<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new controlo();

if (isset($_POST['bookid'])) {
    $bookid = $_POST['bookid'];

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user_id = $_SESSION['user_dados']['id'];
    $user_status = $_SESSION['user_dados']['status'];

    $reservar = $controlo->reservar_livro($user_id, $bookid, $user_status);
    if ($reservar['success']) {
?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading"><?php echo $reservar['data']['resesucesso'] ?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="location.reload();"></button>
            <p class="mb-0"><?php echo $reservar['data']['reserinfor'] ?></p>
        </div>
    <?php

    } else {
    ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h4 class="alert-heading"><?php echo $reservar['data']['reqfalhada'] ?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php
            if (isset($reservar['data']['conta_block'])) {
                echo '<p>' . $reservar['data']['conta_block'] . '</p>';
            }
            if (isset($reservar['data']['vermultas'])) {
                echo '<p>' . $reservar['data']['vermultas'] . '</p>';
            }
            if (isset($reservar['data']['rese2'])) {
                echo '<p>' . $reservar['data']['rese2'] . '</p>';
            }
            if (isset($reservar['data']['reslimite'])) {
                echo '<p>' . $reservar['data']['reslimite'] . '</p>';
            }
            ?>
            <hr>
            <p class="mb-0"><?php echo $reservar['data']['contsuporte'] ?></p>
        </div>
<?php
    }
}
?>