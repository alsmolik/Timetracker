<?php
session_start();
session_regenerate_id();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once("classes/DB.class.php");
require_once("classes/Tasks.class.php");

$mysqli = (new DB())->getConnection();
$tasks = new Tasks($mysqli);

if ($tasks->takeTask($_POST['id'], $_SESSION['id'])) {
    header("Location: task.php?id={$_POST['id']}");
} else {
    die("Произошла ошибка");
}
?>