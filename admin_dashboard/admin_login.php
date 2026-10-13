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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Query to check admin credentials
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Check password (ensure password hashing in production)
        if ($password === $admin["password"]) {
            $_SESSION["adminUser"] = $admin["username"];

            echo json_encode([
                "status" => "success",
                "message" => "Login successful.",
                "username" => $admin["username"]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No account found with that username."]);
    }

    $stmt->close();
}

$conn->close();
?>

