<?php
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
        'status' => 'error', 
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// Function to get product overview
function getProductOverview($conn) {
    $overview = [
        'cat' => [
            'total_products' => 0,
            'total_stock' => 0,
            'total_value_php' => 0
        ],
        'dog' => [
            'total_products' => 0,
            'total_stock' => 0,
            'total_value_php' => 0
        ],
        'bird' => [
            'total_products' => 0,
            'total_stock' => 0,
            'total_value_php' => 0
        ]
    ];

    // Get product counts and total sales including product names
    $sql = "SELECT 
                category, 
                product_name,
                COUNT(*) as total_products,
                SUM(stock) as total_stock,
                SUM(price * stock) as total_value_php
            FROM addproducts
            GROUP BY category, product_name";
    
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $category = strtolower($row['category']);
        $product_name = strtolower($row['product_name']);

        // Check both category and product name for relevant keywords
        if (strpos($category, 'cat') !== false || strpos($product_name, 'cat') !== false) {
            $overview['cat']['total_products'] += $row['total_products'];
            $overview['cat']['total_stock'] += $row['total_stock'];
            $overview['cat']['total_value_php'] += $row['total_value_php'];
        } 
        if (strpos($category, 'dog') !== false || strpos($product_name, 'dog') !== false) {
            $overview['dog']['total_products'] += $row['total_products'];
            $overview['dog']['total_stock'] += $row['total_stock'];
            $overview['dog']['total_value_php'] += $row['total_value_php'];
        } 
        if (strpos($category, 'bird') !== false || strpos($product_name, 'bird') !== false) {
            $overview['bird']['total_products'] += $row['total_products'];
            $overview['bird']['total_stock'] += $row['total_stock'];
            $overview['bird']['total_value_php'] += $row['total_value_php'];
        }
    }

    return $overview;
}

// Function to get inventory status
function getInventoryStatus($conn) {
    $inventory = [];
    
    $sql = "SELECT 
            category, 
            product_name, 
            stock,
            CASE 
                WHEN stock < 20 THEN 'Critical'
                WHEN stock BETWEEN 20 AND 50 THEN 'Low'
                ELSE 'Sufficient'
            END AS stock_status
            FROM addproducts
            ORDER BY stock_status, stock ASC";
    
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $inventory[] = $row;
    }
    
    return $inventory;
}

// Function to get orders overview
function getOrdersOverview($conn) {
    $sql = "SELECT 
            COUNT(*) AS total_orders,
            ROUND(SUM(total_price), 2) AS total_revenue_php,
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_orders,
            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_orders
            FROM orders";
    
    $result = $conn->query($sql);
    
    return $result->fetch_assoc();
}

// Function to generate custom report
function generateCustomReport($conn, $type, $start_date, $end_date) {
    $report = [];
    
    switch($type) {
        case 'Product Sales':
            $sql = "SELECT 
                    p.category, 
                    p.product_name, 
                    COUNT(o.order_id) AS total_sales,
                    SUM(o.quantity) AS total_quantity,
                    ROUND(SUM(o.total_price), 2) AS total_revenue_php
                    FROM orders o
                    JOIN addproducts p ON o.product_id = p.product_id
                    WHERE o.order_date BETWEEN ? AND ?
                    GROUP BY p.category, p.product_name";
            break;
        
        case 'Inventory':
            $sql = "SELECT 
                    category, 
                    product_name, 
                    stock, 
                    price AS price_php
                    FROM addproducts
                    ORDER BY stock ASC";
            break;
        
        case 'Customer Analysis':
            $sql = "SELECT 
                    preferred_pet, 
                    COUNT(*) AS customer_count,
                    ROUND(AVG(total_spending), 2) AS avg_spending_php
                    FROM customers
                    GROUP BY preferred_pet";
            break;
        
        case 'Order Trends':
            $sql = "SELECT 
                    MONTH(order_date) AS month,
                    COUNT(*) AS total_orders,
                    ROUND(SUM(total_price), 2) AS total_revenue_php
                    FROM orders
                    WHERE order_date BETWEEN ? AND ?
                    GROUP BY MONTH(order_date)";
            break;
        
        default:
            return ['error' => 'Invalid report type'];
    }
    
    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters for date-based queries
    if (in_array($type, ['Product Sales', 'Order Trends'])) {
        $stmt->bind_param("ss", $start_date, $end_date);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $report[] = $row;
    }
    
    return $report;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    
    $response = ['status' => 'success'];
    
    if (isset($_GET['action'])) {
        switch($_GET['action']) {
            case 'product_overview':
                $response['data'] = getProductOverview($conn);
                break;
            
            case 'inventory':
                $response['data'] = getInventoryStatus($conn);
                break;
            
            case 'orders':
                $response['data'] = getOrdersOverview($conn);
                break;
            
            case 'customers':
                $response['data'] = getCustomerInsights($conn);
                break;
            
            case 'custom_report':
                $type = $_GET['type'] ?? 'Product Sales';
                $start_date = $_GET['start_date'] ?? date('Y-01-01');
                $end_date = $_GET['end_date'] ?? date('Y-12-31');
                
                $response['data'] = generateCustomReport($conn, $type, $start_date, $end_date);
                break;
            
            default:
                $response = [
                    'status' => 'error', 
                    'message' => 'Invalid action'
                ];
        }
    } else {
        $response = [
            'status' => 'error', 
            'message' => 'No action specified'
        ];
    }
    
    echo json_encode($response);
    exit();
}

// Close the database connection
$conn->close();
?>
