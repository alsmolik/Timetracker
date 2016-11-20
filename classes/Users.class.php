<?php

class Users {
    private $mysqli;

    function __construct ($mysqli) {
        $this->mysqli = $mysqli;
    }

    function auth ($username, $password) {
        $password = md5($password);

        $stmt = $this->mysqli->prepare('SELECT * FROM Users WHERE (Users.username = ? OR Users.email = ?) AND Users.password = ?');
        if (!$stmt) return 0;
        $stmt->bind_param('sss', $username, $username, $password);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result->num_rows) return -1;
        
        $row = $result->fetch_assoc();
        if (!$row['is_active']) return -2;

        return array('username' => $row['username'], 'id' => $row['id'], 'is_admin' => $row['is_admin']);
    }

    function signup ($username, $email, $password) {
        $stmt = $this->mysqli->prepare('SELECT * FROM Users WHERE username = ? OR email = ?');
        if (!$stmt) return 0;
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) return -1;

        $password = md5($password);

        $stmt = $this->mysqli->prepare('INSERT INTO Users (username, email, password) VALUES (?, ?, ?)');
        if (!$stmt) return 0;
        $stmt->bind_param('sss', $username, $email, $password);
        $stmt->execute();
        if (!$stmt->affected_rows) return 0;      

        return 1;    
    }

    function getUsers () {
        if ($result = $this->mysqli->query("SELECT * FROM Users")) {
            return $result;
        } else {
            return false;
        }  
    }

    function delUser ($id) {
        $stmt = $this->mysqli->prepare('DELETE FROM Users WHERE id = ?');
        if (!$stmt) return false;
        $stmt->bind_param('s', $id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }  
    }

    function changeUserStatus ($id) {
        $stmt = $this->mysqli->prepare('UPDATE Users SET is_active = (is_active ^ 1) WHERE id = ?');
        if (!$stmt) return false;
        $stmt->bind_param('s', $id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }  
    }

}
?>