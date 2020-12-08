<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once '../database/database.php';
    require_once 'functions/functions.php';

    //connect to database: PHP Data object representing Database connection
    $pdo = db_connect();

    //end php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Edit Guest Information</title>
</head>
<body>
    <h1>Good Hotel - Edit Guest Information </h1>
    <?php
       //Edit Botton is clicked...Show form to edit check_in and check_out.
        if ($_POST['action'] == 'Edit') {

            $guestName = $_POST['id'];
            $sql = "SELECT * FROM Hotel.guest_info WHERE guest_name = '$guestName' ";
            $result = $pdo->query($sql)->fetch();
            $oldIn = date('Y-m-d', strtotime($result['check_in']));
            $oldOut = date('Y-m-d', strtotime($result['check_out']));

            echo '<form class="guestForm" method="POST" action="editConfirm.php">
                Update Guest Information for <b>'.$_POST['id'].'</b> <br>
                *Showing currently selected dates* <br><br>
                
                <label for="checkIn">New Check In Date: </label><br>
                <input type="date" name="checkIn" value='.$oldIn.'>  <br><br>

                <label for="checkOut">New Check Out Date: </label><br>
                <input type="date" name="checkOut" value='.$oldOut.'> <br><br><br>
                    
                <input type="submit" name="submit" value="Update Dates">
                <input type="hidden" name="id" value="'.$_POST['id'].'">
            </form>';
        }    
    
        //Delete Button is clicked...Confirm deletion of guest name and proceed to delete.
        else if ($_POST['action'] == 'Delete') {
            echo '<form class="guestForm" method="POST" action="editConfirm.php">
                Deleting Guest Information for <b> '.$_POST['id'].'</b> <br>
                *ALL other members with the guest name has been removed as well*
                <br>
                
                <input type="submit" name="submit" value="Confirm Delete">
                <input type="hidden" name="id" value="'.$_POST['id'].'">
            </form>';
        }
    ?>

    <br><br><br><br>
    <a href="../index.php">Home</a>
</body>
</html>