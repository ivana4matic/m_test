<?php

//----------------------------
// Forbid Access
//----------------------------

if (!defined('CONFIG_ACCESS')) {
    die('Direct access not permitted');
}

$config = array (
    'dbhost' => '127.0.0.1',
    'dbuser' => 'root',
    'dbpass' => 'root',
    'dbport' => 3306,
    'dbname' => 'mdpi',
    'apiurl' =>'https://www.scilit.net/api/v1/articles?_format=json&token=575ffe99998e814d2e8054ed030f9dea',
    'task' => 'index',
    'error' => ''
);

if (isset($_GET['task'])) {
    $config['task'] = $_GET['task'];
}

try {
    $pdo = new PDO('mysql:host='.$config['dbhost'].';dbname='.$config['dbname'], $config['dbuser'], $config['dbpass']);
} catch (PDOException $e) {
    $config['error'] = $e->getMessage();
    include_once "views/error.html.php";
    die();
} 

//----------------------------
// Routing
//----------------------------

if (isset($_GET['task'])) {
    require_once(__DIR__.'/controllers/PapersController.php');
    $controller = new PapersController($pdo, $config);

    if ($_GET['task'] === 'all') {
        $controller->show();
    } elseif (in_array($_GET['task'], ['csv', 'xml', 'json'])) {
        $controller->store($_GET['task']);
    }
}


