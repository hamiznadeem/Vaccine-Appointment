<?php

function safe_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

// Database Connection Configuration

// Database Credentials
$hostname = "localhost";
$username = "root";
$password = "";
$database = "vaccination_db";

// Check connection
$conn = mysqli_connect($hostname, $username, $password, $database);
    if(!$conn){
        die("". mysqli_connect_error());
    }else{
        echo "<script> console.log('$database: Database Connected') </script>";
    }


?>
