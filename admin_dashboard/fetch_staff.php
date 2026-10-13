<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Fetch staff data
$sql = "SELECT first_name, last_name, email, phone, role FROM staff";
$result = $conn->query($sql);

$staffData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staffData[] = $row;
    }
}

echo json_encode($staffData);

$conn->close();
?>
