<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['texto']) && isset($_POST['tipo'])) {
    $texto = $_POST['texto'];
    $tipo = $_POST['tipo'];
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user_type = $_SESSION['user_dados']['type'];
    $listar_tickets = $controlo->pesquisa_ticket($texto, $tipo);
    if ($listar_tickets['success']) {
        if (!empty($listar_tickets['data'])) {
            foreach ($listar_tickets['data'] as $item) {
?>
                <tr class="texto-font">
                    <td class="coluna-200">
                        <p class="m-0 truncate-texto"><?php echo (!empty($item['first_name']) && !empty($item['last_name'])) ? $item['first_name'] . " " .  $item['last_name'] : $item['username'] ?></p>
                        <p class="m-0 truncate-texto opacidade-60"><?php echo $item['email'] ?></p>
                    </td>
                    <td class="coluna-max-10">
                        <p class="m-0 truncate-texto"><?php echo $item['title'] ?></p>
                        <p class="m-0 truncate-texto opacidade-60"><?php echo $item['description'] . $item['description'] . $item['description'] . $item['description'] . $item['description'] ?></p>
                    </td>
                    <td class="coluna-200">
                        <p class="m-0"><?php echo $item['tipo1'] ?></p>
                        <p class="m-0 opacidade-60"><?php echo $item['tipo2'] ?></p>
                    </td>
                    <td class="text-center coluna-200">
                        <?php
                        if ($item['status']) {
                        ?>
                            <span class="btn-vazio-borda-2 color-primary">
                                <?php echo $aberto ?>
                            </span>
                        <?php
                        } else {
                        ?>
                            <span class="btn-back-primary color-back">
                                <?php echo $fechado ?>
                            </span>
                        <?php
                        }
                        ?>
                    </td>
                    <td class="text-center coluna-200"><?php echo $item['created_at'] ?></td>
                    <td class="text-center coluna-200">
                        <?php
                        if ($item['admin_response'] == 1 && $user_type == 2) {
                            echo $apenasadmin;
                        } else {
                        ?>
                            <form action="<?php echo get_link("detalhesticketstaff") ?>" method="post" target="_blank">
                                <input type="hidden" name="ticket_id" value="<?php echo $item['ticket_id'] ?>">
                                <input type="hidden" name="user_id" value="<?php echo $item['user_id'] ?>">
                                <button type="submit" class="text-decoration-none texto-font color-text border-0 bg-transparent" href="<?php echo get_link("procurarconta") ?>">
                                    <?php
                                    if ($item['status']) {
                                        echo $respoticket;
                                    } else {
                                        echo $detalhes;
                                    }
                                    ?>
                                </button>
                            </form>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">
                    <?php echo $pesqrapivazio ?>
                </td>
            </tr>
<?php
        }
    } else {
        echo $livros['data'];
    }
}
