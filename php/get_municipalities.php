<?php


// THIS PROCESS IS GETTING DATA FROM SERVER [[[WITH]]] USER PARTICULAR REQUEST

include "db_config.php"; // [-1] include the database config which is in a separate file so it could be reused

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // [0] see if theres a request


    $json = file_get_contents('php://input'); // [1] get the data in json form
    $formData = json_decode($json, true);     // [2] convert json data into php object
    $provCode = $formData["provCode"];        // [3] assign to variable the data from the object

    //echo json_encode($provCode);

    try {
        $selectQuery = "SELECT citymunDesc, citymunCode FROM refcitymun WHERE 
        provCode = ?"; // [4] create the query with placeholder
        
        
        $stmt = $conn->prepare($selectQuery);// [5] prepare the query to process the placeholder, to only accept data thats necessary
        $stmt->bind_param("s", $provCode); //  [6] bind the value of provCode to the placeholder
        $stmt->execute(); //[7] excute the command


        // handling the result
        $stmt->bind_result($citymunDesc, $citymunCode); // [8] create two variable to handle the result of the query
        $results = array(); // [9] create an associative array to store objects

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