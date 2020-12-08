<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../database/database.php';
require_once 'functions/functions.php';


//connect to database: PHP Data object representing Database connection
$pdo = db_connect();
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Booking Confirmation</title>
</head>
<body>

    <h1>Good Hotel - Booking Confirmation </h1>

    <?php
        global $pdo;
        $guestName = $_SESSION['name1'];

        //join sql
        $sql = "SELECT * FROM Hotel.guest_info 
        join Hotel.room_info on Hotel.guest_info.guest_name = Hotel.room_info.guest_name 
        join Hotel.room_ison on Hotel.room_ison.room_ID = Hotel.room_info.room_ID 
        join Hotel.floor_isin on Hotel.floor_isin.floor_num = Hotel.room_ison.floor_num
        join Hotel.branch_information on Hotel.branch_information.branch_ID = Hotel.floor_isin.branch_ID
        where Hotel.guest_info.guest_name = '$guestName'";

        $result = $pdo->query($sql);

        while($row = $result->fetch()){

        echo "<div class = 'guestInfo'>";
        echo "<h2> Reservation Information </h2>";
        echo "Reservation for " .  $row['guest_name'] . "</br>";
        echo "Reservation Location:  " . $row['branch_name'] . "</br> For party of : " .$row['party_size'];
        echo "</br> Room Number: " . $row['room_num'];
        echo " </br> Check In: " . $row['check_in'] . "</br> Check Out: " . $row['check_out'];
        echo "</div>";

        }
    ?>
    </br>

    <a href="../index.php">Back Home</a>
    

</body>
</html>