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
    <title>Staff</title>
</head>
<body>
    <h1>Good Hotel - Staff </h1>
    <?php

    //init post lol
    if(empty($_POST['location'])){
        $_POST['location'] = "";
    }

    if(empty($_POST['beds'])){
        $_POST['beds'] = "";
    }

    if(empty($_POST['stay'])){
        $_POST['stay'] = "";
    }

    if(empty($_POST['division'])){
        $_POST['division'] = "";
    }

    //filters
    //note: division will never show results (ie guests not allowed to stay in multiple locations at the same time), but technically works if limitation was removed
    echo '
    <form action = "staff.php" method = "POST">

        <table class = "Filter">
        <tr><td>
        <label for="location">Location</label>
        <select name = "location" id="location">
            <option value = "">All Locations</option>
            <option '.($_POST['location'] == 69 ? "selected " : "").'value = "69">Vancouver Downtown</option>
            <option '.($_POST['location'] == 70 ? "selected " : "").'value = "70">Vancouver Waterfront</option>
            <option '.($_POST['location'] == 71 ? "selected " : "").'value = "71">Seattle Downtown</option>
            <option '.($_POST['location'] == 72 ? "selected " : "").'value = "72">Seattle Waterfront</option>
            <option '.($_POST['location'] == 73 ? "selected " : "").'value = "73">Tokyo</option>
        </select></td>

        <td>
        <label for="beds">Number of Beds</label>
        <select name ="beds" id="beds">
            <option value = "">Any Number</option>
            <option '.($_POST['beds'] == 1 ? "selected " : "").'value = "1">One Bed</option>
            <option '.($_POST['beds'] == 2 ? "selected " : "").'value = "2">Two Beds</option>
        </select>
        </td>

        <td>
        <label for="stay">Duration of Stay</label>
        <select name ="stay" id="stay">
            <option value = "">Any Duration</option>
            <option '.($_POST['stay'] == 1 ? "selected " : "").'value = "1">Shortest Term</option>
            <option '.($_POST['stay'] == 2 ? "selected " : "").'value = "2">Longest Term</option>
        </select>
        </td>

        <td>
        <label for="division">Staying at all locations?</label>
        <select name = "division" id = "division">
        <option value = ""> No </option>
        <option '.($_POST['division'] == 2 ? "selected " : "").'value = "2"> Yes </option>
        </select>
        </tr>

        </table>
        
        <input type="submit" value="Filter Rooms">
    </form>

    ';
        //INCASE I FAIL LMAO
        // SELECT Hotel.guest_info.guest_name, Hotel.room_info.room_ID, Hotel.floor_isin.branch_ID, 
        // min(DATEDIFF(check_out, check_in)) as days
        // FROM Hotel.room_info 
        // join Hotel.guest_info on Hotel.room_info.guest_name = Hotel.guest_info.guest_name 
        // join Hotel.room_ison on Hotel.room_ison.room_ID = Hotel.room_info.room_ID 
        // join Hotel.floor_isin on Hotel.floor_isin.floor_num = Hotel.room_ison.floor_num
        // WHERE DATEDIFF(check_out, check_in) = (
        //     SELECT 
        //     min(DATEDIFF(check_out, check_in)) as days
        //     FROM Hotel.room_info 
        //     join Hotel.guest_info on Hotel.room_info.guest_name = Hotel.guest_info.guest_name 
        //     join Hotel.room_ison on Hotel.room_ison.room_ID = Hotel.room_info.room_ID 
        //     join Hotel.floor_isin on Hotel.floor_isin.floor_num = Hotel.room_ison.floor_num
        //     group by branch_ID
		// 	)
        // group by branch_ID, guest_name
        // order by days DESC

        $sql = "SELECT Hotel.guest_info.guest_name, Hotel.room_info.room_ID, Hotel.floor_isin.branch_ID, check_in, check_out "
        .((empty($_POST['stay']) ? "" : ($_POST['stay'] == 1 ? ", min(DATEDIFF(check_out, check_in)) as days" : ", max(DATEDIFF(check_out, check_in)) as days")))."
        FROM Hotel.room_info 
        JOIN Hotel.guest_info ON Hotel.room_info.guest_name = Hotel.guest_info.guest_name 
        JOIN Hotel.room_ison ON Hotel.room_ison.room_ID = Hotel.room_info.room_ID 
        JOIN Hotel.floor_isin ON Hotel.floor_isin.floor_num = Hotel.room_ison.floor_num ".
        ((empty($_POST['stay'])) ? 
        "WHERE ".(empty($_POST['location']) ? "true" : "branch_ID = ").$_POST['location']."
        AND ".(empty($_POST['beds']) ? "true" : "bed_counter = ").$_POST['beds']."
        AND ". (empty($_POST['division']) ? "true" : 
        " NOT EXISTS ((SELECT branch_ID FROM Hotel.branch_information) 
        EXCEPT (SELECT Hotel.floor_isin.branch_ID
        FROM Hotel.floor_isin floor, Hotel.room_ison ison, Hotel.room_info info
        WHERE floor.floor_num = ison.floor_num AND ison.room_ID = info.room_ID))
        ") : "
            WHERE ".(empty($_POST['location']) ? "true" : "branch_ID = ").$_POST['location']."
            AND ".(empty($_POST['beds']) ? "true" : "bed_counter = ").$_POST['beds']."
            AND ".(empty($_POST['division']) ? "true" : 
            " NOT EXISTS ((SELECT branch_ID FROM Hotel.branch_information) 
            EXCEPT (SELECT Hotel.floor_isin.branch_ID
            FROM Hotel.floor_isin floor, Hotel.room_ison ison, Hotel.room_info info
            WHERE floor.floor_num = ison.floor_num AND ison.room_ID = info.room_ID))
            ").
            " AND DATEDIFF(check_out, check_in) = (
            SELECT ".
            ($_POST['stay'] == 1 ? "MIN(DATEDIFF(check_out, check_in)) as days" : "MAX(DATEDIFF(check_out, check_in)) as days")."
            FROM Hotel.room_info 
            JOIN Hotel.guest_info on Hotel.room_info.guest_name = Hotel.guest_info.guest_name 
            JOIN Hotel.room_ison on Hotel.room_ison.room_ID = Hotel.room_info.room_ID 
            JOIN Hotel.floor_isin on Hotel.floor_isin.floor_num = Hotel.room_ison.floor_num 
            WHERE ".
            (empty($_POST['location']) ? "true" : " branch_ID = ".$_POST['location'])."
            AND ".
            (empty($_POST['beds']) ? "true" : " bed_counter = ".$_POST['beds']).

            (empty($_POST['location']) && empty($_POST['beds']) ? "" : (!empty($_POST['location']) && !empty($_POST['beds']) ? " GROUP BY branch_ID, bed_counter " : (empty($_POST['location']) ? " GROUP BY bed_counter " : " GROUP BY branch_ID ")))
            ."
            
            )")."
        GROUP BY branch_ID, guest_name ".
        ((empty($_POST['stay'])) ? "" : "order by days DESC");

        echo '<table class="guestForm">
                <tr>
                    <th> Guest Name </th>
                    <th> Room ID </th>
                    <th> Check In </th>
                    <th> Check Out </th>
                    <th> Actions </th>
                </tr>';

        if($result = $pdo->query($sql)){
            while($row = $result->fetch()) {
                $fieldname1 = $row["guest_name"];
                $fieldname2 = $row["room_ID"];
                $fieldname3 = $row["check_in"];
                $fieldname4 = $row["check_out"];
                

                echo '<tr align=center>
                    <td> '.$fieldname1.' </td>
                    <td> '.$fieldname2.' </td>
                    <td> '.$fieldname3.' </td>
                    <td> '.$fieldname4.' </td>
                    <td> 
                        <form action="edit.php" method="POST">
                            <input type="submit" name="action" value="Edit"/>
                            <input type="submit" name="action" value="Delete"/>
                            <input type="hidden" name="id" value="'.$fieldname1.'"/>
                        </form>
                    </td>
                </tr>';
            }
        }
    ?>
    <a href="../index.php">Home</a>
</body>
</html>