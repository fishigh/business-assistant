<?php

set_include_path('.' . PATH_SEPARATOR . './modules');
include('env_init.php');

// var_export($_SERVER);
// var_export($_REQUEST);
session_start();

// header('Access-Control-Allow-Origin: http://127.0.0.1:8081');
// header('Access-Control-Allow-Credentials: true');

$url = parse_url($_SERVER['REQUEST_URI']);
if (preg_match('/\/user\/(.*)/', $url['path'], $matches)) {
    $method = $matches[1];
    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB, MYSQL_PORT);    
    require('user.php');
    // var_export($_SESSION);
    // var_export(session_id());
} else if (preg_match('/\/api\/(.*)\/(.*)/', $url['path'], $matches)) {
    // var_export($matches);
    $module = $matches[1];
    $method = $matches[2];
    if ($method != 'init') {
        // var_export($_SESSION);
        if (!isset($_SESSION['user']) || time() - $_SESSION['last_activity'] > 86400) {
            session_destroy();
            header('HTTP/1.1 401');
            print('need login');
            exit(0);
        } else {
            $_SESSION['last_activity'] = time();
        }
    }
    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB, MYSQL_PORT);
    require($module . '.php');
}