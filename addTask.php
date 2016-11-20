<?php
session_start();
session_regenerate_id();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once("classes/DB.class.php");
require_once("classes/Tasks.class.php");

if (isset($_POST['name']) && isset($_POST['description'])) {
    $mysqli = (new DB())->getConnection();
    $tasks = new Tasks($mysqli);

    if ($tasks->addTask($_POST['name'], $_POST['description'], $_SESSION['id'])) {
        echo "<script>alert('Задача успешно добавлена');
        window.location.href='tasks.php';</script>";
    } else {
        echo "<script>alert('Произошла ошибка при добавлении задачи. Пожалуйста, сообщите администратору, если ошибка будет повторяться');
        window.location.href='tasks.php';</script>";
    }
}
?>

<html>
    <head>
        <title>Новая задача</title>
    </head>
    <body>
        <a href="tasks.php">К списку задач</a><br/><br/>
        <form action="addTask.php" method="post">
            <input type="text" name="name" placeholder="Название" required autofocus>
            <input type="text" name="description" placeholder="Описание" required>
            <button type="submit">Добавить</button>
        </form>
    </body>
</html>