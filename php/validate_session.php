<?php
// used by check_cookies.js
// code algorithm
// [1] get the session cookie user id and session string
// [2] if session active then
    // if user has no activity for time span
        // yes? - log out
        // no? - log in
// [2] else
    // log out
include "db_config.php";
include "dynamic_html.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $formData = json_decode($json, true);

    $ip = $formData["ip"];  
    $user_id = $formData["user_id"];
    $sessionStringHash = $formData["sessionStringHash"];

    try { 
        $selectQuery = "SELECT status_ FROM sessions_ WHERE 
        token = ? AND user_id = ?";
        
        
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("si", $sessionStringHash, $user_id);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();
        $stmt->close();
        $result = ['status' => $status];
        
        if ($result['status'] == "active"){
            // check last activity
            // query is like this
            // SELECT timestampdiff(minute,(SELECT time_stamp from ip_logs 
            // WHERE user_id = 134 AND log_id = (SELECT MAX(log_id) FROM ip_logs WHERE user_id = 134)),NOW()) 
            $selectQuery3 = "SELECT timestampdiff(minute,(SELECT time_stamp from ip_logs 
            WHERE user_id = ? AND log_id = (SELECT MAX(log_id) FROM ip_logs WHERE user_id = ?)),NOW()) AS diff";
            $stmt3 = $conn->prepare($selectQuery3);
            $stmt3->bind_param("ii", $user_id, $user_id);
            $stmt3->execute();
            $stmt3->bind_result($diff);
            $stmt3->fetch();
            $stmt3->close();
            $result3 = ['diff' => $diff];
            
            if ($result3['diff'] > 1){ // session expired
               // log out code
                // [1] set session from active to expired
                $updateQuery = "UPDATE sessions_ set status_ = 'expired' WHERE 
                token = ? AND user_id = ?";
                $stmt4 = $conn->prepare($updateQuery);
                $stmt4->bind_param("si", $sessionStringHash, $user_id);
                $stmt4->execute();
                $stmt4->close();
                // [2] log event as logout/session expired log out
                $insertQuery5 = "INSERT INTO ip_logs(user_id, session_id, ip_address, event_,time_stamp) 
                VALUES(?,(SELECT MAX(session_id) FROM sessions_),?,'session expired logout',NOW())";
                $stmt5 = $conn->prepare($insertQuery5);
                $stmt5->bind_param("is", $user_id,$ip);
                $stmt5->execute();
                $stmt5->close();

                // [3] 
                echo "0";
    
            }
            else{ // session not expired
                // echo dashboard
                $selectQuery2 = "SELECT role_ FROM user_roles WHERE user_id = ?";
                $stmt2 = $conn->prepare($selectQuery2);
                $stmt2->bind_param("i", $user_id);
                $stmt2->execute();
                $stmt2->bind_result($role);
                $stmt2->fetch();
        
                $result2 = ['role_' => $role];
                
                if ($result2['role_'] == "admin"){
                echo $dashboardhtml;
        
                }
                else{
                echo $cashierdashboardhtml;
                }
                $stmt2->close(); 
            }
            
        }
        else{
           echo $loginhtml;
        }    

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
    
} 
else {
    //header('Location: ../register.html');
}
?>