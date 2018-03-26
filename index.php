<?php

set_include_path('.' . PATH_SEPARATOR . './modules');
include('env_init.php');

// var_export($_SERVER);
// var_export($_REQUEST);

$url = parse_url($_SERVER['REQUEST_URI']);
if (preg_match('/\/api\/(.*)\/(.*)/', $url['path'], $matches)) {
    // var_export($matches);
    $module = $matches[1];
    $method = $matches[2];
    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB, MYSQL_PORT);
    require($module . '.php');
}