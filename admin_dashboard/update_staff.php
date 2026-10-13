<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search_id'])) {
        // Search for staff by ID
        $staff_id = trim($_POST['search_id']);
        $stmt = $conn->prepare("SELECT * FROM staff WHERE id = ?");
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            echo json_encode(["status" => "error", "message" => "No staff found with this ID."]);
        }
        $stmt->close();
    } elseif (isset($_POST['staff_id'])) {
        // Update staff details
        $staff_id = trim($_POST['staff_id']);
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $role = trim($_POST['role']);
        $password = trim($_POST['password']);

        if (!empty($password)) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE staff SET first_name=?, last_name=?, email=?, phone=?, role=?, password=? WHERE id=?");
            $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $role, $password_hashed, $staff_id);
        } else {
            $stmt = $conn->prepare("UPDATE staff SET first_name=?, last_name=?, email=?, phone=?, role=? WHERE id=?");
            $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $role, $staff_id);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Staff information updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating staff."]);
        }
        $stmt->close();
    }
}
$conn->close();
?>

