<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all dog-related products (where product name contains 'dog')
$sql = "SELECT * FROM addproducts WHERE product_name LIKE '%bird%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bird Products</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Include Bootstrap CSS -->
    <style>
        /* Custom styles for square card layout */
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 30px;
            height: 350px; /* Fixed height to make the card square */
            width: 350px; /* Fixed width to match height */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            margin: 0 auto; /* Center cards horizontally */
        }

        .product-card:hover {
            transform: translateY(-10px);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card-body {
            padding: 15px;
            text-align: center;
            height: 130px; /* Adjust height for body content */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-card-body h3 {
            font-size: 1.25rem;
            color: #333;
        }

        .product-card-body p {
            font-size: 1rem;
            color: #666;
        }

        .product-card-body .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #28a745;
        }

        .product-card-body .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            text-align: center;
        }

        .product-card-body .btn:hover {
            background-color: #0056b3;
        }

        /* Add to cart button */
        .add-to-cart-btn {
            background-color: #f0ad4e;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            padding: 10px 20px;
            margin-top: 10px;
        }

        .add-to-cart-btn:hover {
            background-color: #ec971f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4"></h2>
        <div class="row">
            <?php
            // Check if there are products
            if ($result->num_rows > 0) {
                // Output each product in a card layout
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 d-flex justify-content-center">';
                    echo '<div class="product-card">';
                    echo '<img src="' . $row['image_url'] . '" alt="' . $row['product_name'] . '">';
                    echo '<div class="product-card-body">';
                    echo '<h3>' . $row['product_name'] . '</h3>';
                    echo '<p>' . $row['description'] . '</p>';
                    echo '<p class="price">$' . $row['price'] . '</p>';
                    echo '<a href="product_details.php?id=' . $row['id'] . '" class="btn">View Details</a>';
                    echo '<a href="add_to_cart.php?id=' . $row['id'] . '" class="add-to-cart-btn">Add to Cart</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No Birds products found</p>";
            }
            ?>
        </div>
    </div>

    <script src="path/to/bootstrap.bundle.js"></script> <!-- Include Bootstrap JS -->
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

