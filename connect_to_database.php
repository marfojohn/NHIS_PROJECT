<?php 
    //This connects to the NHIS database 
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpassword = "";
    $database = "nhis_db";

    try{
        $conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $database);
    }
    catch(mysqli_sql_exception){
        echo "Could not connect";
    
    }
?>