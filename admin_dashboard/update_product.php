<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}

$id = $_POST["id"];
$product_name = $_POST["product_name"];
$category = $_POST["category"];
$price = $_POST["price"];
$stock = $_POST["stock"];
$image_url = "";

// Handle image upload
if (isset($_FILES["image"]) && $_FILES["image"]["size"] > 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    } else {
        die(json_encode(["success" => false, "error" => "Image upload failed"]));
    }
}

// Update query
if ($image_url) {
    $sql = "UPDATE addproducts SET product_name=?, category=?, price=?, stock=?, image_url=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $product_name, $category, $price, $stock, $image_url, $id);
} else {
    $sql = "UPDATE addproducts SET product_name=?, category=?, price=?, stock=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $product_name, $category, $price, $stock, $id);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>

