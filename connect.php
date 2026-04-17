<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "fixndrive_db";

// Using MySQLi for consistency across your files
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>