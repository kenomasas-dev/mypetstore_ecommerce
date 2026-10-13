<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Handle place order request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the JSON data sent from the frontend
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
        exit;
    }

    // Validate and sanitize input data
    $trackingId = $conn->real_escape_string($data["tracking_id"] ?? "");
    $firstName = $conn->real_escape_string($data["first_name"] ?? "");
    $lastName = $conn->real_escape_string($data["last_name"] ?? "");
    $productName = $conn->real_escape_string($data["product_name"] ?? "");
    $quantity = intval($data["quantity"] ?? 0);
    $price = floatval($data["price"] ?? 0);
    $totalPrice = floatval($data["total_price"] ?? 0);

    // Validate required fields
    if (empty($trackingId) || empty($firstName) || empty($lastName) || empty($productName) || $quantity <= 0 || $price <= 0 || $totalPrice <= 0) {
        echo json_encode(["status" => "error", "message" => "Missing or invalid order data"]);
        exit;
    }

    // Check product stock
    $stockCheckQuery = "SELECT stock FROM addproducts WHERE product_name = '$productName'";
    $stockResult = $conn->query($stockCheckQuery);

    if ($stockResult->num_rows > 0) {
        $row = $stockResult->fetch_assoc();
        $currentStock = $row['stock'];

        if ($currentStock >= $quantity) {
            // Insert order into the database
            $insertOrderQuery = "INSERT INTO orders (tracking_id, first_name, last_name, product_name, quantity, price, total_price)
                                 VALUES ('$trackingId', '$firstName', '$lastName', '$productName', $quantity, $price, $totalPrice)";

            if ($conn->query($insertOrderQuery)) {
                // Deduct stock
                $newStock = $currentStock - $quantity;
                $updateStockQuery = "UPDATE addproducts SET stock = $newStock WHERE product_name = '$productName'";

                if ($conn->query($updateStockQuery)) {
                    // Remove the item from the cart
                    $deleteCartQuery = "DELETE FROM cart WHERE product_name = '$productName'";
                    if ($conn->query($deleteCartQuery)) {
                        echo json_encode(["status" => "success", "message" => "Order placed successfully! Item removed from cart. Tracking ID: $trackingId"]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Failed to remove item from cart: " . $conn->error]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to update stock: " . $conn->error]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Insufficient stock for $productName"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Product not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>

