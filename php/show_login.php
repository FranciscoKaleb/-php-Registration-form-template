<?php
include "db_config.php";
include "dynamic_html.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    echo $loginhtml;   
} 
else {
    //header('Location: ../register.html');
}
?>