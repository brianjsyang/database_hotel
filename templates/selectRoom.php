<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../database/database.php';
require_once 'functions/functions.php';


//connect to database: PHP Data object representing Database connection
$pdo = db_connect();
session_start();

if ( !empty( $_POST ) ) {
    if ( isset($_POST['location'])){
        $_SESSION['location'] = $_POST['location'];
    }
    if ( isset( $_POST['party_size'])){
        $_SESSION['party_size']= $_POST['party_size'];
    }
    if ( isset($_POST['check_in'])){
        $_SESSION['check_in'] = date('Y-m-d', strtotime($_POST['check_in']));
    }
    if ( isset($_POST['check_out'])){
        $_SESSION['check_out'] = date('Y-m-d', strtotime($_POST['check_out']));
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Select A Room</title>
</head>
<body>
    
    <h1>Good Hotel - Select A Room </h1>

    <div class = 'wrapper'>
    <?php
        displayRooms();
    
    ?>
    </div>
    </br>
    <a href="../index.php">Back Home</a>
    
</body>
</html>