<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once 'database/database.php';

$pdo = db_connect();
include 'templates/index.php';
?>