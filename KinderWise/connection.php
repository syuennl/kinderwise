<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "kinderwisedb";  
    $conn = new mysqli($servername, $username, $password, $db_name, 3306);
    if($conn->connect_error){
        die("Connection failed".$conn->connect_error);
    }
    // echo "Successfully connected to database"; // check if connected successfully
    
?>
