<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new controlo();

if (isset($_POST['bookid']) && isset($_POST['available'])) {
    $bookid = $_POST['bookid'];
    $available = $_POST['available'];

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user_id = $_SESSION['user_dados']['id'];
    $user_status = $_SESSION['user_dados']['status'];

    $requisitar = $controlo->requisitar_livro($user_id, $bookid, $user_status, $available);
    if ($requisitar['success']) {
?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading"><?php echo $requisitar['data']['reqsucesso'] ?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="location.reload();"></button>
            <?php echo $requisitar['data']['levantarlivro'] ?>
            <hr>
            <p class="mb-0"><?php echo $requisitar['data']['reqinfor'] ?></p>
        </div>
    <?php

    } else {
    ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h4 class="alert-heading"><?php echo $requisitar['data']['reqfalhada'] ?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php
            if (isset($requisitar['data']['conta_block'])) {
                echo '<p>' . $requisitar['data']['conta_block'] . '</p>';
            }
            if (isset($requisitar['data']['vermultas'])) {
                echo '<p>' . $requisitar['data']['vermultas'] . '</p>';
            }
            if (isset($requisitar['data']['reqs5'])) {
                echo '<p>' . $requisitar['data']['reqs5'] . '</p>';
            }
            ?>
            <hr>
            <p class="mb-0"><?php echo $requisitar['data']['contsuporte'] ?></p>
        </div>
<?php
    }
}
?>