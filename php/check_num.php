
<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $json = file_get_contents('php://input');

    $formData = json_decode($json, true);

    $phone_number = $formData["phone_number"];


try {
        $checkQuery2 = "SELECT * FROM userinfo WHERE phone_number = ?";
        $stmt = $conn->prepare($checkQuery2);
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();
        
        // Check for results
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo ("1"); // Phone number exists, its not available
        } else {
            echo ("0"); // Phone number is not found which means its available
        }
        
        $stmt->close();
    } 
    
    catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} 
else {
   header('Location: registration.html');
}
?>