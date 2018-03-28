<?php

$table = 'user';
if ($method == 'init') {
    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
    // $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
    $ret = mysqli_query($mysqli, 'CREATE TABLE IF NOT EXISTS ' . $table . '(' .
                                    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,' .
                                    'name CHAR(128) NOT NULL,' .
                                    'tel CHAR(16) NOT NULL,' .
                                    'password CHAR(64) NOT NULL,' .
                                    'remark TEXT,' .
                                    'create_time TIMESTAMP DEFAULT "0000-00-00 00:00:00",' .
                                    'update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,' .
                                    'UNIQUE KEY(name, tel)) ENGINE=InnoDB');
    if ($ret) {
        print('success');
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else if ($method == 'login') {
    $tel = $_POST['tel'];
    $password = $_POST['password'];
    $result = mysqli_query($mysqli, 'SELECT * FROM ' . $table . ' WHERE tel = "' . $tel . '" AND password = "' . $password . '"');
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user'] = $row['name'];
            $_SESSION['last_activity'] = time();
            header('HTTP/1.1 200');
            print('success');    
        } else {
            header('HTTP/1.1 400');
            print('invalid user or wrong password');
        }
        $result->close();
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else if ($method == 'register') {
    if ($_POST['password'] != $_POST['verify_password']) {
        header('HTTP/1.1 400');
        print('密码输入不一致');
        exit(0);
    }
    unset($_POST['verify_password']);
    $columns = implode(', ', array_keys($_POST));
    $values = implode('", "', array_values($_POST));

    $ret = mysqli_query($mysqli, 'INSERT INTO ' . $table . ' (' . $columns . ', create_time) VALUES ("' .  $values . '", NOW())');
    if ($ret) {
        print('success');
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else {
    header('HTTP/1.1 400');
    print('fail');
}