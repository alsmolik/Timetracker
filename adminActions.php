<?php
session_start();
session_regenerate_id();
if (!isset($_SESSION['username']) || $_SESSION['is_admin'] != 1) {
    echo 'ERR';
    exit();
}

require_once("classes/DB.class.php");
require_once("classes/Users.class.php");

$mysqli = (new DB())->getConnection();
$users = new Users($mysqli);

switch ($_POST['action']) {
    case 'delUser':
        if ($users->delUser($_POST['id'])) {
            echo 'OK';
        } else {
            echo 'ERR';
        }
        break;
    case 'changeUserStatus':
        if ($users->changeUserStatus($_POST['id'])) {
            echo 'OK';
        } else {
            echo 'ERR';
        }
        break;
}
?>