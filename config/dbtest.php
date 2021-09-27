<?php

 
$host = "173.225.100.50";
$db_name = "andresma_andre_dani";
$username = "andresma_andredani";
$password = "AndreDani-1114";
$port = "3306";
    $conn;
 

    try {
        //$conn = new PDO("mysql:host=$servername;port=$port;dbname=$db_name", $username, $password);
        $conn = new PDO("mysql:host=" . $host . ";port=" . $port . ";dbname=" . $db_name, $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
      } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }



  
?>
