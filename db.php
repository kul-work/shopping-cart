<?php

// Other settings
$currency = ' $';


// DB settings
$servername = "localhost";
$username = "root";
$password = ""; // Change this if you have a password set for your MySQL server
$dbname = "shopping_cart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Other functions
function sanitize($string, $nostriptags=false) {
    global $conn;
    
    $string = str_replace("'","",$string);
    $string = str_replace("\"","",$string);
    if (!$nostriptags) $string = strip_tags($string);
    $string = trim(rtrim(ltrim($string)));

    return mysqli_real_escape_string($conn, $string);
}
?>
