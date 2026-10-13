<?php
// Database connection (replace with your actual database details)
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'db';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data and sanitize input
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($dob) || empty($gender) || empty($password) || empty($confirm_password)) {
        $response['message'] = 'All fields are required';
        echo json_encode($response);
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format';
        echo json_encode($response);
        exit();
    }
    
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $response['message'] = 'Invalid phone number format';
        echo json_encode($response);
        exit();
    }
    
    if ($password !== $confirm_password) {
        $response['message'] = 'Passwords do not match';
        echo json_encode($response);
        exit();
    }

    // Check if the email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $response['message'] = 'Email is already registered';
        echo json_encode($response);
        $check_stmt->close();
        $conn->close();
        exit();
    }
    $check_stmt->close();

    // Store the password in plain text (Not Recommended for Security)
    $plain_password = $password;

    // Prepare and execute the SQL insert query
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, middle_name, email, phone, dob, gender, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $first_name, $last_name, $middle_name, $email, $phone, $dob, $gender, $plain_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! You can now login.'); window.location.href='shop.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to register user.'); window.location.href='shop.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Return the response as JSON
echo json_encode($response);
?>
