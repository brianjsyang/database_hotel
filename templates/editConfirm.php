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
    <title>Edit Confirmation</title>
</head>
<body>
    <h1>Good Hotel - Edit Confirmation</h1>

    <?php
        $guestName = $_POST['id'];
        $sql = "SELECT * FROM Hotel.guest_info WHERE guest_name = '$guestName' ";
        $result = $pdo->query($sql)->fetch();

        //Confirming Update
        if($_POST['submit'] == 'Update Dates'){
            $oldIn = date('Y-m-d', strtotime($result['check_in']));
            $oldOut = date('Y-m-d', strtotime($result['check_out']));
            $newIn = date('Y-m-d', strtotime($_POST['checkIn']));
            $newOut = date('Y-m-d', strtotime($_POST['checkOut']));
            $today = date('Y-m-d');

            //unacceptable dates:
            //when new check in & check out are before today
            //when new check in is after new check out
            //when new check out is before new check out
            if($newIn < $today || $newOut < $today || $newIn > $newOut){
                echo '<p class="guestInfo">
                    <b>Error: Unacceptable dates</b> <br><br>
                    Please check your input dates again! <br>
                    *New dates cannot be before today* <br>
                    *New Check In cannot be before New Check Out* <br>
                </p>';
            }
            else{
                //check if guestname exists in keyholder table
                $findGuestName = $pdo->prepare("SELECT first_keyholder, second_keyholder FROM Hotel.keyholders WHERE first_keyholder=? OR second_keyholder=?"); 
                $findGuestName->execute([$guestName, $guestName]);

                if($findGuestName->rowCount() > 0) {
                    //Guest name exists in keyholder table
                    //updating query to change data in database.
                    //Get room id of the guest name and update all guests who have the same roomid
                    $update = "UPDATE Hotel.guest_info INNER JOIN Hotel.room_info USING (guest_name)
                            SET check_in=?, check_out=? 
                            WHERE room_ID=(SELECT room_ID FROM Hotel.room_info WHERE guest_name=?)";
                    $stmt = $pdo->prepare($update);
                    $stmt->execute([$newIn, $newOut, $guestName]);
                    
                    //Count affect rows. 0 = no change has been made.
                    if($stmt->rowCount()== '0'){
                        echo 'Failed to Update, new dates are identical to the original dates.';
                    }
                    else{
                        echo '<p class="guestInfo">
                            <b>Update complete for Guest Name: '.$guestName.'</b> <br><br>
                            Original check in date: '.$oldIn.' <br>
                            New Check In Date: '.$newIn.' <br><br>

                            Original Check Out Date: '.$oldOut.' <br>
                            New Check Out Date: '.$newOut.'; <br>
                        </p>
                        ';
                    }
                }
                else {
                    //Guest name does not exist in keholder table
                    echo '<p class="guestInfo">
                        <b>Failed to update for Guest Name: '.$guestName.'</b> <br><br>
                        The selected guest is not a keyholder <br>
                        Only key holder may change check in/ check out dates <br>
                    </p>
                    ';
                }
            }
        }

        //Confirm Delete
        if($_POST['submit'] == 'Confirm Delete'){

            //check if guestname exists in keyholder table
            $findGuestName = $pdo->prepare("SELECT first_keyholder, second_keyholder FROM Hotel.keyholders WHERE first_keyholder=? OR second_keyholder=?"); 
            $findGuestName->execute([$guestName, $guestName]);


            if($findGuestName->rowCount() > 0) {
                //Guest name exists in keyholder table, 
                //Delete all guests with same room id.
                $delete =  "DELETE Hotel.guest_info, Hotel.room_info, Hotel.keyholders
                            FROM Hotel.guest_info 
                            LEFT JOIN Hotel.keyholders ON (Hotel.keyholders.first_keyholder=Hotel.guest_info.guest_name)
                            LEFT JOIN Hotel.room_info ON (Hotel.room_info.guest_name=Hotel.guest_info.guest_name) 
                            WHERE room_ID IN (
                                SELECT temp.room_ID FROM (SELECT room_ID FROM Hotel.room_info WHERE guest_name=?) temp
                            )";
                $stmt = $pdo->prepare($delete);
                $stmt->execute([$guestName]);
                
                //Count affect rows. 0 = no change has been made.
                if($stmt->rowCount() == '0'){
                    echo 'Failed to delete, the entry does not exist.';
                }
                else{
                    echo '<p class="guestInfo">
                        Guest Name: '.$guestName.' <br><br>
                        Deletion of the guest has been successful. <br>
                        *ALL other members with the guest name has been removed as well* <br>
                    </p>';
                }
            }
            else {
                //Guest name does not exist in key holder table
                //Delete the guest name
                $delete =  "DELETE Hotel.guest_info, Hotel.room_info, Hotel.keyholders
                            FROM Hotel.guest_info 
                            LEFT JOIN Hotel.keyholders ON (Hotel.keyholders.first_keyholder=Hotel.guest_info.guest_name)
                            LEFT JOIN Hotel.room_info ON (Hotel.room_info.guest_name=Hotel.guest_info.guest_name) 
                            WHERE Hotel.guest_info.guest_name=?";
                $stmt = $pdo->prepare($delete);
                $stmt->execute([$guestName]);
                
                //decrement partysize by 1.
                $decSql = "UPDATE Hotel.guest_info SET party_size = party_size - 1 WHERE (party_size > 2)";
                $decrement = $pdo->prepare($decSql);
                $decrement->execute();

                if($stmt->rowCount() == '0'){
                    echo 'Failed to delete, the entry does not exist.';
                    //echo $getRoomID;
                }
                else{
                    echo '<p class="guestInfo">
                        Guest Name: '.$guestName.' <br><br>
                        Deletion of the guest has been successful.
                    </p>';
                }
            }
        }
    ?>

    <br><br><br><br>
    <a href="staff.php">Back</a>
    <a href="../index.php">Home</a>
</body>
</html>