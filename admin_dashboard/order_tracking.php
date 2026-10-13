<?php
// database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['tracking_id'])) {
    $tracking_id = $_POST['tracking_id'];

    $query = "SELECT * FROM accepted_orders WHERE tracking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $tracking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $order]);
    } else {
        echo json_encode(["status" => "error", "message" => "Tracking ID not found."]);
    }

    $stmt->close();
}

$conn->close();
?>
