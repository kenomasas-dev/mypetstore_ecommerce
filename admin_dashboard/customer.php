<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Customers</title>
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
            margin-left: 20px;
            margin-top: -30px;
        }
        
        /* Gender badge styling */
        .badge-male {
            background-color: #3498db;
        }
        
        .badge-female {
            background-color: #e91e63;
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
        
        /* Animation classes */
        .fade-in-row {
            animation-duration: 0.4s;
        }
        
        /* Pagination styling */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .page-item.active .page-link {
            background: linear-gradient(120deg, #4a90e2, #8e44ad);
            border-color: #4a90e2;
        }
        
        .page-link {
            color: #4a90e2;
        }
    </style>
</head>
<body>
    <div id="sidebar">
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
                <a class="nav-link dropdown-toggle" href="orders.html" id="ordersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
            <h2 class="page-title animate__animated animate__fadeInDown">Registered Customers</h2>
        </div>
        
        <!-- Search and Filter Controls -->
        <div class="filter-section animate__animated animate__fadeIn">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchCustomers" class="form-control" placeholder="Search customers...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="genderFilter">
                        <option value="">All Genders</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
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
                       
                        <th><i class="fas fa-user me-1"></i> First Name</th>
                        <th><i class="fas fa-user me-1"></i> Last Name</th>
                        <th><i class="fas fa-envelope me-1"></i> Email</th>
                        <th><i class="fas fa-phone me-1"></i> Phone</th>
                        <th><i class="fas fa-birthday-cake me-1"></i> Birthday</th>
                        <th><i class="fas fa-venus-mars me-1"></i>Gender</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody">
                    <?php
                    // Enable error reporting
                    error_reporting(E_ALL);
                    ini_set('display_errors', 1);

                    // Database connection
                    $host = 'localhost';
                    $username = 'root';
                    $password = '';
                    $dbname = 'db';

                    $conn = new mysqli($host, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("<tr><td colspan='7' class='text-center'>Database connection failed</td></tr>");
                    }

                    // Pagination variables
                    $rowsPerPage = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $rowsPerPage;

                    // Get total number of customers
                    $totalQuery = "SELECT COUNT(*) as total FROM users";
                    $totalResult = $conn->query($totalQuery);
                    $totalRow = $totalResult->fetch_assoc();
                    $totalCustomers = $totalRow['total'];
                    $totalPages = ceil($totalCustomers / $rowsPerPage);

                    // Fetch customer data with pagination
                    $sql = "SELECT id, first_name, last_name, email, phone, dob, gender FROM users LIMIT $offset, $rowsPerPage";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            // Calculate age
                            $birthdate = new DateTime($row['dob']);
                            $today = new DateTime();
                            $age = $birthdate->diff($today)->y;

                            echo "<tr class='animate__animated animate__fadeInUp fade-in-row' style='animation-delay: " . ($i * 0.05) . "s'>";
                         
                            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                            echo "<td>" . date('M d, Y', strtotime($row['dob'])) . " <small class='text-muted'>($age yrs)</small></td>";
                            
                            $genderBadgeClass = strtolower($row['gender']) == 'male' ? 'badge-male' : 'badge-female';
                            echo "<td><span class='badge $genderBadgeClass'><i class='fas fa-" . 
                                 (strtolower($row['gender']) == 'male' ? 'mars' : 'venus') . 
                                 " me-1'></i>" . htmlspecialchars($row['gender']) . "</span></td>";
                            echo "</tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='7' class='empty-state'>";
                        echo "<i class='fas fa-users'></i>";
                        echo "<h4>No customers found</h4>";
                        echo "<p>When customers register, they will appear here.</p>";
                        echo "</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination-container animate__animated animate__fadeIn" style="animation-delay: 0.4s">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php 
                    // Show page numbers
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);
                    
                    if ($startPage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }
                    
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
                    }
                    
                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo "<li class='page-item'><a class='page-link' href='?page=$totalPages'>$totalPages</a></li>";
                    }
                    ?>
                    
                    <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchCustomers');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#customerTableBody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if(text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Gender filter
        const genderFilter = document.getElementById('genderFilter');
        genderFilter.addEventListener('change', function() {
            const selectedGender = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#customerTableBody tr');
            
            if(selectedGender === '') {
                tableRows.forEach(row => row.style.display = '');
                return;
            }
            
            tableRows.forEach(row => {
                const genderCell = row.querySelector('td:nth-child(7)');
                if(genderCell && genderCell.textContent.toLowerCase().includes(selectedGender)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        
        
        // Refresh button
        const refreshBtn = document.getElementById('refreshBtn');
        refreshBtn.addEventListener('click', function() {
            this.disabled = true;
            const icon = this.querySelector('i');
            icon.classList.add('fa-spin');
            
            setTimeout(() => {
                icon.classList.remove('fa-spin');
                this.disabled = false;
                
                // Reset filters
                searchInput.value = '';
                genderFilter.value = '';
                ageFilter.value = '';
                
                // Show all rows
                const tableRows = document.querySelectorAll('#customerTableBody tr');
                tableRows.forEach(row => row.style.display = '');
            }, 800);
        });
    });
    </script>
</body>
</html>
