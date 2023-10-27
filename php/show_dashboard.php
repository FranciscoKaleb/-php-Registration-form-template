<?php
include "db_config.php";
include "dynamic_html.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo $dashboardhtml;   
    
} 
else {
    //header('Location: ../register.html');
}
?>