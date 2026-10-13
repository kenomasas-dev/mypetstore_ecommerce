<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        'success' => false, 
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required'); window.location.href='login.php';</script>";
        exit();
    }

    // Modified query to also retrieve first_name and last_name
    $stmt = $conn->prepare("SELECT id, email, password, first_name, last_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('Invalid email or password'); window.location.href='shop.php';</script>";
        $stmt->close();
        $conn->close();
        exit();
    }

    $user = $result->fetch_assoc();

    // Password verification (replace with password_verify() for hashed passwords)
    if ($password === $user['password']) {
        // Set session variables including first_name and last_name
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        
        echo "<script>alert('Login successfully'); window.location.href='shop.php';</script>";
    } else {
        echo "<script>alert('Invalid email or password'); window.location.href='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method'); window.location.href='login.php';</script>";
}
?>
