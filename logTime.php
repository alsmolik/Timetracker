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

if ($tasks->logTime($_POST['id'], $_SESSION['id'], $_POST['date'], $_POST['time'])) {
    echo "<script>
        alert('Время успешно залогано');
        window.location.href = '{$_POST['callback']}';
    </script>";
} else {
    echo "<script>
        alert('Произошла ошибка');
        window.location.href = '{$_POST['callback']}';
    </script>";
}
?>