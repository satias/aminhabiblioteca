<?php
$rootDirectory = dirname(dirname(__DIR__));
$link = $rootDirectory . '/controlo/user-controlo.php';
$link2 = $rootDirectory . '/funcoes/funcoes.php';
require_once $link;
include_once $link2;
$controlo = new user_controlo();

if (isset($_POST['nome'])) {
    $user = $_POST['nome'];
    if ($nome != null || $nome != "") {
        $resultados = $controlo->pesquisar_utilizadores($user);

?>
        <table class="texto-font color-text">
            <tbody>
                <?php
                $itemcount = 0;
                // Verificar se há resultados
                if (!empty($resultados['data'])) {
                    foreach ($resultados['data'] as $item) {
                ?>
                        <tr class="clickable-row" data-id="<?php echo $item['id']; ?>" style="cursor: pointer;">
                            <!-- <form action="<?php echo get_link("procurarconta") ?>" method="post"> -->
                            <td>
                                <div class="avatar-ball<?php echo $itemcount ?> rounded-circle m-auto"></div>
                                <style>
                                    .avatar-ball<?php echo $itemcount ?> {
                                        width: 3rem;
                                        height: 3rem;
                                        background-color: #dadada;
                                        overflow: hidden;
                                        background-repeat: no-repeat;
                                        background-position: 50% 50%;
                                        background-size: cover;
                                        background-image: url('libs/img/img-perfil/<?php echo $item['photo_url'] ?>');
                                    }
                                </style>
                            </td>
                            <td>
                                <!-- <input type="hidden" name="post_username" value="<?php echo $item['id'] ?>"> -->
                                <?php echo $item['id'] ?>
                            </td>
                            <td><?php echo $item['first_name'] . " " . $item['last_name'] ?></td>
                            <td><?php echo $item['email'] ?></td>
                            <td><?php echo $item['username'] ?></td>
                            <!-- </form> -->
                        </tr>
                        <!-- Criação de um formulário separado para submissão de dados -->
                        <!-- <form id="form-<?php echo $item['id']; ?>" action="<?php echo get_link('procurarconta'); ?>" method="post" class="d-none">
                            <input type="hidden" name="post_username" value="<?php echo $item['id']; ?>">
                        </form> -->
                    <?php
                        $itemcount++;
                    }
                } else {
                    // Exibir mensagem se não houver resultados
                    ?>
                    <tr>
                        <td colspan="5">
                            <?php echo $pesqrapivazio ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <?php 
            $link_pag = $_SERVER["REQUEST_URI"];
            $link = "";
            $linkconta = get_link("procurarconta");
            $linkutilizador = get_link("procurarutilizador");
            if($link_pag == $linkconta){
                $link = get_link("procurarconta");
            }else if($link_pag == $linkutilizador){
                $link = get_link("procurarutilizador");
            }
        ?>
        <script>
            $(document).ready(function() {
                // Submete o formulário correspondente quando a linha é clicada
                $(".clickable-row").on("click", function() {
                    var userId = $(this).data("id"); // Obtém o ID do usuário a partir do atributo data-id

                    // Cria dinamicamente o formulário
                    var form = $('<form>', {
                        'action': '<?php echo $link; ?>',
                        'method': 'POST'
                    }).append($('<input>', {
                        'type': 'hidden',
                        'name': 'post_username',
                        'value': userId
                    }));

                    // Adiciona o formulário ao body e submete
                    form.appendTo('body').submit();
                });
            });
        </script>
<?php
    }
}
?>