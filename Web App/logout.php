<?php
session_start();
switch ($_GET['destroy']) {
    case 'username':
        unset($_SESSION['username']);
        header('location: /');
        break;
    case 'admin':
        unset($_SESSION['super_admin']);
        header('location: /admin');
        break;
    default:
        session_destroy();
        header('location: /');
        break;
}
