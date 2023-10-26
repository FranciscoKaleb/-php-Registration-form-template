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
        $selectQuery = "SELECT status_ FROM sessions_ WHERE 
        token = ? AND user_id = ?";
        
        
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("si", $sessionStringHash, $user_id);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();

        $result = ['status' => $status];
        echo json_encode($result);
        
        $stmt->close();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
    
} 
else {
    //header('Location: ../register.html');
}
?>