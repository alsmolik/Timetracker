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

    $user = $users->auth($_POST['username'], $_POST['password']);

    if (is_array($user)) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        header("Location: calendar.php");
    } else {
        switch ($user) {
            case 0:
                echo "<script>alert('Возникла ошибка при авторизации. Пожалуйста, сообщите администратору, если ошибка будет повторяться');</script>";
                break;
            case -1:
                echo "<script>alert('Неверный логин/email или пароль');</script>";
                break;
            case -2:
                echo "<script>alert('Ваша учетная запись не активирована администратором');</script>";
                break;
        }
    }
}
?>

<html>
    <head>
        <title>Авторизация</title>
    </head>
    <body>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Логин или email" required autofocus>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
            <button type="button" onclick="location.href='signup.php';">Регистрация</button>
        </form>
    </body>
</html>