<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Create a SQL query to fetch data from the database
        $checkQuery2 = "SELECT regDesc FROM refregion";
        
        // Prepare and execute the query
        $stmt = $conn->prepare($checkQuery2);
        $stmt->execute();
        
        // Bind the result to a variable
        $stmt->bind_result($regDesc);
        
        // Create an array to store the results
        $results = array();
        
        // Fetch results and store them in the array
        while ($stmt->fetch()) {
            $results[] = $regDesc;
        }
        
        // Return the results as JSON
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