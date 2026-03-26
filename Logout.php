<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
if (isset($_COOKIE['site_nuke_timer'])) {
    if (($_COOKIE['site_nuke_timer'] - time()) > 0) {
        if (basename($_SERVER['PHP_SELF']) != 'error.php') {
            header('Location: error.php'); // Redirige vers error.php au lieu de 404.php
            exit;
        }
    }
}
?>