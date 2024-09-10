<?php
include_once "verifica-sessao.php";
$link = 'controlo/user-controlo.php';
require_once $link;
$controlo = new user_controlo();
$listar_tickets = $controlo->listar_todos_tickets();
$listar_ticket_types = $controlo->listar_ticket_types();
?>
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="w-85 d-flex flex-column">
        <div class="identificacao d-flex mx-3">
            <div class="my-auto identificacao-texto texto-font d-flex flex-column">
                <span>
                    <a href="<?php echo get_link("") ?>" class="color-primary"><?php echo $adminpages ?> / </a>
                    <a href="<?php echo get_link("gerirtickets") ?>" id="identidade-site" class="color-text"><?php echo $gertick ?></a>
                </span>
                <span class="color-text"><?php echo $listatickets ?></span>
            </div>
            <?php include_once "views/notif-img.php" ?>
        </div>
        <div class="grid-gerirtickets px-5 pt-3">
            <div></div>
            <div class="col-item s-back-1 table-box d-flex align-items-center p-3">
                <table class="tabela-suporte tabela-gerirtickets">
                    <tr>
                        <td colspan="2" class="coluna-max-10">
                            <div class="input-group flex-nowrap me-5 w-75">
                                <span class="input-group-text material material-symbols-rounded color-accent bg-transparent input-dark-border-icon" id="addon-wrapping">search</span>
                                <input type="text" id="gerir-ticket-pesquisa-texto" class="pesquisa-ticket-form texto-font form-control bg-transparent color-text input-dark-border" placeholder="<?php echo $procnomeemailcodigo ?>" aria-label="Username" aria-describedby="addon-wrapping">
                            </div>
                        </td>
                        <td class="coluna-200">
                            <div class="form-floating select-input">
                                <select class="pesquisa-ticket-form form-select color-text" id="gerir-ticket-pesquisa-tipo" aria-label="Floating label select example">
                                    <option selected value="all"><?php echo $todos ?></option>
                                    <?php
                                    foreach ($listar_ticket_types['data'] as $item) {
                                    ?>
                                        <option class="texto-font" value="<?php echo $item['type_id'] ?>"><?php echo $item['type_name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <label for="pesquisa-genero" class="texto-pequeno-font color-accent"><?php echo $tipo ?></label>
                            </div>
                        </td>
                        <td class="coluna-200">
                            <div class="d-flex flex-row w-100 h-100 justify-content-center align-items-center">
                                <button type="button" class="btn-open p-3 py-2 color-text texto-font bg-transparent"><?php echo $aberto ?></button>
                                <button type="button" class="btn-closed px-3 py-2 color-text texto-font bg-transparent"><?php echo $fechado ?></button>
                            </div>
                        </td>
                        <td class="text-center coluna-200">
                            <div class="d-flex flex-row w-100 h-100 justify-content-center align-items-center">
                                <button class="d-flex flex-row align-items-center justify-content-center border-10 border-1-primary bg-transparent" type="button" id="invertRows">
                                    <span class="color-text texto-font"><?php echo $ordenardata ?></span>
                                    <div class="d-flex flex-column color-accent">
                                        <span class="material material-symbols-rounded icon-25 mb-neg5 opacidade-60">arrow_drop_up</span>
                                        <span class="material material-symbols-rounded icon-25 mt-neg5">arrow_drop_down</span>
                                    </div>
                                </button>
                            </div>
                        </td>
                        <td class="text-center coluna-200">
                        <button class="btn-vazio-borda-2 subtitulo-font color-text" id="reload-btn" type="button"><?php echo "Reset"; ?></button>
                        </td>
                    </tr>
                </table>
            </div>
            <div></div>
            <div class="s-back-1 srcoll-div col-item table-box p-3">
                <table class="color-text tabela-suporte" id="myTable">
                    <thead>
                        <tr class="subtitulo-font text-uppercase">
                            <td><?php echo $user ?></td>
                            <td><?php echo $titulodescricao ?></td>
                            <td class="coluna-200"><?php echo $tipo ?></td>
                            <td class="text-center coluna-200"><?php echo $estado ?></td>
                            <td class="text-center coluna-200">
                                <span class="color-text text-uppercase"><?php echo $data ?></span>
                            </td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody id="pesquisa-conteudo">
                        <?php
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
                        ?>
                    </tbody>

                </table>
                <script>
                    $(document).ready(function() {
                        var isFilteredOpen = false; // Variável para rastrear o estado do filtro para "Aberto"
                        var isFilteredClosed = false; // Variável para rastrear o estado do filtro para "Fechado"

                        $(".btn-open").click(function() {
                            var filterValue = "<?php echo $aberto; ?>"; // Valor a ser filtrado

                            if (isFilteredOpen) {
                                // Se já estiver filtrado, mostrar todas as linhas
                                $("#myTable tbody tr").show();
                                isFilteredOpen = false; // Resetar o estado do filtro
                            } else {
                                // Oculta todas as linhas da tabela
                                $("#myTable tbody tr").hide();

                                // Mostra as linhas que contêm o valor dentro do <span> na 4ª coluna
                                $("#myTable tbody tr").filter(function() {
                                    return $(this).find('td:eq(3) span').text().trim() === filterValue;
                                }).show();

                                isFilteredOpen = true; // Atualizar o estado do filtro
                                isFilteredClosed = false; // Resetar o estado do outro filtro
                            }
                        });

                        $(".btn-closed").click(function() {
                            var filterValue = "<?php echo $fechado; ?>"; // Valor a ser filtrado

                            if (isFilteredClosed) {
                                // Se já estiver filtrado, mostrar todas as linhas
                                $("#myTable tbody tr").show();
                                isFilteredClosed = false; // Resetar o estado do filtro
                            } else {
                                // Oculta todas as linhas da tabela
                                $("#myTable tbody tr").hide();

                                // Mostra as linhas que contêm o valor dentro do <span> na 4ª coluna
                                $("#myTable tbody tr").filter(function() {
                                    return $(this).find('td:eq(3) span').text().trim() === filterValue;
                                }).show();

                                isFilteredClosed = true; // Atualizar o estado do filtro
                                isFilteredOpen = false; // Resetar o estado do outro filtro
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>