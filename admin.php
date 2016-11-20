<?php
session_start();
session_regenerate_id();
if (!isset($_SESSION['username']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

require_once("classes/DB.class.php");
require_once("classes/Users.class.php");

$mysqli = (new DB())->getConnection();
$users = new Users($mysqli);

if (!$usersResult = $users->getUsers()) {
    die("Произошла ошибка при загрузке пользователей");
}

?>

<html>
    <head>
        <title>Админка</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    </head>
    <body>
        <a href="calendar.php">Вернуться к календарю</a><br/><br/>
        <table border='1'>
            <tr>
                <td>Логин</td>
                <td>Статус</td>
                <td>Действия</td>
            </tr>
            <?php
            while ($user = $usersResult->fetch_assoc()) {
                echo "<tr>
                <td>{$user['username']}</td>
                <td>{$user['is_active']}</td>
                <td>
                    <button onclick='changeUserStatus({$user['id']})'>Изменить статус</button> <br/>
                    <button onclick='delUser({$user['id']})'>Удалить</button>
                </td>
                </tr>";
            }
            ?>
        </table>
        
        <script>
            function delUser (id) {
                result = confirm("Вы действительно хотите удалить пользователся (id " + id + ")?");
                if (result)
                $.post('adminActions.php', {action: 'delUser', id: id})
                .done(function(result){  
                    if (result == 'OK') {
                        alert('Пользователь успешно удален');
                        location.reload();
                    } else {
                        alert('Произошла ошибка при удалении пользователя');
                    }
                })
                .fail(function(xhr, status, error) {
                    alert('Произошла ошибка при удалении пользователя');
                });
            }

            function changeUserStatus (id) {
                result = confirm("Изменить статус пользователя (id " + id + ")?");
                if (result)
                $.post('adminActions.php', {action: 'changeUserStatus', id: id})
                .done(function(result){  
                    if (result == 'OK') {
                        alert('Статус пользователя успешно изменен');
                        location.reload();
                    } else {
                        alert('Произошла ошибка при изменении статуса пользователя');
                    }
                })
                .fail(function(xhr, status, error) {
                    alert('Произошла ошибка при изменении статуса пользователя');
                });
            }
        </script>

    </body>
</html>