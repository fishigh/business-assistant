<?php

$table = 'customer';
if ($method == 'init') {
    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
    // $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
    $ret = mysqli_query($mysqli, 'CREATE TABLE IF NOT EXISTS ' . $table . ' (' .
                                    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,' .
                                    'user_id INT NOT NULL,' .
                                    'name CHAR(128) NOT NULL,' .
                                    'level INT NOT NULL,' .
                                    'gender CHAR(8),' .
                                    'birthday DATE,' .
                                    'tel CHAR(16) NOT NULL,' .
                                    'weixin CHAR(32),' .
                                    'qq CHAR(16),' .
                                    'addr CHAR(255),' .
                                    'fund FLOAT DEFAULT 0.0,' .
                                    'remark TEXT,' .
                                    'create_time TIMESTAMP DEFAULT "0000-00-00 00:00:00",' .
                                    'update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,' .
                                    'UNIQUE KEY (user_id, name, tel)) ENGINE=InnoDB');
    if ($ret) {
        print('success');
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else {
    include('user_db_default.php');
}