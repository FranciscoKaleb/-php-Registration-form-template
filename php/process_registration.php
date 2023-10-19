<?php
// Include database connection configuration
include "db_config.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

     // Get the JSON data from the request
     $json = file_get_contents('php://input');
    
     // Decode the JSON data into a PHP array
     $formData = json_decode($json, true);

    // Retrieve user input
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $birthday = $_POST['birthday'];
    $user_name = $_POST['user_name'];
    $pass_word = $_POST['password'];
    $ip_address = $_POST['ip_address'];

    // Validate user input (add more validation as needed)

    try{
        // Check if the username and email already exist
        $checkQuery = "SELECT * FROM user_credentials WHERE user_name = '$user_name' OR email = '$email'";
        $result = mysqli_query($conn, $checkQuery);

        $checkQuery2 = "SELECT * FROM userinfo WHERE phone_number = '$phone_number'";
        $result2 = mysqli_query($conn, $checkQuery2);
    }
    catch(Exception $e){
        echo"". $e->getMessage();
    }
    
    

    if (mysqli_num_rows($result) > 0) {
        echo "Username or email already exists.";
    } 

    else if(mysqli_num_rows($result2) > 0){
        echo "Phone number already exists.";
    }

    else {
        try{
            // Insert user data into the database
            $insertQuery = "INSERT INTO userinfo (first_name, last_name, phone_number, birth_date) 
            VALUES ('$first_name', '$last_name', '$phone_number', '$birthday')";



            $insertQuery2 = "INSERT INTO user_credentials (user_id, email, user_name, password_) 
            VALUES ((SELECT MAX(id) FROM userinfo),'$email', '$user_name', '$pass_word')";



            $insertQuery3 = "INSERT INTO ip_logs(user_id, ip_address, event_, time_stamp) 
            VALUES ((SELECT MAX(id) FROM userinfo),'$ip_address', 'register', NOW())";

            //$insertQuery4 = ""

        }
        catch(Exception $e){
            echo"". $e->getMessage();
        }
        

        if (mysqli_query($conn, $insertQuery) == true && mysqli_query($conn, $insertQuery2) == true && mysqli_query($conn, $insertQuery3) == true) {

            //header(Location ..registrationsuccess.html)
            header("Location: ../register.html");
        } 

        else {
            echo "Registration failed: " . mysqli_error($conn);
        }
    }
    

    mysqli_close($conn);
} 
else {
    header('Location: registration.html');
}
?>
