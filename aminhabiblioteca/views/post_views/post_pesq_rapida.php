<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new controlo();

if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    if ($nome != null || $nome != "") {
        $resultados = $controlo->pesquisa_rapida_home($nome);

?>
        <table class="texto-font color-text">
            <?php
            // Verificar se há resultados
            if (!empty($resultados['data'])) {
                foreach ($resultados['data'] as $result) {
                    $link = "";
                    if ($result['type'] == 'book') {
                        $link = get_link_completo("livro", $result['internal_code']);
                    } elseif ($result['type'] == 'author') {
                        $link = get_link_completo("autor", $result['id']);
                    }
            ?>
                    <tr data-href="<?php echo $link; ?>">
                        <td>
                            <?php
                            if ($result['type'] == 'book') {
                            ?>
                                <a href="<?php echo $link; ?>"><img src="libs/img/book-covers/<?php echo $result['fcover']; ?>" alt="<?php echo $result['name']; ?>"></a>
                            <?php
                            } elseif ($result['type'] == 'author') {
                            ?>
                                <a href="<?php echo $link; ?>"><img src="libs/img/author-pics/<?php echo $result['photo']; ?>" alt="<?php echo $result['name']; ?>"></a>
                            <?php
                            }
                            ?>
                        <td>
                        <td><a href="<?php echo $link; ?>"><?php echo $result['name']; ?></a></td>
                    </tr>
                <?php
                }
            } else {
                // Exibir mensagem se não houver resultados
                ?>
                <tr>
                    <td>
                        <?php echo $pesqrapivazio ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
<?php
    }
}
?>