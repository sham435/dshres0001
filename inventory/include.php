<?php
// include.php
$servername = "localhost";
$username = "root"; // Change as per your configuration
$password = ""; // Change as per your configuration
$dbname = "school_inventory_system";

// Create connection with socket
$conn = mysqli_connect($servername, $username, $password, $dbname, 3306, "/tmp/mysql.sock");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");
?>