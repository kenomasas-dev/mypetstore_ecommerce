<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $total_price = $_POST["total_price"];

    // Check if product is already in the cart
    $check_sql = "SELECT * FROM cart WHERE product_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing item quantity
        $update_sql = "UPDATE cart SET quantity = quantity + ?, total_price = total_price + ? WHERE product_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("idi", $quantity, $total_price, $product_id);
        $stmt->execute();
        echo "Cart updated successfully!";
    } else {
        // Insert new item into the cart
        $insert_sql = "INSERT INTO cart (product_id, product_name, price, quantity, total_price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("issid", $product_id, $product_name, $price, $quantity, $total_price);
        $stmt->execute();
        echo "Item added to cart successfully!";
    }
}

$conn->close();
?>

