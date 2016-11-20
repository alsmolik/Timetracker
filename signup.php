<?php
session_start();
session_regenerate_id();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once("classes/DB.class.php");
require_once("classes/Users.class.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $mysqli = (new DB())->getConnection();
    $users = new Users($mysqli);

    switch ($users->signup($_POST['username'], $_POST['email'], $_POST['password'])) {
        case 1:
            echo "<script>alert('Ваш запрос отправлен администратору');</script>";
            break;
        case 0:
            echo "<script>alert('Произошла ошибка при регистрации. Пожалуйста, сообщите администратору, если ошибка будет повторяться');</script>";
            break;
        case -1:
            echo "<script>alert('Данный логин или email уже используется');</script>";
            break;
    }
}
?>

<html>
    <head>
        <title>Регистрация</title>
    </head>
    <body>
        <form action="signup.php" method="post">
            <input type="text" name="username" placeholder="Логин" required autofocus>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </body>
</html>