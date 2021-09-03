<?php
require 'config.php';

// Should return a PDO
function db_connect() {

    try {

    $connectionString = "mysql:dbhost=" . DBHOST . "; dbname =" . DBNAME;
    $dbuser = DBUSER;
    $dbpass = '';

    $pdo = new PDO($connectionString, $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;

    }

    catch (PDOException $e)
    {
        die($e->getMessage());
    }
}

function handleUserInput($x){
    // inserts user info in the database

    global $pdo;

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // inserts into guest_info table
        $i=1;
        while ($i <= $_SESSION['party_size']){
            if(isset($_POST['name' . $i])){

                $_SESSION['name' . $i] = $_POST['name' . $i];
        
                $sql = "INSERT INTO Hotel.guest_info (guest_name, party_size, check_in, check_out) VALUES (:guest_name, :party_size, :check_in, :check_out)";
                
                $statement = $pdo->prepare($sql);
        
                $statement->bindValue(':guest_name', $_SESSION['name' . $i]);
                $statement->bindValue(':party_size', $_SESSION['party_size']);
                $statement->bindValue(':check_in', $_SESSION['check_in']);
                $statement->bindValue(':check_out', $_SESSION['check_out']);
        
                $statement->execute();

                $sql = "INSERT INTO Hotel.room_info VALUES (:guest_name, :room_ID)";

                $statement = $pdo->prepare($sql);
                $statement->bindValue(':guest_name', $_SESSION['name'.$i]);
                $statement->bindValue(':room_ID', $x);
                $statement->execute();
            }
            $i++;
        }
        // inserts names into keyHolder table

            $sql = "INSERT INTO Hotel.keyholders (first_keyholder, second_keyholder) VALUES (:first_keyholder, :second_keyholder)";
            $statement = $pdo->prepare($sql);

            // if theres only one guest, theres only one keyholder name
            if ($_SESSION['party_size'] == 1 ){
            
                    $statement->bindValue(':first_keyholder', $_SESSION['name1']);
                    $statement->bindValue(':second_keyholder', $_SESSION['name1']);
    
                    $statement->execute();
    
                // }
            // else, if theres 2 or more guests, put the 2 keyholder names in 
            }else{
        
                $statement->bindValue(':first_keyholder', $_SESSION['name1']);
                $statement->bindValue(':second_keyholder', $_SESSION['name2']);

                $statement->execute();
            }
            
        
        }
    }

?>
