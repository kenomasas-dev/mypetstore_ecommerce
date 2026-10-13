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

// Handle status update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $update_sql = "UPDATE orders SET status='$status' WHERE id=$order_id";
    if ($conn->query($update_sql)) {
        echo json_encode(["status" => "success", "message" => "Order status updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
    exit;
}

// Fetch orders from database
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff View Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Enhanced Table Styling */
body {
    background-color: #f4f7f6;
    font-family: 'Arial', sans-serif;
}



.table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    width: 100%; /* Make the table stretch to fit its container */
    table-layout: auto; /* Ensures columns adjust to content */
}



.table thead {
    background-color: #4a90e2;
    color: white;
    transition: background-color 0.3s ease;
    
}

.table thead tr th {
    font-weight: 550;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    padding: 15px;
    
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(74, 144, 226, 0.1);
    transform: scale(1.01);
}

.table tbody tr td {
    vertical-align: middle;
    padding: 12px 15px;
}

/* Status Styling */
.table tbody tr td[id^="status-"] {
    font-weight: 600;
    text-transform: uppercase;
}

.table tbody tr td[id^="status-"]:hover {
    transform: scale(1.05);
}

/* Button Styling */
.btn-success, .btn-danger {
    margin: 2px;
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-success:hover, .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Responsive Animations */
@keyframes fadeInTable {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.table tbody tr {
    animation: fadeInTable 0.5s ease forwards;
    opacity: 0;
}

.table tbody tr:nth-child(1) { animation-delay: 0.1s; }
.table tbody tr:nth-child(2) { animation-delay: 0.2s; }
.table tbody tr:nth-child(3) { animation-delay: 0.3s; }
.table tbody tr:nth-child(4) { animation-delay: 0.4s; }
.table tbody tr:nth-child(5) { animation-delay: 0.5s; }

/* Modal Enhancements */
.modal-content {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background-color:rgb(231, 234, 238);
    color: white;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.modal-header .btn-close {
    background-color: white;
    border-radius: 50%;
    opacity: 1;
}

.main-content {
    margin-left: 237px; /* Adjust based on sidebar width */
    padding: 20px;
    width: calc(100% - 250px);
    margin-top: -70px;
    
}


    </style>
</head>
<body>
<div id="sidebar" style="margin-top: 70px;">
        <div class="text-center py-3">
            <img src="mypetstorelogo.png" alt="Logo" width="80" height="80">
            <h5 class="mt-2">Staff Panel</h5>
        </div>
        <ul class="nav flex-column">
         
        <li class="nav-item">
            <a class="nav-link" href="staffviewproduct.html">
                <i class="fas fa-box me-2"></i>Products
            </a>
        </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="ordersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-shopping-cart me-2"></i> Orders
                </a>
                <ul class="dropdown-menu" aria-labelledby="ordersDropdown">
                    <li><a class="dropdown-item" href="staffvieworders.php">View Orders</a></li>
                    <li><a class="dropdown-item" href="staffrecentorders.php">Recent Orders</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="settings.html"><i class="fa fa-cog"></i> Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="customer.php"><i class="fas fa-users me-2"></i> Customers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.html"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </li>
        </ul>
    </div>

   <div class="main-content">
    <div class="container mt-5">
        <h2 class="text-center"></h2>
        <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>Tracking_ID</th>
                <th>First_Name</th>
                <th>Last_Name</th>
                <th>Product_Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total_Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr id="row-<?php echo $row['tracking_id']; ?>">
            <td><?php echo htmlspecialchars($row['tracking_id']); ?></td>
            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            <td><?php echo htmlspecialchars($row['price']); ?></td>
            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
            <td id="status-<?php echo $row['tracking_id']; ?>"><?php echo htmlspecialchars($row['status'] ?? 'Pending'); ?></td>
            <td>
                <button class="btn btn-success btn-sm accept-btn" 
                    data-tracking="<?php echo $row['tracking_id']; ?>" 
                    data-product="<?php echo $row['product_name']; ?>" 
                    data-quantity="<?php echo $row['quantity']; ?>" 
                    data-total="<?php echo $row['total_price']; ?>">
                    Accept
                </button>
                
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

    </table>
</div>

<!-- Accept Order Modal -->
<div class="modal fade" id="acceptOrderModal" tabindex="-1" aria-labelledby="acceptOrderLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Pickup Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="acceptOrderForm">
                    <input type="hidden" id="tracking_id" name="tracking_id">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" id="product_name" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="text" id="quantity" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Price</label>
                        <input type="text" id="total_price" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pickup Date</label>
                        <input type="date" id="pickup_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(".accept-btn").click(function() {
        let trackingId = $(this).data("tracking");
        let productName = $(this).data("product");
        let quantity = $(this).data("quantity");
        let totalPrice = $(this).data("total");

        $("#tracking_id").val(trackingId);
        $("#product_name").val(productName);
        $("#quantity").val(quantity);
        $("#total_price").val(totalPrice);

        $("#acceptOrderModal").modal("show");
    });

    $("#acceptOrderForm").submit(function(e) {
        e.preventDefault();
        let trackingId = $("#tracking_id").val();
        let pickupDate = $("#pickup_date").val();

        $.post("update_order.php", { tracking_id: trackingId, status: "Accepted", pickup_date: pickupDate }, function(response) {
            alert(response);
            $("#status-" + trackingId).text("Accepted");
            $("#acceptOrderModal").modal("hide");
        });
    });

    $(".decline-btn").click(function() {
        let trackingId = $(this).data("tracking");
        $.post("update_order.php", { tracking_id: trackingId, status: "Declined" }, function(response) {
            alert(response);
            $("#status-" + trackingId).text("Declined");
        });
    });
});
</script>

<script>
    $(document).ready(function() {
    $("#acceptOrderForm").submit(function(e) {
        e.preventDefault();
        let trackingId = $("#tracking_id").val();
        let pickupDate = $("#pickup_date").val();

        $.post("update_order.php", { tracking_id: trackingId, status: "Accepted", pickup_date: pickupDate }, function(response) {
            let res = JSON.parse(response);
            alert(res.message);
            if (res.status === "success") {
                $("#row-" + trackingId).remove(); // Remove row from table
                $("#acceptOrderModal").modal("hide");
            }
        });
    });

    $(".decline-btn").click(function() {
        let trackingId = $(this).data("tracking");
        $.post("update_order.php", { tracking_id: trackingId, status: "Declined" }, function(response) {
            let res = JSON.parse(response);
            alert(res.message);
            if (res.status === "success") {
                $("#status-" + trackingId).text("Declined");
            }
        });
    });
});

</script>

</body>
</html>

<?php $conn->close(); ?>
