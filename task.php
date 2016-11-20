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

if (!$task = $tasks->getTask($_GET['id'])) {
    die("Произошла ошибка при загрузке задачи");
}
?>

<html>
    <head>
        <title>Задача</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="css/jquery.datetimepicker.min.css">
        <script src="js/jquery.datetimepicker.full.min.js"></script>
    </head>
    <body>
       <a href="tasks.php">К списку задач</a><br/><br/>
       <b>Id:</b> <?=$task['id']?><br/>
       <b>Название:</b> <?=$task['name']?><br/>
       <b>Описание:</b> <?=$task['description']?><br/>
       <b>Автор:</b> <?=$task['author_name']?><br/>
       <b>Исполнитель:</b> <?=$task['performer_name']?><br/><br/>

       <?php
            if ($_SESSION['id'] != $task['performer']) {
                echo "<form action='takeTask.php' method='post'>
                <input type='hidden' name='id' value='{$_GET['id']}'>
                <button type='submit'>Взять себе</button>
                </form>";
            } else {
                echo "<h3>Залогать время</h3>
                <form action='logTime.php' method='post'>
                Дата: <input name='date' id='datepicker' type='text' required><br/>
                Время: <input name='time' id='timepicker' type='text' required>
                <input type='hidden' name='id' value='{$_GET['id']}'>
                <input type='hidden' name='callback' value='task.php?id={$_GET['id']}'>
                <p><button type='submit'>Залогать время</button>
                </form>
                
                <script>
                jQuery('#datepicker').datetimepicker({timepicker:false, format:'d.m.Y'});
                jQuery('#timepicker').datetimepicker({datepicker:false, format:'H:i'});
                </script>";
            }
       ?>
    </body>
</html>