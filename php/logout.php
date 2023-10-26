<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $formData = json_decode($json, true);

    //$ip = $formData["password"];  to be added later
    $user_id = $formData["user_id"];
    $sessionStringHash = $formData["sessionStringHash"];
    
    //echo $user_id;

    // Perform input validation and sanitation as needed here
    try {
        $updateQuery = "UPDATE sessions_ set status_ = 'expired' WHERE 
        token = ? AND user_id = ?";
        
        
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $sessionStringHash, $user_id);
        $stmt->execute();
        echo "log out success";
        
        $stmt->close();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
    
} 
else {
    //header('Location: ../register.html');
}
?>