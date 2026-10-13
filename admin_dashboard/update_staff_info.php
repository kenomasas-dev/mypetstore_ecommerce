<?php
session_start();
header("Content-Type: application/json");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Check if user is logged in
if (!isset($_SESSION["loggedInUser"])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit();
}

// Get logged-in user's email
$loggedInUser = $_SESSION["loggedInUser"];

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $first_name = trim($data["first_name"]);
    $last_name = trim($data["last_name"]);
    $email = trim($data["email"]);
    $phone = trim($data["phone"]);
    $role = trim($data["role"]);

    // Update staff information
    $stmt = $conn->prepare("UPDATE staff SET first_name = ?, last_name = ?, email = ?, phone = ?, role = ? WHERE email = ?");
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $role, $loggedInUser);

    if ($stmt->execute()) {
        // Update session email if changed
        $_SESSION["loggedInUser"] = $email;
        echo json_encode(["status" => "success", "message" => "Staff information updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating staff information."]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input data."]);
}

$conn->close();
?>

