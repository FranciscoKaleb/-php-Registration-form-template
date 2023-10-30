<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $formData = json_decode($json, true);


    $ip = $formData["ip"];
    $user_name = $formData["username"];
    $pass_word = $formData["password"]; 

    // Perform input validation and sanitation as needed here

    try {
        $selectQuery = "SELECT user_id, password_ FROM user_credentials WHERE user_name = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            //echo "1";
            $row = $result->fetch_assoc();
            $storedHash = $row['password_'];

            // Verify the hashed password
            if (password_verify($pass_word, $storedHash)) {
                // [1] create session 
                $user_id = $row['user_id'];
                $timestamp = (new DateTime())->getTimestamp();
                $sessionString = $user_id.$timestamp;
                $sessionStringHash = password_hash($sessionString, PASSWORD_DEFAULT);

                // [2] insert session to db
                $insertQuery = "INSERT INTO sessions_(user_id, token, status_) VALUES(?,?, 'active')";
                $stmt2 = $conn->prepare($insertQuery);
                $stmt2->bind_param("ss", $user_id, $sessionStringHash);
                $stmt2->execute();
                // [2.1] insert to ip logs to be added later
                $insertQuery2 = "INSERT INTO ip_logs(user_id, session_id, ip_address, event_,time_stamp) 
                VALUES(?,(SELECT MAX(session_id) FROM sessions_),?,'login',NOW())";
                $stmt3 = $conn->prepare($insertQuery2);
                $stmt3->bind_param("is", $user_id, $ip);
                $stmt3->execute();
                // [3] echo the sessionString 
                $data =[
                    'user_id' => $user_id,
                    'sessionStringHash' => $sessionStringHash,
                    'ip' => $ip
                ];
                echo json_encode($data);
                
            } else {
                echo 'wrong password';
            }
        } else {
            echo 'user does not exist';
        }

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        // Handle exceptions, log errors, or return an error response
        echo json_encode(['error' => 'Database error']);
    }
    
        //header('Location: dashboard.html');
    mysqli_close($conn);
    
} 
else {
    header('Location: ../register.html');
}
?>