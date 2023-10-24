<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$json = file_get_contents('php://input');
    //$formData = json_decode($json, true);


    $ip = $_POST["ip_address"];
    $user_name = $_POST["username"];
    $pass_word = $_POST["password"]; 

    // Perform input validation and sanitation as needed here

    try {
        $selectQuery = "SELECT user_id, password_ FROM user_credentials WHERE user_name = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedHash = $row['password_'];

            // Verify the hashed password
            if (password_verify($pass_word, $storedHash)) {
                //echo json_encode(['user_id' => $row['user_id']]);
                $html_content = '<html>';
                $html_content .= '<head><title>Welcome to Home</title></head>';
                $html_content .= '<body>';
                $html_content .= '<p> Hi </p>';
                $html_content .= '<h1>Welcome, ' . $user_data['username'] . '</h1>';
                $html_content .= '<p>Your email: ' . $user_data['email'] . '</p>';
                // Include more user-specific data here
                $html_content .= '</body>';
                $html_content .= '</html>';
                echo $html_content;
                //header('Location: ../home.html');
                
            } else {
                //echo json_encode(['error' => 'Invalid credentials']);
            }
        } else {
            echo json_encode(['error' => 'User not found']);
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