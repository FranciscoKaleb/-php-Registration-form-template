<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $json = file_get_contents('php://input');

    $formData = json_decode($json, true);

    $citymunCode = $formData["citymunCode"];

    //echo json_encode($provCode);

    try {
        $checkQuery = "SELECT brgyDesc, brgyCode FROM refbrgy WHERE 
        citymunCode = ?";
        
        
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $citymunCode);
        $stmt->execute();
        $stmt->bind_result($brgyDesc, $brgyCode);
        $results = array();

        while ($stmt->fetch()) {
            $resultItem = array(
                "brgyDesc" => $brgyDesc,
                "brgyCode"=> $brgyCode
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