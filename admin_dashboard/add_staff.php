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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $role = trim($_POST["role"]);
    $password = trim($_POST["password"]); // Store password as plain text (âš  Not Secure)

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM staff WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists!"]);
    } else {
        // Insert new staff
        $stmt = $conn->prepare("INSERT INTO staff (first_name, last_name, email, phone, role, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $role, $password);

        if ($stmt->execute()) {
            // Check if running on localhost
            $host = $_SERVER['HTTP_HOST'];
            if ($host == "localhost") {
                echo json_encode(["status" => "success", "message" => "Staff added successfully!", "notify" => true]);
            } else {
                echo json_encode(["status" => "success", "message" => "Staff added successfully!"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error adding staff."]);
        }
        $stmt->close();
    }

    $checkEmail->close();
    $conn->close();
}
?>

