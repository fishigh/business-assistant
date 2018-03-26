<?php

header('Access-Control-Allow-Origin: *');
$table = 'action';
if ($method == 'init') {
    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
    // $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
    $ret = mysqli_query($mysqli, 'CREATE TABLE IF NOT EXISTS ' . $table . ' (' .
                                    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,' .
                                    'user_id INT NOT NULL,' .
                                    'item_id INT NOT NULL,' .
                                    'date DATE NOT NULL,' .
                                    'completion INT DEFAULT 0,' .
                                    'remark TEXT,' .
                                    'create_time TIMESTAMP DEFAULT "0000-00-00 00:00:00",' .
                                    'update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,' .
                                    'UNIQUE KEY (user_id, item_id, date)) ENGINE=InnoDB');
    if ($ret) {
        print('success');
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else if ($method == 'range') {
    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
    // $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
    $result = mysqli_query($mysqli, 'SELECT * FROM ' . $table . 
                                    ' WHERE date BETWEEN "' . $_REQUEST['start'] . '" AND "' . $_REQUEST['end'] . '"');
    if ($result) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $items[$row["item_id"]][$row["date"]] = $row;
        }
        $result->close();
        print json_encode($items);
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else {
    include('db_default.php');
}