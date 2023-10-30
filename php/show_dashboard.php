<?php
// used by log_in.js
include "db_config.php";
include "dynamic_html.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // run a db command selecting user id in roles, if admin then to admin, if cashier then to cashier

    $json = file_get_contents('php://input');
    $formData = json_decode($json, true);

    $ip = $formData["ip"];  
    $user_id = $formData["user_id"];
    $sessionStringHash = $formData["sessionStringHash"];

    try {
        $selectQuery = "SELECT role_ FROM user_roles WHERE user_id = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();

        $result = ['role_' => $role];
        
        if ($result['role_'] == "admin"){
           echo $dashboardhtml;

        }
        else{
           echo $cashierdashboardhtml;
        }

        
        $stmt->close();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
    
} 
else {
    //header('Location: ../register.html');
}
?>