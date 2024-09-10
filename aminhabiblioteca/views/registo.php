<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    // Se a sessão não estiver ativa, a ativa
    session_start();
    // Verifica se a variável de sessão 'user_dados' está definida
    if (isset($_SESSION['user_dados'])) {
        header('Location: ' . get_link("") . '');
        exit;
    }
}
?>
<div id="alert-container" class="fade show position-absolute mt-4 start-50 translate-middle-x top-1 w-auto"></div> <!-- Container para os alerts -->
<div class="d-flex pagina-log-reg">
    <div class="log-reg seccao-esq-fundo">
        <div class="seccao-esq-content d-flex flex-column">
            <div class="seccao-esq-content-topo mb-auto">
                <div class="logo-container">
                    <img src="libs/img/logo_branco.png" alt="Logo Branco">
                </div>
                <hr class="mt-4">
                <div class="d-flex flex-row align-items-center font-pop">
                    <button class="setCookieBtn btn pe-1 primary nav-link log-reg-color <?php if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "pt") {
                                                                                            echo "active";
                                                                                        } ?>" name="pt">
                        <div class="bordinhabrancahorizontal position-relative">
                            <img src="libs/img/Rectangle_19.png">
                        </div>
                        <span class="span-lang">pt</span>
                    </button>
                    <button class="setCookieBtn btn ps-1 primary nav-link log-reg-color <?php if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == "eng") {
                                                                                            echo "active";
                                                                                        } ?>" name="eng">
                        <div class="bordinhabrancahorizontal position-relative">
                            <img src="libs/img/Rectangle_19.png">
                        </div>
                        <span class="span-lang">eng</span>
                    </button>
                </div>
            </div>
            <div class="seccao-esq-content-centro">
                <span>
                    <?php echo $boas_vindas; ?>
                </span>
            </div>
            <div class="seccao-esq-content-fundo d-flex flex-column mt-auto">
                <span>
                    <?php echo $tem_conta; ?>
                </span>
                <span>
                    <a href="<?php echo get_link("login") ?>"><?php echo $login; ?></a>
                </span>
            </div>
        </div>
    </div>
    <div class="log-reg seccao-direita-fundo reg-pad">
        <form class="h-100 d-flex flex-column" id="registo-form">
            <span class="form-title mb-auto">
                <?php echo $registo; ?>
            </span>
            <div class="my-auto">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control input-form text-form" id="reg-email" placeholder="<?php echo $ph_email; ?>" required>
            </div>
            <div class="my-auto">
                <label for="username" class="form-label"><?php echo $username; ?></label>
                <input type="text" class="form-control input-form text-form" id="reg-username" placeholder="<?php echo $ph_username; ?>" required>
            </div>
            <div class="my-auto">
                <label for="pass" class="form-label"><?php echo $password; ?></label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control input-form password-form" placeholder="<?php echo $ph_password; ?>" required id="reg-pass" aria-describedby="pass-show">
                    <button class="btn d-flex btn-pass-input" type="button" id="reg-pass-show">
                        <span class="material-symbols-rounded my-auto icon-35" id="visibility-icon-pass">
                            visibility
                        </span>
                    </button>
                </div>
                <div class="input-group mt-3">
                    <input type="password" class="form-control input-form password-form" placeholder="<?php echo $ph_confi_pass; ?>" required id="reg-confi-pass" aria-describedby="confi-pass-show">
                    <button class="btn d-flex btn-pass-input" type="button" id="reg-confi-pass-show">
                        <span class="material-symbols-rounded my-auto icon-35" id="visibility-icon-confi-pass">
                            visibility
                        </span>
                    </button>
                </div>
            </div>
            <div class="form-check my-auto check-form d-flex">
                <input class="form-check-input check-form-box my-auto me-1" type="checkbox" value="" id="aceitar-privacidade">
                <label class="form-check-label check-form-label my-auto" for="aceitar-privacidade">
                    <a class="color-text text-decoration-none" target="_blank" href="<?php echo get_link("termospoliticaprivacidade") ?>">
                        <?php echo $aceitar_privacidade; ?>
                    </a>
                </label>
            </div>
            <button class="btn btn-primary w-100 btn-form mt-auto" id="submit-reg" disabled type="submit"><?php echo $registo; ?></button>
        </form>
    </div>
</div>
<script src="libs/js/log-reg.js"></script>