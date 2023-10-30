<?php
//used by register.js
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $formData = json_decode($json, true);

    // Retrieve user input
    $ip = $formData['ip'];
    $first_name = $formData['first_name'];
    $last_name = $formData['last_name'];
    $email = $formData['email'];
    $phone_number = $formData['phone'];
    $birthday = $formData['birth'];
    $gender = $formData['gender'];
    $address = $formData['address_code'];
    $user_name = $formData['user_name'];
    $pass_word = $formData['password']; // theres underscore in 'pass_word' because 'password' variable is taken already in dbconfig.php
    $hashed_pass = password_hash($pass_word, PASSWORD_DEFAULT);
    // part 1 Data validation
    $messageString = '';

    // [1] check if fname and lname exceeds length limitation database will error if it exceeds varchar length
    if(strlen($first_name) > 45 || strlen($first_name) == 0 || strlen($last_name) > 45 || strlen($last_name) == 0) {
        $messageString .= "*name must not be empty or exceed 45 characters\n";
    }

    // [2] check if age is allowed
    $dobString = $birthday;
    $dob = new DateTime($dobString);
    $currentDate = new DateTime();
    $age = $currentDate->diff($dob)->y;
    if ($age < 18) {
        $messageString .= "*must be at least 18 years old\n";
    }

    

    // [3] check if number does not contain letters
    for($i = 0; $i < strlen($phone_number); $i++){

        $char = $phone_number[$i];
        if (ctype_alpha($char)) {
            echo "*phone no. must not contain letters\n";
        }

    }

    // [3] check if number length is valid
    if(strlen($phone_number) != 11){
        $messageString .= "*phone number must be 11 digits\n";
    }

    // [3] check if number prefix is valid
        $prefix = substr($phone_number, 0, 4);
    try{
        $selectQuery2 = "SELECT network_prefix FROM simformat WHERE network_prefix = ?";
        $stmt2 = $conn->prepare($selectQuery2);
        $stmt2->bind_param("s", $prefix);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        if($result2->num_rows == 0 ) {
            $messageString .= "*phone no. prefix is invalid\n";   
        }
        $stmt2->close();
    }
    catch(PDOException $e) {

    }

    // [3] check if phone exist in the database - WONT implement since I cant send otp yet
    // try{
    //     $selectQuery1 = "SELECT * FROM userinfo WHERE phone_number = ?;";
    //     $stmt1 = $conn->prepare($selectQuery1);
    //     $stmt1->bind_param("s", $phone_number);
    //     $stmt1->execute();
    //     $result1 = $stmt1->get_result();
    //     if($result1->num_rows > 0 ) {
    //         $messageString .= "*number is taken\n";
    //     }
    //     $stmt1->close();   
    // }
    // catch(PDOException $e) {
    // }

    // [3] send one time pin to number to confirm if owned


    // [4] check if email format/length is wont implement yet
    
    
    // [4] check if email exist in the database - wont implement yet since this works only on local network
    // try{
    //     $selectQuery2 = "SELECT * FROM user_credentials WHERE email = ?";
    //     $stmt2 = $conn->prepare($selectQuery2);
    //     $stmt2->bind_param("s", $email);
    //     $stmt2->execute();
    //     $result2 = $stmt2->get_result();
    //     if($result2->num_rows > 0 ) {
    //         $messageString .= "*email is taken\n";   
    //     }
    //     $stmt2->close();
    // }
    // catch(PDOException $e) {

    // }

    // [4] send link to email to confirm registration wont implement yet



    // [5] check if username is taken
    try{
        $selectQuery3 = "SELECT * FROM user_credentials WHERE user_name = ?";
        $stmt3 = $conn->prepare($selectQuery3);
        $stmt3->bind_param("s", $user_name);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        if($result3->num_rows > 0 ) {
            $messageString .= "*username is not available\n";
        }
       $stmt3->close();
    }
    catch(PDOException $e) {
        
    }

    // [5] check if username length exceeds limit or be empty
    if(strlen($user_name) > 30 || strlen($user_name) == 0){
        $messageString .= "*username must not be empty or exceed 30 characters\n";
    }

    // [6] check if address is filled
        
    // part 2 - Data insertion
    if($messageString == ''){
        try{
            $insertQuery = "INSERT INTO userinfo(first_name, last_name, birth_date, gender, phone_number, address_code) 
            VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);

            if ($stmt === false) {
                echo "Prepare failed: " . $conn->error;
            } else {
                $stmt->bind_param("ssssss", $first_name, $last_name, $birthday, $gender, $phone_number,$address);

                if ($stmt->execute()) {
                    echo "1";
                } else {
                    echo "Insert failed: " . $stmt->error;
                }

                $stmt->close();
            }
        }
        catch(PDOException $e) {
            //die($e->getMessage());
        }
        try{
            $insertQuery = "INSERT INTO user_credentials(user_id, email, user_name, password_) 
            VALUES((SELECT MAX(id) FROM userinfo), ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);

            if ($stmt === false) {
                echo "Prepare failed: " . $conn->error;
            } else {
                $stmt->bind_param("sss", $email, $user_name, $hashed_pass);

                if ($stmt->execute()) {
                    echo "1";
                } else {
                    echo "Insert failed: " . $stmt->error;
                }

                $stmt->close();
            }
        }
        catch(PDOException $e) {
            
        }
        try{
            $insertQuery = "INSERT INTO ip_logs(user_id, ip_address, event_, time_stamp) 
            VALUES((SELECT MAX(id) FROM userinfo), ?,'register', NOW())";
            $stmt = $conn->prepare($insertQuery);

            if ($stmt === false) {
                echo "Prepare failed: " . $conn->error;
            } else {
                $stmt->bind_param("s", $ip);

                if ($stmt->execute()) {
                    echo "1";
                } else {
                    echo "Insert failed: " . $stmt->error;
                }

                $stmt->close();
            }
        }
        catch(PDOException $e) {
            echo "failed to insert into ip_logs";
        }
        try{
            $insertQuery = "INSERT INTO user_roles(user_id, role_) VALUES((SELECT MAX(id) FROM userinfo), 'cashier')";
            $stmt = $conn->prepare($insertQuery);

            if ($stmt === false) {
                echo "Prepare failed: " . $conn->error;
            } else {
                
                if ($stmt->execute()) {
                    echo "1";
                } else {
                    echo "Insert failed: " . $stmt->error;
                }

                $stmt->close();
            }
        }
        catch(PDOException $e) {
            echo "failed to insert into ip_logs";
        }
             
    }
    else{
        echo $messageString;
    }

    mysqli_close($conn);  
    
} 
else {
    
}
?>