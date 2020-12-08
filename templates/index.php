<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'database/database.php';
require_once 'functions/functions.php';

$pdo = db_connect();
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Good Hotel</title>
</head>

<body>

    <div class="container">
        <img src="images/beachHotel.jpg" alt="hotel on the beach">
        <div class="top-left">
            <h1>Good Hotel</h1>
        </div>
        <div class="top-right">
        <?php
        validateLogIn();
        ?>
            <form action="" method = "post">
            <label for="log_in">Staff Login:
                <input type="text" id="log_in" name = "log_in">
            </label>
            <input type="submit" value= "Login">
            </form>

        </div>
        <?php
            $today = date('Y-m-d');

        ?>
        <div class = "option-bar">
        <form action="templates/selectRoom.php" method="post">

            <div class = "location">
                <label for="location">Location</label>
                <select name="location" id="location">
                    <option value="69">Vancouver Downtown</option>
                    <option value="70">Vancouver Waterfront</option>
                    <option value="71">Seattle Downtown</option>
                    <option value="72">Seattle Waterfront</option>
                    <option value="73">Tokyo</option>
                </select>
            </div>
            <div class = "checkInDate">
                <label for="check_in">Check In</label>
                <?php
                echo "<input type='date' id='check_in' name='check_in'
                value=$today 
                min=$today max='2021-12-31'>";
                ?>
                

            </div>
            <div class = "checkOutDate">
            <?php
                echo "<input type='date' id='check_out' name='check_out'
                value= $today 
                min=$today max='2021-12-31'>";
                ?>
            </div>
            <div class = "numGuests">
                <label for="party_size">Number of Guests</label>
                <select name="party_size" id="party_size">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
            <input type="submit" value="Find a Room">
        </form>
        </div>

    </div> 

    
</body>
</html>