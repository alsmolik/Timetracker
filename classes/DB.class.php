<?php
class DB {
    private $mysqli;

    public function getConnection() {
        return $this->mysqli;
    }

    public function __construct() {
        $this->mysqli = new mysqli("localhost", "root", "", "Timetracker");
        $this->mysqli->set_charset("utf8");

        if ($this->mysqli->connect_errno) {
            printf("Не удалось подключиться: %s\n", $this->mysqli->connect_error);
            exit();
        }
    }

}
?>