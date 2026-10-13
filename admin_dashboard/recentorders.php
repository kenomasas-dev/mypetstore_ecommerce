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

// Fetch accepted orders
$sql = "SELECT tracking_id, product_name, quantity, total_price, pickup_date, status FROM accepted_orders ORDER BY pickup_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Accepted Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Enhanced Table Styling */
        .table {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            overflow: hidden;
            margin-left: 30px;
            
        }
        
        .table thead {
            background: linear-gradient(120deg, #4a90e2, #8e44ad);
            color: white;
            
        }
        
        .table thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(74, 144, 226, 0.05);
            transform: translateY(-3px);
        }
        
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }
        
        .table-container {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            margin-right: -15px;

        }
        
        /* Status Badge Styling */
        .badge {
            padding: 8px 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .badge-pending {
            background-color: #f39c12;
        }
        
        .badge-success {
            background-color: #2ecc71;
        }
        
        .badge-delivered {
            background-color: #3498db;
        }
        
        /* Animation classes */
        .fade-in-row {
            animation-duration: 0.4s;
        }
        
        /* Page title styling */
        .page-title {
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 10px;
            margin-left: 25px;
        }
        
        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, #4a90e2, #8e44ad);
        }
        
        /* Search and filter section */
        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-left: 30px;
            margin-top: -30px;
        }
        
        /* Empty state styling */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 50px;
            margin-bottom: 15px;
            color: #d1d1d1;
        }
    </style>
</head>
<body>

<div id="sidebar">
    <div class="text-center py-3">
        <img src="mypetstorelogo.png" alt="Logo" width="80" height="80">
        <h5 class="mt-2">Admin Panel</h5>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="admin.html"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        </li>
        <li class="nav-item">
                <a class="nav-link" href="viewproduct.html">
                    <i class="fas fa-box me-2"></i> Products
                </a>
            </li>
        <li class="nav-item">
                <a class="nav-link" href="recentorders.php">
                    <i class="fas fa-shopping-cart me-2"></i>Orders
                </a>
            </li>
        <li class="nav-item">
            <a class="nav-link" href="reports.html"><i class="fas fa-file-alt"></i> Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="managestaff.html"><i class="fas fa-user-tie me-2"></i> Manage Staff</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login.html"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h2 class="page-title animate__animated animate__fadeInDown">Recent Orders</h2>
    </div>
    
    <!-- Search and Filter Controls -->
    <div class="filter-section animate__animated animate__fadeIn"  >
        <div class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchOrders" class="form-control" placeholder="Search orders...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Processing">Decline</option>
                   
                   
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="dateFilter">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <div class="table-container animate__animated animate__fadeIn">
        <table class="table">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i> Tracking ID</th>
                    <th><i class="fas fa-box me-1"></i> Product Name</th>
                    <th><i class="fas fa-sort-amount-up me-1"></i> Quantity</th>
                    <th><i class="fas fa-tag me-1"></i> Total Price</th>
                    <th><i class="fas fa-calendar-alt me-1"></i> Pickup Date</th>
                    <th><i class="fas fa-info-circle me-1"></i> Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $i = 0; while ($row = $result->fetch_assoc()): ?>
                        <tr class="animate__animated animate__fadeInUp fade-in-row" style="animation-delay: <?= $i * 0.05 ?>s">
                            <td><?= htmlspecialchars($row['tracking_id']) ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($row['product_name']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td>
                                <span class="fw-bold">â‚±<?= htmlspecialchars(number_format($row['total_price'], 2)) ?></span>
                            </td>
                            <td>
                                <span class="text-muted"><i class="far fa-calendar me-1"></i> 
                                <?= date('M d, Y', strtotime($row['pickup_date'])) ?></span>
                            </td>
                            <td>
                                <?php
                                $statusClass = 'bg-success';
                                if (strtolower($row['status']) == 'pending') {
                                    $statusClass = 'badge-pending';
                                } else if (strtolower($row['status']) == 'delivered') {
                                    $statusClass = 'badge-delivered';
                                }
                                ?>
                                <span class="badge <?= $statusClass ?>">
                                    <i class="fas fa-check-circle me-1"></i> <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php $i++; endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-shopping-cart"></i>
                            <h4>No accepted orders found</h4>
                            <p>When customers place orders, they will appear here.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Controls -->
    <?php if ($result->num_rows > 10): ?>
    <nav aria-label="Page navigation" class="d-flex justify-content-center animate__animated animate__fadeIn" style="animation-delay: 0.4s">
        <ul class="pagination">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation when table rows are in viewport
    const observeElements = document.querySelectorAll('.fade-in-row');
    
    // Search functionality
    const searchInput = document.getElementById('searchOrders');
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if(text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        if(selectedStatus === '') {
            tableRows.forEach(row => row.style.display = '');
            return;
        }
        
        tableRows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(6)');
            if(statusCell && statusCell.textContent.toLowerCase().includes(selectedStatus)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Refresh button animation
    const refreshBtn = document.getElementById('refreshBtn');
    refreshBtn.addEventListener('click', function() {
        this.disabled = true;
        const icon = this.querySelector('i');
        icon.classList.add('fa-spin');
        
        setTimeout(() => {
            icon.classList.remove('fa-spin');
            this.disabled = false;
            
            // Add refresh animation to table
            const tableContainer = document.querySelector('.table-container');
            tableContainer.classList.remove('animate__fadeIn');
            void tableContainer.offsetWidth; // Trigger reflow
            tableContainer.classList.add('animate__fadeIn');
            
            // Reset filters
            searchInput.value = '';
            statusFilter.value = '';
            document.getElementById('dateFilter').value = '';
            
            // Show all rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => row.style.display = '');
        }, 800);
    });
});
</script>
</body>
</html>

<?php $conn->close(); ?>
