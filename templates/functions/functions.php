<?php
$valid = false;
function displayRooms(){
//goal: display rooms from the location the user picked 

    global $pdo;
    $location = $_POST['location'];

    // join sql
    $sql = "SELECT * FROM Hotel.branch_information 
    join Hotel.floor_isin on Hotel.floor_isin.branch_ID = Hotel.branch_information.branch_ID 
    join Hotel.room_ison on Hotel.room_ison.floor_num = Hotel.floor_isin.floor_num
    where Hotel.branch_information.branch_ID=$location";
    $result = $pdo->query($sql);

    $sqlID = "SELECT room_ID FROM Hotel.room_info";
    $results = $pdo->query($sqlID);

    $pass = false;
    
    // only dsiplays rooms that are not occupied by another guest 
    while($row = $result->fetch()){
        $sqlID = "SELECT room_ID FROM Hotel.room_info";
        $results = $pdo->query($sqlID);
        while($rows = $results->fetch()){
            if ($rows['room_ID'] == $row['room_ID']){
                $pass = true;
                
            break;
            }
        }
        if($pass){
            $pass = false;
            continue;
        }
        echo "<form action='guestInfo.php' method='post'>";
                echo "<div class = 'showRooms'>";
                    echo "<img src='../images/" . $row['branch_ID'] . ".jpg' >";
                echo "<div class = 'description'>";
                    echo "<h3> Description </h3> <p> Location: " . $row['branch_name'] . "</br> Floor Number: " . substr($row['floor_num'],-1) . "</br> Room Number: " . $row['room_num'] . " </br> Beds:  " . $row['bed_counter'] . " </p>";
                    echo "</br>";
                echo "</div>";
                //to get room id
                echo "<input type='submit' value='Book Now' onclick = 'document.getElementById(\"room_ID\").value=\"".$row['room_ID']."\";'>";
                echo "</div>";
    }
    
    //to get room id
    echo "<input id = 'room_ID' name = 'room_ID' type = 'hidden' />";
}

//Displays rooms that are occupied by guest
function displayGuests(){
    global $pdo;
    $sql = "SELECT * FROM Hotel.guest_info";
    $result = $pdo->query($sql);
    while ($row = $result->fetch()){
        echo "<form action='guestDeleted.php' method='post'>";
            echo "<div class = 'displayGuest'>";
                echo "<h3>Guest: </h3>"."<input name = 'displayGuest' value =".$row['guest_name']." /> ";
            echo "<input type='submit' value = 'Delete Guest'>";
            echo "<br/>";
            echo "</div>";
    }

}

function validateInput($x){
    // validate user input, if valid, send to handleUserInput() to input data into db
    
        $valid = false;
        $unique = false;
        global $pdo;
        $sql = "SELECT guest_name FROM Hotel.guest_info";
        $result = $pdo->query($sql);
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $i=1;
            //while i less or equal to party size
            while($i <= $_SESSION['party_size']){

                //if the field has a value
                if(isset($_POST['name' . $i]) && !empty($_POST['name' . $i])){
                    $name = $_POST['name' . $i];

                    //if the table is empty (first guest), then no need to check the db
                    if($result->fetchAll() == false){
                        $valid = true;
                    }

                    //else, the hotel isn't already empty, so check
                    else{

                        //redo sql because fetched all earlier
                        $sql = "SELECT guest_name FROM Hotel.guest_info";
                        $result = $pdo->query($sql);
                  
                        //while the db still has rows 
                        while ($row = $result->fetch()){   

                            //if the name exists, only unique names allowed in this hotel
                            if ($name == $row['guest_name']){
                                $valid = false;
                                $unique = false;
                                echo "<div class = 'errormsg'>Please enter a unique name </div>";
                                break;
                            }

                            //else, we can put inside hotel
                            else{
                                $valid = true;
                                $unique = true; 
                            }
                        }
                    }
                    // if not unique, stop checking others and exit loop
                    if(!$unique){
                    break;
                    }
                    
                //increment; check next 
                $i++;
                }
    
                //else, some field is empty and exits loop
                else{
                    $valid = false;
                    echo "Please enter all fields";
                    break;
                }
    
                
            }

            //if there is a unique name, put inside hotel
            if ($valid){
                handleUserInput($x);
                header('location:../templates/confirmedBooking.php');
            }
            //else, it gives appropriate error message
    }

}

function validateDate(){
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['check_in']) && $_POST['check_out'] ){
        $in = date('Y-m-d', strtotime($_POST['check_in']));
        $out = date('Y-m-d', strtotime($_POST['check_out']));

        $today = date('Y-m-d');

        //unacceptable dates:
        //when new check in & check out are before today
        //when check in is after check out

        if($in < $today || $out < $today || $in > $out){
            echo "Please enter a valid date";
        }else{
            header('location:../templates/selectRoom.php');
        }
    }
}

function validateLogin(){
// validate that user is in db

global $pdo;

if($_SERVER['REQUEST_METHOD']== 'POST')
{
    $location = $_POST['log_in'];
    
    //project sql 
    $sql = "SELECT staff_ID FROM Hotel.staff";
    $result = $pdo->query($sql);

    while($row = $result->fetch()){
        if ($location == $row['staff_ID']){
            header('location:templates/staff.php');
        }
    }

    echo  "<div class ='errormsg'> staff ID does not exist </div>";
}

}
?>