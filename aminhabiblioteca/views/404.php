<?php
include_once "verifica-sessao.php";

?>
<div class="pagina d-flex">

    <?php require_once "views/menu/menu.php" ?>
    <div class="h-100 w-85 d-flex justify-content-center align-items-center background-404 poppins-font">
        <div class="text-center">
            <p class="m-0 color-text p-404">404</p>
            <p class="m-0 color-text p-mens1"><?php echo $mens1_404 ?></p>
            <p class="m-0 color-primary p-mens2"><?php echo $mens2_404 ?></p>
        </div>
    </div>
</div>