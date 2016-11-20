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

if (!$userTasks = $tasks->getUserTasks($_SESSION['id'])) {
    die("Произошла ошибка при загрузке задач");
}
?>

<html>
    <head>
        <title>Календарь</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/jquery.datetimepicker.min.css">
        <script src="js/jquery.datetimepicker.full.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Timetracker</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Календарь</a></li>
                    <li><a href="tasks.php">Задачи</a></li>
                    <?php 
                        if ($_SESSION['is_admin'] == 1) {
                            echo "<li><a href='admin.php'>Админка</a></li>";
                        }
                    ?>
                    <li><a href="logout.php">Выйти</a></li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <table class="table table-striped table-bordered">
                <tr>
                    <td colspan="5"><center><?=$_SESSION['username']?></center></td>
                    <?php
                        if (isset($_GET['offset'])) {
                            $offset = $_GET['offset'] * 86400 * 7;
                            $week = $_GET['offset'] + 1;
                        } else {
                            $offset = 0;
                            $week = 1;
                        }

                        for ($i = 0; $i < 7; $i++) {
                            $time = time() + (86400 * $i) + $offset;
                            $dayOfWeek = date("D", $time);
                            echo "<td>{$dayOfWeek}</td>";
                        }
                    ?>
                    <td>Итог</td>
                </tr>
                <tr>
                    <td></td>
                    <td><center><a href="calendar.php?offset=<?=$week-2?>"><</a></center></td>
                    <td>week</td>
                    <td><?=$week;?></td>
                    <td><center><a href="calendar.php?offset=<?=$week?>">></a></center></td>
                    <?php
                        for ($i = 0; $i < 7; $i++) {
                            $time = time() + (86400 * $i) + $offset;
                            $dayOfMonth = date("d M", $time);
                            echo "<td>{$dayOfMonth}</td>";
                        }
                    ?>
                    <td></td>
                </tr>
                <?php
                    $colSumm = array(0, 0, 0, 0, 0, 0, 0, 0);
                    while ($task = $userTasks->fetch_assoc()) {
                        echo "<tr>
                        <td></td>
                        <td>задание {$task['id']}</td>
                        <td colspan='3'>{$task['name']}</td>";
                        $rowSumm = 0;
                        for ($i = 0; $i < 7; $i++) {
                            $time = time() + (86400 * $i) + $offset;
                            $date = date("Y-m-d", $time);
                            $dayTime = DateTime::createFromFormat("H:i:s", $tasks->getDayTime($task['id'], $_SESSION['id'], $date));
                            if ($dayTime) { 
                                $dayTime = $dayTime->format('H:i');
                                $parts = explode(':', $dayTime);
                                $sec = ($parts[0] * 3600) + ($parts[1] * 60);
                                $rowSumm += $sec;
                                $colSumm[$i] += $sec;
                                $colSumm[7] += $sec;
                            }
                            echo "<td><a data-toggle='modal' href='#logTimeModal' class='openLogTimeModal' date='" . date("d.m.Y", $time) . "' 
                            taskId='{$task['id']}'>+</a><br/>{$dayTime}</td>";
                        }
                        echo "<td>" . gmdate("H:i", $rowSumm) . "</td>
                        </tr>";
                    }
                ?>
                <tr>
                    <td></td>
                    <td colspan="4">Итог</td>
                    <td><?=gmdate("H:i", $colSumm[0])?></td>
                    <td><?=gmdate("H:i", $colSumm[1])?></td>
                    <td><?=gmdate("H:i", $colSumm[2])?></td>
                    <td><?=gmdate("H:i", $colSumm[3])?></td>
                    <td><?=gmdate("H:i", $colSumm[4])?></td>
                    <td><?=gmdate("H:i", $colSumm[5])?></td>
                    <td><?=gmdate("H:i", $colSumm[6])?></td>
                    <td><?=gmdate("H:i", $colSumm[7])?></td>
                </td>
            </table>
        </div>

        <div id="logTimeModal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Залогать время</h4>
                    </div>
                    <div class="modal-body">
                        <form action='logTime.php' method='post'>
                            <div class="form-group">
                                <label for="date">Дата:</label>
                                <input class="form-control" name='date' id='date' type='text' required>
                            </div>
                            <div class="form-group">
                                <label for="time">Время:</label>
                                <input class="form-control" name='time' id='time' type='text' required>
                            </div>
                            <input type='hidden' name='id' id="taskId" value=''>
                            <input type='hidden' name='callback' value='calendar.php?offset=<?=$week-1?>'>
                            <p><button type='submit' class="btn btn-default">Залогать время</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).on("click", ".openLogTimeModal", function () {
                $("#date").val($(this).attr("date"));
                $("#taskId").val($(this).attr("taskId"));
            });

            jQuery('#date').datetimepicker({timepicker:false, format:'d.m.Y'});
            jQuery('#time').datetimepicker({datepicker:false, format:'H:i'});
        </script>

    </body>
</html>