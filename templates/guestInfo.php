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
    <title>Guest Information</title>
</head>
<body>

    <h1>Good Hotel - Guest Information </h1>
    <?php
        global $pdo;
        $location = $_SESSION['location'];

        //select sql
        $sql = "SELECT branch_name FROM Hotel.branch_information where Hotel.branch_information.branch_ID=$location";
        $result = $pdo->query($sql);

        while($row = $result->fetch()){

        echo "<div class = 'guestInfo'>";
        echo "<h2> Reservation Information </h2>";
        echo "Reservation for room at the " . $row['branch_name'] . "</br> For party of : " . $_SESSION['party_size'];
        echo " </br> Check in : " . $_SESSION['check_in'] . "</br> Check out : " . $_SESSION['check_out'];
        echo "</div>";

        }

        echo "</br> ";

    ?>

    <div class = "guestForm">
        <form action="guestInfo.php" method="post">
            <h2> Guest Information </h2>

            <?php
                validateInput($_POST['room_ID']);
                echo "</br>";
                echo "</br> ";

                // displays text box for guest name according to number of guests 
                $j=1;
                while ($j <= $_SESSION['party_size']){
                    
                    echo "<label for='name$j'>Guest $j Full Name:</label><br>";
                    echo "<input type='text' id='name$j' name='name$j'><br>";
                    $j++;
                }

                    //this is so that if error, we keep the post variable
                echo "<input id = 'room_ID' name = 'room_ID' value = '".$_POST['room_ID']."' type = 'hidden' />"; 
            ?>
            
            <div class = "wrapper">
                <input type="submit" value="Confirm Booking">
                <a href="../index.php">Cancel</a>
            </div>
        </form>
    </div>


    
    

</body>
</html>