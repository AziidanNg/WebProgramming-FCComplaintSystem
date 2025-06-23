<?php
    $db_host = "localhost";
    $db_user = "root";
    $db_pwd  = "";
    $db_name = "MiniProject";

    $conn = mysqli_connect($db_host, $db_user, $db_pwd, $db_name);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }   
?>