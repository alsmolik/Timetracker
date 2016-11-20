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

if (!$tasksResult = $tasks->getTasks()) {
    die("Произошла ошибка при загрузке списка задач");
}
?>

<html>
    <head>
        <title>Задачи</title>
    </head>
    <body>
        <a href="calendar.php">Вернуться к календарю</a><br/>
        <p><button type="button" onclick="location.href='addTask.php';">Добавить задачу</button></p>
        <table border="1">
            <tr>
                <td>Id</td>
                <td>Название</td>
                <td>Автор</td>
                <td>Исполнитель</td>
            </tr>
            <?php
                while ($task = $tasksResult->fetch_assoc()) {
                    echo "<tr>
                    <td>{$task['id']}</td>
                    <td><a href='task.php?id={$task['id']}'>{$task['name']}</a></td>
                    <td>{$task['author_name']}</td>
                    <td>{$task['performer_name']}</td>
                    </tr>";
                }
            ?>
        </table>
    </body>
</html>