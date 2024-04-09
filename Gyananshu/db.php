<?php
// Database configuration
$host = "localhost"; // or your host name/IP
$username = "root"; // your database username
$password = ""; // your database password
$database = "gyananshu"; // your database name

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
