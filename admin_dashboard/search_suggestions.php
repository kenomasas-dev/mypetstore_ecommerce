<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'db';

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode([]));
}

// Get the search query
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$suggestions = [];

if (!empty($query)) {
    $stmt = $conn->prepare("SELECT product_name FROM addproducts WHERE product_name LIKE CONCAT('%', ?, '%') LIMIT 5");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['product_name'];
    }

    $stmt->close();
}

$conn->close();
echo json_encode($suggestions);
?>

