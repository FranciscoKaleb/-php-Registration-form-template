<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $json = file_get_contents('php://input');

    $formData = json_decode($json, true);

    $provCode = $formData["provCode"];

    //echo json_encode($provCode);

    try {
        $checkQuery = "SELECT citymunDesc, citymunCode FROM refcitymun WHERE 
        provCode = ?";
        
        
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $provCode);
        $stmt->execute();
        $stmt->bind_result($citymunDesc, $citymunCode);
        $results = array();

        while ($stmt->fetch()) {
            $resultItem = array(
                "citymunDesc" => $citymunDesc,
                "citymunCode"=> $citymunCode
            );
            $results[] = $resultItem;
        }
        header("Content-Type: application/json");
        echo json_encode($results);
        
        $stmt->close();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: registration.html');
}
    
?>