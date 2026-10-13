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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'];
    $productCategory = $_POST['productCategory'];
    $productPrice = $_POST['productPrice'];
    $productStock = $_POST['productStock'];
    $productDescription = $_POST['productDescription'];

    // Handle file upload
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        $targetDir = "uploads/"; // Directory to store uploaded images
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // Create directory if it doesn't exist
        }

        $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["productImage"]["tmp_name"]);
        if ($check !== false) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                $productImage = $targetFile; // Save the file path
            } else {
                die("Error uploading file.");
            }
        } else {
            die("File is not an image.");
        }
    } else {
        die("No image uploaded.");
    }

    // Insert product into the database
    $sql = "INSERT INTO addproducts (product_name, category, price, stock, description, image_url)
            VALUES ('$productName', '$productCategory', '$productPrice', '$productStock', '$productDescription', '$productImage')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Product added successfully!');
                window.location.href = 'staffviewproduct.html'; // Redirect back to the form
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $conn->error . "');
              </script>";
    }
}

// Close the database connection
$conn->close();
?>

