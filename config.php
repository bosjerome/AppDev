<?php
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = "";     // Default MySQL password (leave blank for XAMPP)
$dbname = "clothing_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>