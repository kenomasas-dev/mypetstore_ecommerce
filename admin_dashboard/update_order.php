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
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tracking_id = $_POST['tracking_id'];
    $status = $_POST['status'];

    if ($status == "Accepted") {
        $pickup_date = $_POST['pickup_date'];

        // Fetch order details from `orders` table
        $query = "SELECT product_name, quantity, total_price FROM orders WHERE tracking_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $tracking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if ($order) {
            // Insert into `accepted_orders` table
            $insert_query = "INSERT INTO accepted_orders (tracking_id, product_name, quantity, total_price, pickup_date, status) 
                             VALUES (?, ?, ?, ?, ?, 'Accepted')";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssids", $tracking_id, $order['product_name'], $order['quantity'], $order['total_price'], $pickup_date);
            $stmt->execute();
            $stmt->close();

            // Update status in `orders` table
            $update_query = "UPDATE orders SET status = 'Accepted' WHERE tracking_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("s", $tracking_id);
            $stmt->execute();
            $stmt->close();
            
            

            echo "Order accepted successfully!";
        } else {
            echo "Order not found.";
        }
    } elseif ($status == "Declined") {
        // Update status in `orders` table
        $update_query = "UPDATE orders SET status = 'Declined' WHERE tracking_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("s", $tracking_id);
        $stmt->execute();
        $stmt->close();

        echo "Order declined successfully!";
    }
}

if ($status === "Accepted") {
    // Delete the order from the database
    $delete_sql = "DELETE FROM orders WHERE tracking_id = '$tracking_id'";
    if ($conn->query($delete_sql)) {
        echo json_encode(["Order removed successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
} else {
    // Update order status
    $update_sql = "UPDATE orders SET status = '$status' WHERE tracking_id = '$tracking_id'";
    if ($conn->query($update_sql)) {
        echo json_encode(["status" => "success", "message" => "Order status updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
}


$conn->close();
?>

