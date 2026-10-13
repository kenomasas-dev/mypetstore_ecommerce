<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_POST["id"] ?? null;

if (!$id) {
    die(json_encode(["success" => false, "error" => "Invalid product ID"]));
}

// Get image path before deleting product permanently
$sql = "SELECT image_url FROM addproducts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$imagePath = $row["image_url"] ?? "";

// Permanently delete product from database
$sql = "DELETE FROM addproducts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Permanently delete image file if it exists
    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }
    echo json_encode(["success" => true, "message" => "Product deleted permanently"]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>

