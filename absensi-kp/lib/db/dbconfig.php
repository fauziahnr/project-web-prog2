<?php 
    $dbhost = "localhost";
    $username = "root";
    $password = "";
    $dbname = "absensi-kp";

    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
 ?>
