<?php
$request = $_SERVER["REQUEST_URI"];
include_once "menu_links.php";
?>
<div class="d-flex min-h-100 sidebar">
    <div class="d-flex flex-column font-pop w-100">
        <div class="w-100 text-center">
            <img src="<?php echo get_link("") ?>libs/img/aminhabiblioteca-high-resolution-logo-transparent.png" class="sidebarlogo" alt="AMinhaBiblioteca">
        </div>
        <div class="w-100 sidebarlinha mt-1"></div>
        <div class="mt-2">
            <?php
            foreach ($links_base as $link) {
                $active = ($request == $link['url'] || (is_array($link['related']) &&
                    array_reduce($link['related'], function ($carry, $item) use ($request) {
                        return $carry || strpos($request, $item) !== false;
                    }, false))) ? "active" : "";
                echo '<a class="nav-link my-1 ' . $active . '" href="' . $link['url'] . '">
            <div class="bordinhaazul">
                <img src="' . get_link("") . 'libs/img/Rectangle_17.png" alt="Rectangle_17">
            </div>
            <div class="nav-links-label">
                <span class="material-symbols-rounded sidebaricon icon-30">
                    ' . $link['icon'] . '
                </span>
                <span class="ms-2 span-links">' . $link['label'] . '</span>
            </div>
        </a>';
            }
            ?>
        </div>
        <div>
            <div>
                <div class="d-flex justify-content-center align-items-center">
                    <span class="pages-span-text"><?php echo $accpages ?></span>
                    <?php
                    if ($user_type != 3) {
                        $showaccpages = ($user_type != 3) ? "sidebar-modal" : "";
                    ?>
                        <button class="pages-span-button" type="button" id="accpages-show">
                            <span class="material-symbols-rounded" id="iconadd-acc-show">
                                add
                            </span>
                            <span class="material-symbols-rounded iconhidden" id="iconremove-acc-show">
                                remove
                            </span>
                        </button>
                    <?php
                    }
                    ?>

                </div>

                <div class="<?php echo $showaccpages ?>" id="modal-accpages">
                    <?php
                    foreach ($links_contas as $link) {
                        $active = ($request == $link['url'] || (is_array($link['related']) &&
                            array_reduce($link['related'], function ($carry, $item) use ($request) {
                                return $carry || strpos($request, $item) !== false;
                            }, false))) ? "active" : "";
                        echo '<a class="nav-link my-1 ' . $active . '" href="' . $link['url'] . '">
            <div class="bordinhaazul">
                <img src="' . get_link("") . 'libs/img/Rectangle_17.png" alt="Rectangle_17">
            </div>
            <div class="nav-links-label">
                <span class="material-symbols-rounded sidebaricon icon-30">
                    ' . $link['icon'] . '
                </span>
                <span class="ms-2 span-links">' . $link['label'] . '</span>
            </div>
        </a>';
                    }
                    ?>
                </div>
            </div>
            <?php
            if ($user_type == 2 || $user_type == 1) {
                $showstaffpages = ($user_type == 2) ? "sidebar-modal-open" : "";
                $showstafficonadd = ($user_type == 2) ? "iconhidden" : "";
                $showstafficonrem = ($user_type == 2) ? "" : "iconhidden";
            ?>
                <div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="pages-span-text"><?php echo $staffpages ?></span>
                        <button class="pages-span-button" type="button" id="staffpages-show">
                            <span class="material-symbols-rounded <?php echo $showstafficonadd ?>" id="iconadd-staff-show">
                                add
                            </span>
                            <span class="material-symbols-rounded <?php echo $showstafficonrem ?>" id="iconremove-staff-show">
                                remove
                            </span>
                        </button>
                    </div>

                    <div class="sidebar-modal <?php echo $showstaffpages ?>" id="modal-staffpages">
                        <?php
                        foreach ($links_staff as $link) {
                            $active = ($request == $link['url'] || (is_array($link['related']) &&
                                array_reduce($link['related'], function ($carry, $item) use ($request) {
                                    return $carry || strpos($request, $item) !== false;
                                }, false))) ? "active" : "";
                            echo '<a class="nav-link my-1 ' . $active . '" href="' . $link['url'] . '">
            <div class="bordinhaazul">
                <img src="' . get_link("") . 'libs/img/Rectangle_17.png" alt="Rectangle_17">
            </div>
            <div class="nav-links-label">
                <span class="material-symbols-rounded sidebaricon icon-30">
                    ' . $link['icon'] . '
                </span>
                <span class="ms-2 span-links">' . $link['label'] . '</span>
            </div>
        </a>';
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
            <?php
            if ($user_type == 1) {
                $showadminpages = ($user_type == 1) ? "sidebar-modal-open" : "";
                $showsadminiconadd = ($user_type == 1) ? "iconhidden" : "";
                $showadminiconrem = ($user_type == 1) ? "" : "iconhidden";
            ?>
                <div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="pages-span-text"><?php echo $adminpages ?></span>
                        <button class="pages-span-button" type="button" id="adminpages-show">
                            <span class="material-symbols-rounded <?php echo $showsadminiconadd ?>" id="iconadd-admin-show">
                                add
                            </span>
                            <span class="material-symbols-rounded <?php echo $showadminiconrem ?>" id="iconremove-admin-show">
                                remove
                            </span>
                        </button>
                    </div>

                    <div class="sidebar-modal <?php echo $showadminpages ?>" id="modal-adminpages">
                        <?php
                        foreach ($links_admin as $link) {
                            $active = ($request == $link['url'] || (is_array($link['related']) &&
                                array_reduce($link['related'], function ($carry, $item) use ($request) {
                                    return $carry || strpos($request, $item) !== false;
                                }, false))) ? "active" : "";
                            echo '<a class="nav-link my-1 ' . $active . '" href="' . $link['url'] . '">
            <div class="bordinhaazul">
                <img src="' . get_link("") . 'libs/img/Rectangle_17.png" alt="Rectangle_17">
            </div>
            <div class="nav-links-label">
                <span class="material-symbols-rounded sidebaricon icon-30">
                    ' . $link['icon'] . '
                </span>
                <span class="ms-2 span-links">' . $link['label'] . '</span>
            </div>
        </a>';
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="mt-auto d-flex flex-row align-items-center mb-2 px-4 font-pop">
            <button class="setCookieBtn btn pe-1 primary nav-link <?php if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "pt") {
                                                                        echo "active";
                                                                    } ?>" name="pt">
                <span class="span-lang">pt</span>
                <div class="bordinhaazulhorizontal position-relative">
                    <img src="<?php echo get_link("")?>libs/img/Rectangle_18.png" alt="Rectangle_18.png">
                </div>
            </button>
            <button class="setCookieBtn btn ps-1 primary nav-link <?php if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == "eng") {
                                                                        echo "active";
                                                                    } ?>" name="eng">
                <span class="span-lang">eng</span>
                <div class="bordinhaazulhorizontal position-relative">
                    <img src="<?php echo get_link("")?>libs/img/Rectangle_18.png" alt="Rectangle_18.png">
                </div>
            </button>
            <button class="btn-icon ms-auto" onclick="window.location.href = '<?php echo get_link('logout') ?>';">
                <span class="material-symbols-rounded icon-35 icon-acc">
                    logout
                </span>
            </button>
        </div>
    </div>
</div>