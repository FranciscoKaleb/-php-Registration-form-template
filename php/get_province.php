<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $json = file_get_contents('php://input');
    $formData = json_decode($json, true);
    $regDesc = $formData["regDesc"];

    try {
        $selectQuery = "SELECT provDesc, provCode FROM refprovince WHERE 
        regCode = (SELECT regCode FROM refregion WHERE regDesc = ?)";
        
        
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("s", $regDesc);
        $stmt->execute();
        $stmt->bind_result($provDesc, $provCode);
        $results = array();

        while ($stmt->fetch()) {
            $resultItem = array(
                "provDesc" => $provDesc,
                "provCode"=> $provCode
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