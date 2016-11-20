<?php
session_start();
session_regenerate_id();
if (isset($_SESSION['username'])) {
    header("Location: calendar.php");
} else {
    header("Location: login.php");
}
?>