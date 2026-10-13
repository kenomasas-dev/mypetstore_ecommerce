<?php
// File: get_dashboard_data.php
// This file provides all the data needed for the dashboard in one request

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Initialize response array
$response = [
    "stats" => [],
    "sales" => [],
    "categories" => [],
    "recent_orders" => []
];

// 1. Get stats data
// Total Products
$sql = "SELECT COUNT(*) as total FROM addproducts";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $response["stats"]["total_products"] = $row["total"];
}

// Monthly Revenue (using accepted_orders table)
$sql = "SELECT SUM(total_price) as monthly_revenue FROM accepted_orders WHERE MONTH(pickup_date) = MONTH(CURRENT_DATE())";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $response["stats"]["monthly_revenue"] = $row["monthly_revenue"] ?? 0;
}

// New Orders (accepted orders from last 7 days)
$sql = "SELECT COUNT(*) as new_orders FROM accepted_orders WHERE pickup_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $response["stats"]["new_orders"] = $row["new_orders"] ?? 0;
}

// Low Stock Items
$sql = "SELECT COUNT(*) as low_stock FROM addproducts WHERE stock < 10";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $response["stats"]["low_stock"] = $row["low_stock"] ?? 0;
}

// 2. Get monthly sales data for current and previous year
$months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
$response["sales"]["labels"] = $months;

// Current year sales (using accepted_orders)
$currentYearSales = [];
$sql = "SELECT MONTH(pickup_date) as month, SUM(total_price) as total 
        FROM accepted_orders 
        WHERE YEAR(pickup_date) = YEAR(CURRENT_DATE()) 
        GROUP BY MONTH(pickup_date)";
$result = $conn->query($sql);

// Initialize with zeros
for ($i = 1; $i <= 12; $i++) {
    $currentYearSales[$i] = 0;
}

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $currentYearSales[(int)$row["month"]] = (float)$row["total"];
    }
}
$response["sales"]["current_year"] = array_values($currentYearSales);

// Previous year sales (using accepted_orders)
$previousYearSales = [];
$sql = "SELECT MONTH(pickup_date) as month, SUM(total_price) as total 
        FROM accepted_orders 
        WHERE YEAR(pickup_date) = YEAR(CURRENT_DATE()) - 1 
        GROUP BY MONTH(pickup_date)";
$result = $conn->query($sql);

// Initialize with zeros
for ($i = 1; $i <= 12; $i++) {
    $previousYearSales[$i] = 0;
}

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $previousYearSales[(int)$row["month"]] = (float)$row["total"];
    }
}
$response["sales"]["previous_year"] = array_values($previousYearSales);

// 3. Get category distribution
$sql = "SELECT category, COUNT(*) as count FROM addproducts GROUP BY category";
$result = $conn->query($sql);

$categories = [];
$categoryCounts = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row["category"];
        $categoryCounts[] = (int)$row["count"];
    }
}

$response["categories"]["labels"] = $categories;
$response["categories"]["data"] = $categoryCounts;

// 4. Get recent orders from accepted_orders
$sql = "SELECT id, tracking_id, product_name, quantity, total_price, pickup_date, status
        FROM accepted_orders 
        ORDER BY pickup_date DESC 
        LIMIT 5";
$result = $conn->query($sql);

$recentOrders = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Since accepted_orders might not have customer_name, we'll handle that
        // You may need to adjust this based on your actual database structure
        $recentOrders[] = [
            "id" => $row["id"],
            "tracking_id" => $row["tracking_id"],
            "product" => $row["product_name"],
            "date" => date("F j, Y", strtotime($row["pickup_date"])),
            "total" => $row["total_price"],
            "status" => $row["status"]
        ];
    }
}
$response["recent_orders"] = $recentOrders;

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);



// Close the database connection
$conn->close();
?>
