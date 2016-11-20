<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['id']);
unset($_SESSION['is_admin']);
session_destroy();
header("Location: login.php");
?>