<?php

$config = array (
    'dbhost' => 'localhost', 
    'dbuser' => 'root',
    'dbpass' => 'root',
    'dbport' => 3306,
    'dbname' => 'mdpi',
    'apiurl' =>'https://www.scilit.net/api/v1/articles?_format=json&token=575ffe99998e814d2e8054ed030f9dea',
    'task' => 'index',
    'error' =>''
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




