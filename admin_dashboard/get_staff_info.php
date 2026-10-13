<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["loggedInUser"])) {
    echo json_encode(["status" => "error", "message" => "No staff is logged in."]);
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "db");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

$email = $_SESSION["loggedInUser"];

// Retrieve staff details
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, role FROM staff WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $staff = $result->fetch_assoc();
    echo json_encode(array_merge(["status" => "success"], $staff));
} else {
    echo json_encode(["status" => "error", "message" => "Staff information not found."]);
}

$stmt->close();
$conn->close();
?>

