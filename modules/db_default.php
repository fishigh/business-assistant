<?php
// print(json_encode($_SERVER));
// var_export($_POST);
header('Access-Control-Allow-Origin: *');
if ($method == 'add') {
    $columns = implode(', ', array_keys($_POST));
    $values = implode('", "', array_values($_POST));

    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
    // $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
    $ret = mysqli_query($mysqli, 'insert into ' . $table . ' (' . $columns . ', create_time) values ("' .  $values . '", now())');
    if ($ret) {
        print('success');
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else if ($method == 'list') {
    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
	// $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
	// var_export($mysqli);
    $result = mysqli_query($mysqli, 'select * from ' . $table);
    if ($result) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        $result->close();
        print json_encode($items);
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else if ($method == 'update') {
    $id = $_POST['id'];
    unset($_POST['id']);
    $setter = array();
    foreach ($_POST as $col => $val) {
        $setter[] = $col . ' = "' . $val . '"';
    }

    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
    // $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
    $ret = mysqli_query($mysqli, 'update ' . $table . ' set ' . implode(', ', $setter) . ' where id = ' . $id);
    if ($ret) {
        print('success');
    } else {
        header('HTTP/1.1 400');
        print(mysqli_error($mysqli));
    }
} else if ($method == 'delete') {
    // $mysqli = new mysqli('127.0.0.1', 'root', 'yugao', 'test');
    // $mysqli = new mysqli('sqld-gz.bcehost.com', '44b0554ea4ac464d982fae44701edada', '87cb138aa4b34048bb3926d2a2e77a7d', 'iUlzHcjHFWqoQejMMBSz', 3306);
    $ret = mysqli_query($mysqli, 'delete from ' . $table . ' where id = ' . $_POST['id']);
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