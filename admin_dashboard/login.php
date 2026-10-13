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
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Query to check staff credentials
    $stmt = $conn->prepare("SELECT * FROM staff WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check password (assuming it's stored as plain text, but should use password_hash in real use)
        if ($password === $user["password"]) {
            $_SESSION["loggedInUser"] = $user["email"];
            $_SESSION["userRole"] = $user["role"];

            echo json_encode([
                "status" => "success",
                "message" => "Login successful.",
                "role" => $user["role"],
                "email" => $user["email"]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No account found with that email."]);
    }

    $stmt->close();
}

$conn->close();
?>

