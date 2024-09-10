<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['user_dados']['type'] != 2) {
    if ($_SESSION['user_dados']['type'] != 1) {
        header('Location: ' . get_link(""));
    }
}
