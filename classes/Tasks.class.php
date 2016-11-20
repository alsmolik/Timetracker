<?php

class Tasks {
    private $mysqli;

    function __construct ($mysqli) {
        $this->mysqli = $mysqli;
    }

    function addTask ($name, $description, $author) {
        $stmt = $this->mysqli->prepare('INSERT INTO Tasks (name, description, author) VALUES (?, ?, ?)');
        if (!$stmt) return 0;
        $stmt->bind_param('sss', $name, $description, $author);
        $stmt->execute();
        if (!$stmt->affected_rows) return 0;      

        return 1;
    }

    function getTasks () {
        if ($result = $this->mysqli->query("SELECT Tasks.*, ua.username as author_name, up.username as performer_name FROM Tasks 
        LEFT JOIN Users as ua
        ON Tasks.author = ua.id
        LEFT JOIN Users as up
        ON Tasks.performer = up.id")) {
            return $result;
        } else {
            return false;
        }  
    }

    function getTask ($id) {
        $stmt = $this->mysqli->prepare("SELECT Tasks.*, ua.username as author_name, up.username as performer_name FROM Tasks
        LEFT JOIN Users as ua
        ON Tasks.author = ua.id
        LEFT JOIN Users as up
        ON Tasks.performer = up.id
        WHERE Tasks.id = ?");
        if (!$stmt) return false;
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }  
    }

    function getUserTasks ($user) {
        $stmt = $this->mysqli->prepare("SELECT Tasks.* FROM Tasks
        INNER JOIN Users
        ON Tasks.performer = Users.id
        WHERE Users.id = ?");
        if (!$stmt) return false;
        $stmt->bind_param('i', $user);
        $stmt->execute();

        return $stmt->get_result();
    }

    function takeTask ($id, $user) {
        $stmt = $this->mysqli->prepare("UPDATE Tasks SET performer = ? WHERE id = ?");
        if (!$stmt) return 0;
        $stmt->bind_param('ss', $user, $id);
        $stmt->execute();
        if (!$stmt->affected_rows) return 0;      

        return 1;
    }

    function logTime ($task, $user, $date, $time) {
        $date = date("Y-m-d", strtotime($date));
        $time = date("H:i:s", strtotime($time));

        $stmt = $this->mysqli->prepare("SELECT * FROM Log WHERE task = ? AND user = ? AND date = ?");
        if (!$stmt) return 0;
        $stmt->bind_param('sss', $task, $user, $date);
        $stmt->execute();
        $result = $stmt->get_result();
       
        if ($result->num_rows) {
            $stmt = $this->mysqli->prepare("UPDATE Log SET time = AddTime(time, '{$time}') WHERE id = ?");
            if (!$stmt) return 0;
            $stmt->bind_param('s', $result->fetch_assoc()['id']);
            $stmt->execute();
            if (!$stmt->affected_rows) return 0;      

            return 1;
        } else {
            $stmt = $this->mysqli->prepare('INSERT INTO Log (task, user, date, time) VALUES (?, ?, ?, ?)');
            if (!$stmt) return 0;
            $stmt->bind_param('ssss', $task, $user, $date, $time);
            $stmt->execute();
            if (!$stmt->affected_rows) return 0;      

            return 1;
        }
    }

    function getDayTime ($task, $user, $date) {
        $stmt = $this->mysqli->prepare("SELECT time FROM Log WHERE task = ? AND user = ? AND date = ?");
        if (!$stmt) return false;
        $stmt->bind_param('iis', $task, $user, $date);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['time'];
    }

}
?>