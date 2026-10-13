<?php
// fetch_random_ad.php - Fetches random products for the advertisement carousel

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// Get random products from the database
$sql = "SELECT * FROM addproducts ORDER BY RAND() LIMIT 5";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'product_name' => htmlspecialchars($row['product_name']),
            'price' => number_format($row['price'], 2),
            'image_url' => htmlspecialchars($row['image_url']),
            'category' => htmlspecialchars($row['category'])
        ];
    }
}

// Close connection
$conn->close();

// Return products as JSON
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'products' => $products
]);
?>
