<?php

$table = 'wastebook';
if ($method == 'init') {
    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
    // $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
    $ret = mysqli_query($mysqli, 'CREATE TABLE IF NOT EXISTS ' . $table . '(' .
                                    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,' .
                                    'date DATE NOT NULL,' .
                                    'seller_id INT NOT NULL,' .
                                    'buyer_id INT NOT NULL,' .
                                    'product_id INT NOT NULL,' .
                                    'in_price FLOAT NOT NULL,' .
                                    'out_price FLOAT NOT NULL,' .
                                    'quantity INT NOT NULL,' .
                                    'carriage FLOAT DEFAULT 0.0,' .
                                    'carriage_pay_type CHAR(32),' .
                                    'income FLOAT DEFAULT NULL,' .
                                    'store_id INT NOT NULL,' .
                                    'remark TEXT,' .
                                    'create_time TIMESTAMP DEFAULT "0000-00-00 00:00:00",' .
                                    'update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB');
    if ($ret) {
        print('success');
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else {
    include('db_default.php');
}