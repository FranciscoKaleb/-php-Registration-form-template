<?php
include "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // THIS PROCESS IS GETTING DATA FROM SERVER WITHOUT USER PARTICULAR REQUEST

        $checkQuery2 = "SELECT regDesc FROM refregion";// [1] Create a SQL query to fetch data from the database
        
        
        $stmt = $conn->prepare($checkQuery2);// [2] Prepare and execute the query
        $stmt->execute(); // [3] there is no parameter to bind so we directly execute the code
        
        
        $stmt->bind_result($regDesc); // [4] Create a variable to Bind the result 
        $results = array();// [5] Create an array to store the results
        
        
        while ($stmt->fetch()) { // [6] loop thru each row result set
            $results[] = $regDesc;
        }
        
        // Return the results as JSON
        header("Content-Type: application/json");
        echo json_encode($results);
        
        $stmt->close();
    } 
    
    catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: registration.html');
}
?>