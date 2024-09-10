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

    $adicionar = $controlo->adicionar_favorito($user_id, $bookid);
    if ($adicionar['success']) {
?>
        <span class="icon-30 material-symbols-rounded" id="<?php echo $bookid ?>">
            bookmark_added
        </span>
    <?php
    } else {
    ?>
        <span class="icon-30 material-symbols-rounded" id="<?php echo $bookid ?>">
            more
        </span>
<?php
    }
}
?>