<?php
// Start the session
session_start();

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

// Initialize search query
$search = "";
$whereClause = "";

// Check if search parameter exists
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $searchTerm = $conn->real_escape_string($search); // Prevent SQL injection
    $whereClause = " WHERE product_name LIKE '%$searchTerm%'";
}

// Fetch products from database
$sql = "SELECT id, product_name, category, price, stock, description, image_url FROM addproducts" . $whereClause;
$result = $conn->query($sql);

// Fetch cart items
$cartSql = "SELECT * FROM cart";
$cartResult = $conn->query($cartSql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa; /* Soft pastel blue */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            margin-top: 30px;
        }
        /* Sticky Header */
.header {
    position: fixed;
    top: 20px;
    left: 17px;
    width: 100%;
    background: #007bff;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    
}
        .header h2 {
            margin: 0;
        }
        .cart-button {
            position: relative;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
            border: none;
            background: none;
        }
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
            border-radius: 50%;
            padding: 4px 7px;
        }
        .card {
            width: 38rem;
            margin: 15px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card img {
            height: 200px;
            object-fit: cover;
        }
        .btn-icon {
            border: none;
            background: none;
            font-size: 1.5rem;
            margin: 5px;
            cursor: pointer;
        }
        .modal-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }
        

        .image-container {
            position: relative;
            overflow: hidden;
            height: 220px;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .image-container img {
            transform: scale(1.1);
        }

        .card-category {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .card-price {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }

        .btn-action {
            font-size: 14px;
            transition: all 0.3s ease-in-out;
            padding: 8px 16px;
            border-radius: 50px;
        }

        .btn-action:hover {
            transform: scale(1.05);
        }

        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
        }

        .btn-outline-success:hover {
            background: #28a745;
            color: white;
        }
        
        /* Search bar styling */
        .search-container {
            flex-grow: 1;
            max-width: 500px;
            margin: 0 15px;
        }
        
        .search-container .input-group {
            border-radius: 30px;
            overflow: hidden;
        }
        
        .search-container input {
            border-right: none;
        }
        
        .search-container .btn {
            border-left: none;
            background-color: white;
        }
        
        .search-container .btn:hover {
            background-color: #f8f9fa;
        }
        
        /* No results message */
        .no-results {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
        }

        #checkoutModal .modal-content {
    border: none;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
}

#checkoutModal .modal-header {
    border-bottom: 2px solid #ddd;
}

#checkoutModal .modal-footer {
    border-top: 2px solid #ddd;
}

#advertisementCarousel .carousel-item img {
        height: 520px; /* Adjust the height */
        object-fit: cover; /* Ensure image covers the area without distortion */
    }

    /* Make sure it's responsive */
    @media (max-width: 768px) {
        #advertisementCarousel .carousel-item img {
            height: 280px;
        }
    }

    .carousel-img {
        width: 100%;
        height: 400px; /* Adjust height as needed */
        object-fit: cover; /* Ensures image covers the area without distortion */
    }

    :root {
            --cart-primary: #6c757d;
            --cart-secondary: #f8f9fa;
            --cart-accent: #28a745;
        }

        .modal-cart {
            --bs-modal-bg: var(--cart-secondary);
        }

        .cart-table {
            transition: all 0.3s ease;
        }

        .cart-item {
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }

        .cart-item:hover {
            background-color: rgba(108, 117, 125, 0.05);
            transition: all 0.3s ease;
        }

        .quantity-control {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f1f3f5;
            border-radius: 20px;
            padding: 0.2rem 0.5rem;
        }

        .quantity-btn {
            background-color: transparent;
            border: none;
            color: var(--cart-primary);
            font-weight: bold;
            transition: color 0.2s ease;
        }

        .quantity-btn:hover {
            color: var(--cart-accent);
        }

        .item-checkbox {
            cursor: pointer;
            accent-color: var(--cart-accent);
        }

        .cart-empty {
            text-align: center;
            padding: 2rem;
            color: var(--cart-primary);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

       

        .modal-footer {
            background-color: var(--cart-secondary);
        }

        .total-summary {
            background-color: #f1f3f5;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-checkout {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            border: none;
            transition: transform 0.3s ease;
        }

        .btn-checkout:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #218838 0%, #28a745 100%);
        }

     /* Animate.css classes for modal animations */
     @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translate3d(0, -30px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    .animate__animated {
        animation-duration: 0.5s;
        animation-fill-mode: both;
    }

    .animate__fadeInDown {
        animation-name: fadeInDown;
    }

    .animate__pulse {
        animation-duration: 0.5s;
    }

    /* Input field transitions */
    .input-group-transition input {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .input-group-transition input:focus {
        border-left: 3px solid #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }

    /* Profile avatar hover effect */
    .profile-avatar-container:hover {
        transform: scale(1.05);
        cursor: pointer;
    }

    /* Save button animation */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .btn-primary:hover {
        animation: pulse 1s infinite;
    }

    /* Enable dropdown on hover */
.dropdown:hover .dropdown-menu {
    display: block;
    margin-top: 0; /* Align properly */
}

/* Carousel animations and styling */
.carousel-item-fade {
    opacity: 0;
    transition: opacity 1.2s ease-in-out;
}

.carousel-item-fade.active {
    opacity: 1;
}

.carousel-caption {
    max-width: 60%;
    transition: all 0.5s ease;
}

.carousel-control-prev-icon, .carousel-control-next-icon {
    transition: transform 0.3s ease;
}

.carousel-control-prev:hover .carousel-control-prev-icon, 
.carousel-control-next:hover .carousel-control-next-icon {
    transform: scale(1.2);
}

/* Animation classes */
.animate__animated {
    animation-duration: 1s;
    animation-fill-mode: both;
}

.animate__fadeInUp {
    animation-name: fadeInUp;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 30px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

/* Ensure the carousel is responsive */
@media (max-width: 768px) {
    .carousel-caption {
        max-width: 90%;
    }
}
footer {
    background: #343a40; /* Dark Gray */
}
footer a {
    text-decoration: none;
}
footer a:hover {
    text-decoration: underline;
}
/* Modal animation and design enhancements */
.modal.fade .modal-dialog {
        transform: scale(0.8);
        opacity: 0;
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    
    .modal.show .modal-dialog {
        transform: scale(1);
        opacity: 1;
    }
    
    .modal-content {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    
    .modal-header {
        padding: 15px 20px;
        border-bottom: none;
    }
    
    .product-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .product-image {
        transition: transform 0.5s ease;
    }
    
    .product-image:hover {
        transform: scale(1.05);
    }
    
    .product-title {
        font-size: 1.4rem;
        margin-bottom: 15px;
        color: #333;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 8px;
        animation: fadeInDown 0.5s ease forwards;
    }
    
    .product-details p {
        margin-bottom: 10px;
        animation: fadeInRight 0.5s ease forwards;
        animation-delay: calc(var(--animation-order) * 0.1s);
    }
    
    .category-badge {
        --animation-order: 1;
    }
    
    .price-tag {
        --animation-order: 2;
    }
    
    .stock-info {
        --animation-order: 3;
    }
    
    .description-box {
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 0.3s;
    }
    
    .description-title {
        font-size: 1.1rem;
        margin-bottom: 10px;
        color: #555;
    }
    
    .product-description {
        color: #666;
        line-height: 1.6;
    }
    
    .add-to-cart-btn {
        transition: all 0.3s ease;
    }
    
    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Animation keyframes */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

      /* Modal animation and styling */
      .modal.fade .modal-dialog {
        transform: translate(0, -50px);
        opacity: 0;
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    
    .modal.show .modal-dialog {
        transform: translate(0, 0);
        opacity: 1;
    }
    
    /* Modal content styling */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(to right, #4e73df, #224abe);
    }
    
    /* Avatar styling */
    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        animation: bounceIn 0.6s;
    }
    
    .avatar-circle i {
        font-size: 32px;
        color: #4e73df;
    }
    
    /* Text animations */
    .welcome-text {
        animation: fadeInDown 0.5s ease forwards;
        animation-delay: 0.2s;
        opacity: 0;
    }
    
    .subtitle-text {
        animation: fadeInDown 0.5s ease forwards;
        animation-delay: 0.3s;
        opacity: 0;
    }
    
    /* Form field animations */
    .form-field {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    .form-field:nth-child(1) {
        animation-delay: 0.4s;
    }
    
    .form-field:nth-child(2) {
        animation-delay: 0.5s;
    }
    
    .form-field:nth-child(3) {
        animation-delay: 0.6s;
    }
    
    /* Button animation */
    .login-button {
        background: linear-gradient(to right, #4e73df, #224abe);
        border: none;
        border-radius: 25px;
        font-weight: 500;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 0.7s;
        opacity: 0;
    }
    
    .login-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
    }
    
    /* Register link animation */
    .register-link {
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 0.8s;
        opacity: 0;
    }
    
    /* Input styling */
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .input-group-text {
        border-radius: 0.375rem 0 0 0.375rem;
    }
    
    .form-control {
        border-radius: 0 0.375rem 0.375rem 0;
    }
    
    /* Animation keyframes */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.1);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Animation for the modal entrance */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translate3d(0, -30px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    /* Animation for form elements */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    .animate__animated {
        animation-duration: 0.5s;
        animation-fill-mode: both;
    }

    .animate__fadeInDown {
        animation-name: fadeInDown;
    }

    .fade-in-up {
        animation-name: fadeInUp;
        animation-duration: 0.5s;
        animation-fill-mode: both;
    }

    /* Additional styles for improved appearance */
    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }

    .form-control, .form-select, .input-group-text {
        border-radius: 6px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Input field hover effect */
    .form-control:hover, .form-select:hover {
        border-color: #86b7fe;
    }

    /* Custom animation for the button */
    .btn-primary {
        position: relative;
        overflow: hidden;
    }

    .btn-primary:after {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }

    .btn-primary:hover:after {
        left: 100%;
    }

     /* Main container animation */
     @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .animate__animated {
        animation-duration: 0.8s;
        animation-fill-mode: both;
    }
    
    .animate__fadeIn {
        animation-name: fadeIn;
    }
    
    /* Button hover lift effect */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Pulse animation for button when not logged in */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    /* Icon hover effect */
    @keyframes icon-pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .pulse-on-hover:hover {
        animation: icon-pulse 0.5s ease;
    }
    
    /* Dropdown menu animation */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .slide-down {
        animation: slideDown 0.3s ease forwards;
        border-radius: 10px;
        overflow: hidden;
        padding: 0.5rem;
    }
    
    /* Individual dropdown items animation */
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .dropdown-item-animation {
        opacity: 0;
        animation: fadeInRight 0.3s ease forwards;
        animation-delay: var(--delay, 0s);
    }
    
    /* Enhanced button styling */
    #loginButtonContainer .btn {
        border-radius: 30px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        border: none;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    #loginButtonContainer .btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: 0.5s;
        z-index: -1;
    }
    
    #loginButtonContainer .btn:hover::after {
        left: 100%;
    }
    
    /* Enhanced dropdown styling */
    .dropdown-menu {
        min-width: 220px;
    }
    
    .dropdown-item {
        padding: 0.7rem 1rem;
        transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

      /* Logo container effect */
      .logo-container {
        position: relative;
        overflow: hidden;
    }
    
    /* Text gradient animation for "MyPet" */
    @keyframes textclip {
        0% { background-position: 0% 50%; }
        100% { background-position: 100% 50%; }
    }
    
    .animate-character {
        background-image: linear-gradient(
            -45deg, 
            #ff3d00 0%, 
            #ff8400 25%, 
            #ff0058 50%, 
            #ff3d00 75%, 
            #ff8400 100%
        );
        background-size: 200% auto;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: textclip 3s linear infinite;
        display: inline-block;
    }
    
    /* Bounce animation for "Store" */
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    
    .logo-bounce {
        display: inline-block;
        animation: bounce 2s ease infinite;
        animation-delay: 0.5s;
    }
    
    /* Shadow effect for the whole logo */
    .logo-text {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    /* Hover effect */
    .logo-text:hover {
        transform: scale(1.05);
        cursor: pointer;
    }
    
    /* Add a subtle pulsing glow around the logo on hover */
    .logo-text:hover::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 5px;
        animation: pulse 1.5s infinite;
        box-shadow: 0 0 10px rgba(255, 61, 0, 0.3);
        z-index: -1;
    }
    
    @keyframes pulse {
        0% { opacity: 0.3; }
        50% { opacity: 0.6; }
        100% { opacity: 0.3; }
    }

     /* Modal Animation */
     @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale3d(0.3, 0.3, 0.3);
        }
        50% {
            opacity: 1;
        }
    }
    
    .animate__animated {
        animation-duration: 0.5s;
        animation-fill-mode: both;
    }
    
    .animate__zoomIn {
        animation-name: zoomIn;
    }
    
    /* Fade-in animation */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .fade-in {
        animation: fadeIn 0.8s ease forwards;
    }
    
    /* Slide-up animation */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .slide-up {
        opacity: 0;
        animation: slideUp 0.6s ease forwards;
        animation-delay: 0.1s;
    }
    
    /* Button animation */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
        }
    }
    
    .pulse-on-hover:hover {
        animation: pulse 1.5s infinite;
    }
    
    /* Tracking icon animation */
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-8px); }
        60% { transform: translateY(-4px); }
    }
    
    .tracking-icon {
        animation: bounce 2s ease infinite;
    }
    
    /* Tracking illustration styling */
    .tracking-illustration {
        position: relative;
        height: 100px;
        margin: 0 auto;
        width: 80%;
    }
    
    .package-icon {
        position: absolute;
        right: 0;
        top: 0;
        animation: slide 3s ease-in-out infinite;
    }
    
    @keyframes slide {
        0% { transform: translateX(0); }
        50% { transform: translateX(-80%); }
        100% { transform: translateX(0); }
    }
    
    .tracking-path {
        position: absolute;
        bottom: 10px;
        left: 10%;
        right: 10%;
        display: flex;
        align-items: center;
    }
    
    .tracking-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #e9ecef;
        border: 2px solid #adb5bd;
    }
    
    .tracking-dot.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
        box-shadow: 0 0 0 5px rgba(13, 110, 253, 0.2);
    }
    
    .tracking-line {
        flex-grow: 1;
        height: 4px;
        background-color: #e9ecef;
    }
    
    /* Enhanced button styling */
    .btn-track {
        position: relative;
        overflow: hidden;
        padding: 0.6rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-track:hover {
        transform: translateY(-2px);
    }
    
    .ripple {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0) 60%);
        transform: scale(0);
        opacity: 0;
        pointer-events: none;
        animation: ripple 1s ease-out infinite;
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0);
            opacity: 0.5;
        }
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    /* Enhanced styling for modal */
    .modal-content {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(45deg, #0d6efd, #0a58ca);
    }
    
    .form-control-lg {
        border-radius: 8px;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .list-group-item {
        border: 1px solid rgba(0,0,0,0.125);
        transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .input-group-text {
        border-radius: 8px 0 0 8px;
    }

     /* Animation styles */
     .modal.fade .modal-dialog {
        transform: scale(0.8);
        opacity: 0;
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    
    .modal.show .modal-dialog {
        transform: scale(1);
        opacity: 1;
    }
    
    /* Enhanced styling */
    .modal-content {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .modal-header {
        padding: 1.25rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
    }
    
    .btn {
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .btn-primary {
        background: linear-gradient(to right, #4e73df, #3264c8);
        border: none;
    }
    
    .btn-secondary {
        background-color: #f8f9fa;
        color: #6c757d;
        border: 1px solid #ebebeb;
    }
    
    /* Placeholder animation */
    @keyframes placeholderShimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    
    .placeholder {
        background: linear-gradient(to right, #f6f7f8 8%, #edeef1 18%, #f6f7f8 33%);
        background-size: 1000px 100px;
        animation: placeholderShimmer 2s infinite linear;
        border-radius: 4px;
    }

    /* Enhanced Product Container */
    .product-container {
        padding: 20px 0;
    }
    
    /* Product Card Animation & Styling */
    .product-item {
        transition: transform 0.3s ease;
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    .product-item:nth-child(1) { animation-delay: 0.1s; }
    .product-item:nth-child(2) { animation-delay: 0.2s; }
    .product-item:nth-child(3) { animation-delay: 0.3s; }
    .product-item:nth-child(4) { animation-delay: 0.4s; }
    .product-item:nth-child(5) { animation-delay: 0.5s; }
    .product-item:nth-child(6) { animation-delay: 0.6s; }
    
    .product-item:hover {
        transform: translateY(-10px);
    }
    
    .product-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .product-card:hover {
        box-shadow: 0 12px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Image Container Styling */
    .image-container {
        height: 200px;
        position: relative;
        overflow: hidden;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    
    .product-image {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.1);
    }
    
    /* Card Content Styling */
    .card-title {
        margin-top: 10px;
        font-size: 1.2rem;
        color: #333;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .card-category, .card-price {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 8px 0;
    }
    
    .category-icon, .price-icon {
        margin-right: 6px;
        display: inline-block;
        transform-origin: center;
    }
    
    .price-icon {
        animation: pulse 2s infinite;
    }
    
    .card-price {
        color: #28a745;
        font-size: 1.1rem;
    }
    
    /* Button Styling */
    .btn-action {
        border-radius: 50px;
        padding: 8px 16px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .view-btn:hover {
        background-color: #007bff;
        color: white;
        transform: translateY(-3px);
    }
    
    .add-cart-btn {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
    }
    
    .add-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    
    /* No Results Styling */
    .no-results {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 40px 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        animation: fadeIn 0.8s ease forwards;
    }
    
    .search-icon {
        animation: bounce 2s infinite;
    }
    
    .return-btn {
        animation: pulse 2s infinite;
        border-radius: 30px;
        padding: 8px 25px;
        font-weight: 500;
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-15px);
        }
        60% {
            transform: translateY(-7px);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    </style>
</head>
<body>

   <!-- Header with Search Bar, Cart Button & Category Dropdown --> 
<div class="header d-flex justify-content-between align-items-center p-4 bg-warning shadow-sm" style="margin-left: -19px; margin-right: -19px; margin-top: -20px;">     
    <!-- Logo with Animation -->     
    <div class="d-flex align-items-center logo-container">
        <h3 class="m-0 fw-bold logo-text">
            <span class="text-danger animate-character">MyPet</span> 
            <span class="text-primary logo-bounce">Store</span>
        </h3>
    </div>


 <!-- Search Bar -->
<div class="search-container flex-grow-1 mx-3 position-relative">
    <form action="" method="GET" class="d-flex">
        <div class="input-group">
            <!-- Search Input -->
            <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search products..."
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" autocomplete="off">

            <!-- Clear Search (X) Button -->
            <button type="button" class="btn btn-light" id="clearSearch">
                <i class="fas fa-times text-dark"></i>
            </button>
        </div>
    </form>

    <!-- Search Suggestions Dropdown -->
    <ul id="searchSuggestions" class="dropdown-menu w-100" style="display: none;"></ul>
</div>


    
    <!-- Cart & Account Section -->
    <div class="d-flex align-items-center">
        
           <!-- Login/Register Button Container -->
<div id="loginButtonContainer" class="animate__animated animate__fadeIn">
    <?php if (isset($_SESSION['user_email'])): ?>
        <!-- Logged In State with Dropdown -->
        <div class="dropdown">
            <button class="btn btn-warning dropdown-toggle shadow-sm hover-lift" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle me-2 pulse-on-hover"></i><?php echo htmlspecialchars($_SESSION['user_email']); ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 slide-down" aria-labelledby="userDropdown">
                <li class="dropdown-item-animation" style="--delay: 0.1s">
                    <a class="dropdown-item rounded-3 my-1" href="#" id="profileBtn" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fas fa-user me-2 text-primary"></i>My Profile
                    </a>
                </li>
                <li class="dropdown-item-animation" style="--delay: 0.2s">
                    <a class="dropdown-item rounded-3 my-1" href="#" data-bs-toggle="modal" data-bs-target="#orderTrackingModal">
                        <i class="fas fa-shopping-bag me-2 text-success"></i>My Orders
                    </a>
                </li>
                <li><hr class="dropdown-divider dropdown-item-animation" style="--delay: 0.3s"></li>
                <li class="dropdown-item-animation" style="--delay: 0.4s">
                    <a class="dropdown-item rounded-3 my-1 text-danger" href="#" id="logoutBtn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    <?php else: ?>
        <!-- Login/Register Button -->
        <button class="btn btn-warning shadow-sm hover-lift pulse-animation" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="fas fa-user me-2"></i>Login/Register
        </button>
    <?php endif; ?>
</div>

        <!-- Cart Button -->
        <button class="btn btn- position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
            <i class="fas fa-shopping-cart text-dark"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">
                <?php echo $cartResult->num_rows; ?>
            </span>
        </button>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered animate__animated animate__fadeInDown">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header  bg-gradient text-dark">
                <h5 class="modal-title" id="profileModalLabel">
                    <i class="bi bi-person-circle me-2"></i>My Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Profile avatar section -->
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                       
                        <button class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0" style="transition: all 0.2s ease;" title="Change Photo">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                    </div>
                    <h4 class="mb-0" id="profileName">User Name</h4>
                    <p class="text-muted mb-3" id="profileEmailDisplay">user@example.com</p>
                </div>

                <form id="profileForm">
                    <div class="mb-3 input-group-transition">
                        <label for="profileEmail" class="form-label">
                            <i class="bi bi-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control bg-light" id="profileEmail" readonly>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 input-group-transition">
                                <label for="profileFirstName" class="form-label">
                                    <i class="bi bi-person me-2"></i>First Name
                                </label>
                                <input type="text" class="form-control" id="profileFirstName">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 input-group-transition">
                                <label for="profileLastName" class="form-label">
                                    <i class="bi bi-person me-2"></i>Last Name
                                </label>
                                <input type="text" class="form-control" id="profileLastName">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4 input-group-transition">
                        <label for="profilePassword" class="form-label">
                            <i class="bi bi-lock me-2"></i>New Password
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="profilePassword" placeholder="Leave blank to keep current password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Password must be at least 8 characters long</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 btn-lg animate__animated animate__pulse">
                            <i class="bi bi-check-circle me-2"></i>Save Changes
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Order Tracking Modal -->
<div class="modal fade" id="orderTrackingModal" tabindex="-1" aria-labelledby="orderTrackingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header  text-dark">
                <h5 class="modal-title" id="orderTrackingModalLabel">
                    <i class="fas fa-shipping-fast me-2 tracking-icon"></i>Track Your Order
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 fade-in">
                <div class="tracking-illustration mb-4 text-center">
                    <div class="package-icon">
                        <i class="fas fa-box fa-3x text-primary"></i>
                    </div>
                    <div class="tracking-path">
                        <div class="tracking-dot active"></div>
                        <div class="tracking-line"></div>
                        <div class="tracking-dot"></div>
                        <div class="tracking-line"></div>
                        <div class="tracking-dot"></div>
                    </div>
                </div>
                
                <div class="form-group mb-4 slide-up">
                    <label for="trackingId" class="form-label fw-bold">Enter Tracking ID:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control form-control-lg border-start-0" id="trackingId" placeholder="Enter your tracking ID" autocomplete="off">
                    </div>
                    <div class="form-text text-muted mt-2">
                        <i class="fas fa-info-circle me-1"></i> You can find your tracking ID in your order confirmation email.
                    </div>
                </div>
                
                <div class="recently-tracked slide-up" style="animation-delay: 0.2s;">
                    <h6 class="text-muted mb-3"><i class="fas fa-history me-2"></i>Recently Tracked Orders</h6>
                    <div class="list-group">                      
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-light pulse-on-hover" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-primary btn-track pulse-on-hover" id="trackOrderBtn">
                    <i class="fas fa-search me-2"></i>Track Order
                    <div class="ripple"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header  text-dark">
                <h5 class="modal-title" id="orderDetailsModalLabel">
                    <i class="bi bi-bag-check me-2"></i>Order Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetails">
                <!-- Order details will be displayed here -->
                <div class="placeholder-glow">
                    <div class="placeholder col-12 mb-3" style="height: 20px;"></div>
                    <div class="placeholder col-10 mb-3" style="height: 20px;"></div>
                    <div class="placeholder col-8 mb-3" style="height: 20px;"></div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="printOrderBtn">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Login Modal with Enhanced Design -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header  text-dark">
                <h5 class="modal-title" id="loginModalLabel">Welcome Back</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4 class="mt-3 welcome-text">Login to Your Account</h4>
                    <p class="text-muted subtitle-text">Enter your credentials to continue</p>
                </div>
                
                <form action="user_login.php" method="POST">
                    <div class="mb-4 form-field">
                        <label for="loginEmail" class="form-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-envelope text-muted"></i>
                            </span>
                            <input type="email" class="form-control border-start-0" id="loginEmail" name="email" placeholder="Your email" required>
                        </div>
                    </div>
                    <div class="mb-4 form-field">
                        <label for="loginPassword" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-start-0" id="loginPassword" name="password" placeholder="Your password" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-4 form-field">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <a href="#" class="text-decoration-none">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 login-button">Login</button>
                </form>
                
                <div class="text-center mt-4 register-link">
                    <p class="mb-0">Don't have an account? 
                        <a href="#" class="text-primary text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered animate__animated animate__fadeInDown">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header text-dark">
                <h5 class="modal-title" id="registerModalLabel">
                    <i class="bi bi-person-plus-fill me-2"></i>Create an Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="register_user.php" method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6 mb-3 fade-in-up" style="animation-delay: 0.1s;">
                            <label for="first_name" class="form-label">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" required>
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6 mb-3 fade-in-up" style="animation-delay: 0.2s;">
                            <label for="last_name" class="form-label">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Name -->
                    <div class="mb-3 fade-in-up" style="animation-delay: 0.3s;">
                        <label for="middle_name" class="form-label">Middle Name (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Enter your middle name">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3 fade-in-up" style="animation-delay: 0.4s;">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3 fade-in-up" style="animation-delay: 0.5s;">
                        <label for="phone" class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" pattern="^\d{10}$" required>
                        </div>
                        <div class="form-text">Enter a valid 10-digit phone number.</div>
                    </div>

                    <div class="row">
                        <!-- Date of Birth -->
                        <div class="col-md-6 mb-3 fade-in-up" style="animation-delay: 0.6s;">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control" id="dob" name="dob" required>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6 mb-3 fade-in-up" style="animation-delay: 0.7s;">
                            <label for="gender" class="form-label">Gender</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3 fade-in-up" style="animation-delay: 0.8s;">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3 fade-in-up" style="animation-delay: 0.9s;">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4 py-2 fade-in-up shadow-sm" style="animation-delay: 1s;">
                        <i class="bi bi-check-circle me-2"></i>Create Account
                    </button>
                </form>
                <div class="text-center mt-3 fade-in-up" style="animation-delay: 1.1s;">
                    Already have an account? 
                    <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal" class="text-primary fw-bold">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Image Slider (Advertising Banner) with Random Products -->
<div id="advertisementCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" style="margin-top: 60px;">
    <div class="carousel-indicators" id="carouselIndicators">
        <!-- Indicators will be added dynamically -->
    </div>
    
    <div class="carousel-inner rounded-4 shadow-lg" id="carouselInner">
        <!-- Carousel items will be added dynamically -->
        <div class="carousel-item active">
            <div class="position-relative">
                <div class="d-flex justify-content-center align-items-center bg-light" style="height: 200px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#advertisementCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark bg-opacity-25 rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#advertisementCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark bg-opacity-25 rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>


<div class="container py-5">
    <!-- Display search results message if search was performed -->
    <?php if (!empty($search)): ?>
        <div class="alert alert-info mb-4">
            <i class="fas fa-search me-2"></i> 
            Showing results for: <strong><?php echo htmlspecialchars($search); ?></strong>
            <a href="view_products.php" class="btn btn-sm btn-outline-primary float-end">Clear Search</a>
        </div>
    <?php endif; ?>
   
    <div class="row justify-content-center product-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-4 d-flex align-items-stretch mb-4 product-item">
                <div class="card product-card border-0 shadow-sm h-100">
                    <div class="image-container overflow-hidden">
                        <img src="<?= htmlspecialchars($row["image_url"], ENT_QUOTES, 'UTF-8') ?>" class="card-img-top product-image" alt="Product Image">
                        <div class="product-overlay">
                            <span class="badge bg-primary position-absolute top-0 end-0 m-2 px-2 py-1">
                                New
                            </span>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($row["product_name"], ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="card-category"><span class="category-icon">ðŸ“Œ</span> <?= htmlspecialchars($row["category"], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="card-price fw-bold"><span class="price-icon">ðŸ’°</span> â‚±<?= number_format($row["price"], 2) ?></p>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <button class="btn btn-outline-primary btn-action view-btn me-2" onclick='viewProduct(<?= json_encode($row) ?>)'>
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-success btn-action add-cart-btn" onclick='openCartModal(<?= json_encode($row) ?>)'>
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        // No products found message
        ?>
        <div class="col-12">
            <div class="no-results py-5 my-4 text-center">
                <i class="fas fa-search fa-3x text-muted mb-3 search-icon"></i>
                <h4 class="fw-bold">No products found</h4>
                
                <?php if (!empty($search)) { ?>
                    <p class="text-muted">We couldn't find any products matching "<?= htmlspecialchars($search) ?>"</p>
                    <a href="shop.php" class="btn btn-primary mt-2 return-btn">View All Products</a>
                <?php } else { ?>
                    <p class="text-muted">No products are available at this time.</p>
                <?php } ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<!-- Footer Section -->
<footer class="bg-warning text-dark text-center py-3 mt-5" style="margin-left: -113px; margin-right: -110px; margin-bottom: -70px;">
    <div class="container">
        <!-- Pet Store Name in Center -->
        <div class="d-flex justify-content-center align-items-center mb-3">
            <h3 class="m-0 text-danger fw-bold">MyPet <span class="text-primary">Store</span></h3>
        </div>

        <div class="row">
            <!-- Company Info -->
            <div class="col-md-4">
                <h5>ðŸ¾ PetShop Online</h5>
                <p>Bringing joy to pets & their owners! ðŸ¶ðŸ±ðŸ¦</p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-dark">Home</a></li>
                    <li><a href="view_products.php" class="text-dark">Products</a></li>
                    <li><a href="about.php" class="text-dark">About Us</a></li>
                    <li><a href="contact.php" class="text-dark">Contact</a></li>
                </ul>
            </div>

            <!-- Social Media -->
            <div class="col-md-4">
        <h5>Follow Us</h5>
        <a href="https://www.facebook.com/mypetstore.valencia" class="text-dark me-3">
        <i class="fab fa-facebook fa-2x"></i> <!-- Adjust size with fa-2x -->
        </a>
        </div>

        </div>
        
        <!-- Copyright -->
        <div class="mt-3">
            <p class="mb-0">&copy; <?php echo date("Y"); ?> PetShop Online. All rights reserved.</p>
        </div>
    </div>
</footer>


    <!-- Add to Cart Modal -->
    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addToCartModalLabel">Add to Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="cartProductImage" class="modal-img" src="" alt="Product Image">
                    <p><strong>Product:</strong> <span id="cartProductName"></span></p>
                    <p><strong>Price per Unit:</strong> â‚±<span id="cartProductPrice"></span></p>
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" class="form-control" min="1" value="1" oninput="updateTotalPrice()">
                    <p class="text-danger" id="stockWarning" style="display: none;">Not enough stock available.</p>
                    <p><strong>Total Price:</strong> â‚±<span id="totalPrice"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="confirmAddToCart()">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

   <!-- Cart Modal -->
   <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-cart">
            <div class="modal-content shadow-lg rounded-3 animate__animated animate__fadeIn">
                <div class="modal-header  ">
                    <h5 class="modal-title " id="cartModalLabel">ðŸ›’ Shopping Cart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table cart-table text-center align-middle">
                            <thead class="table-warning">
                                <tr>
                                    <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($cartResult->num_rows > 0) {
                                    while ($row = $cartResult->fetch_assoc()) {
                                        echo "<tr class='cart-item'>";
                                        echo "<td><input type='checkbox' class='form-check-input item-checkbox' data-price='" . $row["total_price"] . "' data-quantity='" . $row["quantity"] . "'></td>";
                                        echo "<td>" . htmlspecialchars($row["product_name"], ENT_QUOTES, 'UTF-8') . "</td>";
                                        echo "<td>â‚±" . number_format($row["price"], 2) . "</td>";
                                        echo "<td>
                                            <div class='quantity-control'>
                                                <button class='quantity-btn quantity-decrease'>âˆ’</button>
                                                <span class='mx-2 quantity-value'>" . $row["quantity"] . "</span>
                                                <button class='quantity-btn quantity-increase'>+</button>
                                            </div>
                                        </td>";
                                        echo "<td class='item-total'>â‚±" . number_format($row["total_price"], 2) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center text-muted'>ðŸ›’ Cart is empty</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="total-summary">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Total Quantity</h6>
                                <h4 id="selectedQuantity" class="text-primary">0</h4>
                            </div>
                            <div class="text-end">
                                <h6 class="text-muted mb-1">Total Price</h6>
                                <h4 id="selectedTotal" class="text-success">â‚±0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-checkout text-white" onclick="prepareCheckout()">Proceed to Checkout</button>
                </div>
            </div>
        </div>
    </div>

   <!-- Product Modal with Enhanced Design and Animation -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header  text-dark">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-image-container">
                            <img id="modalImage" src="" class="img-fluid rounded mb-3 product-image" alt="Product Image">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 id="modalName" class="fw-bold product-title"></h4>
                        <div class="product-details">
                            <p class="category-badge"><span class="badge bg-secondary" id="modalCategory"></span></p>
                            <p class="price-tag"><strong>Price:</strong> â‚±<span id="modalPrice" class="text-primary fw-bold"></span></p>
                            <p class="stock-info"><strong>Stock:</strong> <span id="modalStock"></span> units</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="description-box p-3 bg-light rounded">
                            <h5 class="description-title">Description</h5>
                            <p id="modalDescription" class="product-description"></p>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="checkoutModalLabel">âœ… Checkout</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="fw-bold">Tracking ID:</h5>
                        <p id="trackingId" class="text-muted">Generating...</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold">Order Pickup Details:</h5>
                        <form id="customerForm">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" value="<?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" value="<?php echo isset($_SESSION['last_name']) ? $_SESSION['last_name'] : ''; ?>" readonly>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <h5 class="fw-bold">Order Summary:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody id="checkoutTableBody">
                            <!-- Rows will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h5 class="fw-bold">Total Quantity: <span id="checkoutTotalQuantity" class="text-success">0</span></h5>
                    <h5 class="fw-bold">Total Price: <span id="checkoutTotalPrice" class="text-success">â‚±0.00</span></h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="placeOrder()">Place Order</button>
            </div>
        </div>
    </div>
</div>



 <!-- Bootstrap JS and Popper -->
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('user_login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button to show user email
                const loginContainer = document.getElementById('loginButtonContainer');
                loginContainer.innerHTML = `
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2"></i>${data.user_email}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php">
                                <i class="fas fa-user me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="orders.php">
                                <i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
                            <li><a class="dropdown-item" href="wishlist.php">
                                <i class="fas fa-heart me-2"></i>Wishlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" id="logoutBtn">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                `;
                $('#loginModal').modal('hide');

                // Add logout event listener
                document.getElementById('logoutBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    fetch('logout.php', {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Revert to login button
                            const loginContainer = document.getElementById('loginButtonContainer');
                            loginContainer.innerHTML = `
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    Login / Register
                                </button>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Logout error:', error);
                        alert('An error occurred during logout');
                    });
                });
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred during login');
        });
    });
    </script>

<script>
function prepareCheckout() {
    let selectedItems = [];
    document.querySelectorAll('.item-checkbox:checked').forEach((checkbox) => {
        selectedItems.push(checkbox.dataset.id);
    });

    if (selectedItems.length === 0) {
        alert("Please select items to checkout.");
        return;
    }

    fetch('checkout.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ items: selectedItems })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Checkout successful!");
            window.location.reload(); // Reload the page to update the cart
        } else {
            alert("Error during checkout.");
        }
    });
}
</script>


<script>
// Function to generate a random tracking ID
function generateTrackingId() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let trackingId = '';
    for (let i = 0; i < 10; i++) {
        trackingId += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return trackingId;
}

// Function to prepare the checkout modal
function prepareCheckout() {
    // Generate a tracking ID
    const trackingId = generateTrackingId();
    document.getElementById("trackingId").textContent = trackingId;

    // Get selected items from the cart
    const selectedItems = [];
    let totalQuantity = 0;
    let totalPrice = 0;

    document.querySelectorAll(".item-checkbox:checked").forEach(checkbox => {
        const row = checkbox.closest("tr");
        const productName = row.querySelector("td:nth-child(2)").textContent;
        const quantity = parseInt(row.querySelector(".quantity-value").textContent);
        const price = parseFloat(row.querySelector("td:nth-child(3)").textContent.replace('â‚±', ''));
        const total = parseFloat(row.querySelector(".item-total").textContent.replace('â‚±', ''));

        selectedItems.push({ productName, quantity, price, total });
        totalQuantity += quantity;
        totalPrice += total;
    });

    // Populate the checkout table
    const tableBody = document.getElementById("checkoutTableBody");
    tableBody.innerHTML = ""; // Clear previous content

    selectedItems.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.productName}</td>
            <td>${item.quantity}</td>
            <td>â‚±${item.total.toFixed(2)}</td>
        `;
        tableBody.appendChild(row);
    });

    // Update totals
    document.getElementById("checkoutTotalQuantity").textContent = totalQuantity;
    document.getElementById("checkoutTotalPrice").textContent = `â‚±${totalPrice.toFixed(2)}`;

    // Show the checkout modal
    const checkoutModal = new bootstrap.Modal(document.getElementById("checkoutModal"));
    checkoutModal.show();
}

// Function to handle placing the order
function placeOrder() {
    const firstName = document.getElementById("firstName").value;
    const lastName = document.getElementById("lastName").value;
    const trackingId = document.getElementById("trackingId").textContent;

    if (!firstName || !lastName) {
        alert("Please Login First.");
        return;
    }

    // Prepare order data
    const orderData = {
        tracking_id: trackingId,
        first_name: firstName,
        last_name: lastName,
        product_name: document.querySelector(".item-checkbox:checked").closest("tr").querySelector("td:nth-child(2)").textContent,
        quantity: parseInt(document.querySelector(".item-checkbox:checked").closest("tr").querySelector(".quantity-value").textContent),
        price: parseFloat(document.querySelector(".item-checkbox:checked").closest("tr").querySelector("td:nth-child(3)").textContent.replace('â‚±', '')),
        total_price: parseFloat(document.querySelector(".item-checkbox:checked").closest("tr").querySelector(".item-total").textContent.replace('â‚±', ''))
    };

    // Send order data to the server
    fetch("place_order.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert(data.message); // Show success message
            const checkoutModal = bootstrap.Modal.getInstance(document.getElementById("checkoutModal"));
            checkoutModal.hide();
            window.location.href = "shop.php"; // Redirect to products page
        } else {
            alert(data.message); // Show error message
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while placing the order.");
    });
}

fetch("place_order.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify(orderData) // Ensure orderData is a valid object
})
</script>

    <!-- JavaScript to Calculate Total Quantity & Price -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll(".item-checkbox");
    const totalDisplay = document.getElementById("selectedTotal");
    const quantityDisplay = document.getElementById("selectedQuantity");
    const selectAll = document.getElementById("selectAll");

    function updateTotal() {
        let total = 0;
        let totalQuantity = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                total += parseFloat(checkbox.dataset.price);
                totalQuantity += parseInt(checkbox.dataset.quantity);
            }
        });

        totalDisplay.textContent = "â‚±" + total.toFixed(2);
        quantityDisplay.textContent = totalQuantity;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateTotal);
    });

    // Select all checkboxes
    selectAll.addEventListener("change", function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        updateTotal();
    });

    // Quantity increase and decrease buttons
    document.querySelectorAll(".quantity-increase").forEach(button => {
        button.addEventListener("click", function() {
            let row = this.closest("tr");
            let quantityElement = row.querySelector(".quantity-value");
            let totalElement = row.querySelector(".item-total");
            let checkbox = row.querySelector(".item-checkbox");

            let quantity = parseInt(quantityElement.textContent) + 1;
            let price = parseFloat(row.querySelector(".item-checkbox").dataset.price) / parseInt(row.querySelector(".item-checkbox").dataset.quantity);

            quantityElement.textContent = quantity;
            totalElement.textContent = "â‚±" + (quantity * price).toFixed(2);

            checkbox.dataset.quantity = quantity;
            checkbox.dataset.price = quantity * price;

            updateTotal();
        });
    });

    document.querySelectorAll(".quantity-decrease").forEach(button => {
        button.addEventListener("click", function() {
            let row = this.closest("tr");
            let quantityElement = row.querySelector(".quantity-value");
            let totalElement = row.querySelector(".item-total");
            let checkbox = row.querySelector(".item-checkbox");

            let quantity = parseInt(quantityElement.textContent);
            let price = parseFloat(row.querySelector(".item-checkbox").dataset.price) / parseInt(row.querySelector(".item-checkbox").dataset.quantity);

            if (quantity > 1) {
                quantity--;
                quantityElement.textContent = quantity;
                totalElement.textContent = "â‚±" + (quantity * price).toFixed(2);

                checkbox.dataset.quantity = quantity;
                checkbox.dataset.price = quantity * price;

                updateTotal();
            }
        });
    });

    // Remove item from cart
    document.querySelectorAll(".delete-item").forEach(button => {
        button.addEventListener("click", function() {
            let row = this.closest("tr");
            row.remove();
            updateTotal();
        });
    });
});
</script>

    <script>
        function viewProduct(product) {
            document.getElementById("modalImage").src = product.image_url;
            document.getElementById("modalName").textContent = product.product_name;
            document.getElementById("modalCategory").textContent = product.category;
            document.getElementById("modalPrice").textContent = product.price;
            document.getElementById("modalStock").textContent = product.stock;
            document.getElementById("modalDescription").textContent = product.description;
            var productModal = new bootstrap.Modal(document.getElementById("productModal"));
            productModal.show();
        }

        let selectedProduct = {};
        let cartCount = 0;

        function openCartModal(product) {
            selectedProduct = product;
            document.getElementById("cartProductImage").src = product.image_url;
            document.getElementById("cartProductName").textContent = product.product_name;
            document.getElementById("cartProductPrice").textContent = product.price;
            document.getElementById("quantity").value = 1;
            document.getElementById("quantity").max = product.stock;
            document.getElementById("stockWarning").style.display = "none";
            document.getElementById("totalPrice").textContent = product.price;
            var cartModal = new bootstrap.Modal(document.getElementById("addToCartModal"));
            cartModal.show();
        }

        function updateTotalPrice() {
            let quantity = parseInt(document.getElementById("quantity").value);
            let totalPrice = quantity * selectedProduct.price;
            document.getElementById("totalPrice").textContent = totalPrice.toFixed(2);
        }

        function confirmAddToCart() {
            let quantity = parseInt(document.getElementById("quantity").value);

            if (quantity > selectedProduct.stock) {
                document.getElementById("stockWarning").style.display = "block";
                return;
            }

            let formData = new FormData();
            formData.append("product_id", selectedProduct.id);
            formData.append("product_name", selectedProduct.product_name);
            formData.append("price", selectedProduct.price);
            formData.append("quantity", quantity);
            formData.append("total_price", (quantity * selectedProduct.price).toFixed(2));

            fetch("add_to_cart.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);  // Show success message
                window.location.reload(); // Reload page to update cart
            })
            .catch(error => console.error("Error:", error));
        }
    </script>

<script>
    // Ensure dropdown works after page load
    document.addEventListener('DOMContentLoaded', function() {
        // Get the dropdown toggle button
        const dropdownToggle = document.getElementById('userDropdown');
        
        // Add click event listener if the button exists
        if (dropdownToggle) {
            dropdownToggle.addEventListener('click', function(e) {
                // Prevent default to ensure dropdown behavior
                e.preventDefault();
                
                // Toggle dropdown manually
                this.setAttribute('aria-expanded', 
                    this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'
                );
                
                // Toggle dropdown menu visibility
                const dropdownMenu = this.nextElementSibling;
                dropdownMenu.classList.toggle('show');
            });
        }
    });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logout button functionality
        const logoutBtn = document.getElementById('logoutBtn');
        
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                fetch('logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to reset the login container
                        window.location.reload();
                    } else {
                        alert('Logout failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Logout error:', error);
                    alert('An error occurred during logout');
                });
            });
        }
    });
    </script>

  <!-- JavaScript for Handling Modal Actions -->
<script>
    document.getElementById("trackOrderBtn").addEventListener("click", function() {
        let trackingId = document.getElementById("trackingId").value;
        if (trackingId.trim() === "") {
            alert("Please enter a tracking ID.");
        } else {
            // Simulate tracking process (you can replace this with an AJAX call)
            alert("Tracking order with ID: " + trackingId);
        }
    });
</script>
    
<script>
    document.getElementById('trackOrderBtn').addEventListener('click', function() {
    let trackingId = document.getElementById('trackingId').value;

    if (trackingId.trim() === "") {
        alert("Please enter a tracking ID.");
        return;
    }

    fetch('order_tracking.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `tracking_id=${trackingId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            document.getElementById('orderDetails').innerHTML = `
                <p><strong>Tracking ID:</strong> ${data.data.tracking_id}</p>
                <p><strong>Product Name:</strong> ${data.data.product_name}</p>
                <p><strong>Quantity:</strong> ${data.data.quantity}</p>
                <p><strong>Total Price:</strong> ${data.data.total_price}</p>
                <p><strong>Pickup Date:</strong> ${data.data.pickup_date}</p>
                <p><strong>Status:</strong> ${data.data.status}</p>
            `;
            new bootstrap.Modal(document.getElementById('orderDetailsModal')).show();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>

<script>
    document.getElementById('printOrderBtn').addEventListener('click', function () {
        let orderDetails = document.getElementById('orderDetails').innerHTML;
        let printWindow = window.open('', '', 'width=400,height=400');
        printWindow.document.write('<html><head><title>Print Order Details</title></head><body>');
        printWindow.document.write('<h2>Order Details</h2>');
        printWindow.document.write(orderDetails);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.getElementById('profileBtn');
    const profileForm = document.getElementById('profileForm');

    // Load user profile when modal opens
    profileBtn.addEventListener('click', function () {
        fetch('get_profile.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profileEmail').value = data.email;
                    document.getElementById('profileFirstName').value = data.first_name;
                    document.getElementById('profileLastName').value = data.last_name;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error fetching profile:', error));
    });

    // Handle form submission for updating profile
    profileForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData();
        formData.append('email', document.getElementById('profileEmail').value);
        formData.append('first_name', document.getElementById('profileFirstName').value);
        formData.append('last_name', document.getElementById('profileLastName').value);
        formData.append('password', document.getElementById('profilePassword').value); // Can be empty

        fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response from server:", data); // Debugging
            if (data.success) {
                alert('Profile updated successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error updating profile:', error));
    });
});

</script>

<script>
    // Initialize the modal with animations
    document.addEventListener('DOMContentLoaded', function() {
        // Populate profile name when modal opens
        const profileModal = document.getElementById('profileModal');
        profileModal.addEventListener('show.bs.modal', function() {
            // Populate user data from session
            document.getElementById('profileEmail').value = "<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>";
            document.getElementById('profileEmailDisplay').textContent = "<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>";
            document.getElementById('profileFirstName').value = "<?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''; ?>";
            document.getElementById('profileLastName').value = "<?php echo isset($_SESSION['last_name']) ? $_SESSION['last_name'] : ''; ?>";
            
            const firstName = "<?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''; ?>";
            const lastName = "<?php echo isset($_SESSION['last_name']) ? $_SESSION['last_name'] : ''; ?>";
            document.getElementById('profileName').textContent = firstName + " " + lastName;
        });
        
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('profilePassword');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
        
        // Form submit animation
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const button = this.querySelector('button[type="submit"]');
            button.classList.add('animate__pulse');
            
            // Simulate saving (replace with your actual save logic)
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
            
            setTimeout(() => {
                button.innerHTML = '<i class="bi bi-check-circle me-2"></i>Changes Saved!';
                button.classList.remove('btn-primary');
                button.classList.add('btn-success');
                
                // Reset after some time
                setTimeout(() => {
                    button.innerHTML = '<i class="bi bi-check-circle me-2"></i>Save Changes';
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                }, 2000);
            }, 1500);
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    let dropdown = document.querySelector(".dropdown");

    dropdown.addEventListener("mouseenter", function () {
        let menu = this.querySelector(".dropdown-menu");
        menu.classList.add("show");
    });

    dropdown.addEventListener("mouseleave", function () {
        let menu = this.querySelector(".dropdown-menu");
        menu.classList.remove("show");
    });
});

</script>

<script>
    document.getElementById('clearSearch').addEventListener('click', function() {
        document.getElementById('searchInput').value = ''; // Clear input field
        window.location.href = '<?php echo strtok($_SERVER["REQUEST_URI"], "?"); ?>'; // Reload page without search query
    });
</script>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let query = this.value;

    if (query.length > 0) {
        fetch('search_suggestions.php?query=' + query)
            .then(response => response.json())
            .then(data => {
                let suggestions = document.getElementById('searchSuggestions');
                suggestions.innerHTML = '';
                suggestions.style.display = 'block';

                data.forEach(item => {
                    let listItem = document.createElement('li');
                    listItem.classList.add('dropdown-item');
                    listItem.textContent = item;
                    listItem.addEventListener('click', function() {
                        document.getElementById('searchInput').value = item;
                        suggestions.style.display = 'none';
                    });
                    suggestions.appendChild(listItem);
                });
            });
    } else {
        document.getElementById('searchSuggestions').style.display = 'none';
    }
});

// Clear search button
document.getElementById('clearSearch').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchSuggestions').style.display = 'none';
});
</script>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let query = this.value.trim();

    if (query.length > 0) {
        fetch('search_suggestions.php?query=' + query)
            .then(response => response.json())
            .then(data => {
                let suggestions = document.getElementById('searchSuggestions');
                suggestions.innerHTML = '';
                suggestions.style.display = 'block';

                if (data.length === 0) {
                    suggestions.innerHTML = '<li class="dropdown-item text-muted">No results found</li>';
                }

                data.forEach(item => {
                    let listItem = document.createElement('li');
                    listItem.classList.add('dropdown-item');
                    listItem.textContent = item;
                    listItem.addEventListener('click', function() {
                        document.getElementById('searchInput').value = item;
                        suggestions.style.display = 'none';
                        window.location.href = 'shop.php?search=' + encodeURIComponent(item);
                    });
                    suggestions.appendChild(listItem);
                });
            });
    } else {
        document.getElementById('searchSuggestions').style.display = 'none';
    }
});

// Clear search button
document.getElementById('clearSearch').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchSuggestions').style.display = 'none';
});
</script>

<script>
// Load random advertisements when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Fetch random products from the server
    fetch('fetch_random_ads.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateCarousel(data.products);
            } else {
                console.error('Error fetching products:', data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
    
    // Function to populate the carousel with products
    function populateCarousel(products) {
        const indicatorsContainer = document.getElementById('carouselIndicators');
        const carouselInner = document.getElementById('carouselInner');
        
        // Clear loading state
        carouselInner.innerHTML = '';
        indicatorsContainer.innerHTML = '';
        
        // Add indicators
        products.forEach((product, index) => {
            const activeClass = index === 0 ? 'active' : '';
            indicatorsContainer.innerHTML += `
                <button type="button" data-bs-target="#advertisementCarousel" 
                        data-bs-slide-to="${index}" class="${activeClass}" 
                        aria-current="${index === 0 ? 'true' : 'false'}" 
                        aria-label="Slide ${index + 1}"></button>
            `;
        });
        
        // Add carousel items
        products.forEach((product, index) => {
            const activeClass = index === 0 ? 'active' : '';
            carouselInner.innerHTML += `
                <div class="carousel-item ${activeClass} carousel-item-fade">
                    <div class="position-relative">
                        <img src="${product.image_url}" class="carousel-img d-block w-100" 
                             alt="${product.product_name}" 
                             style="height: 400px; object-fit: cover; filter: brightness(0.85);">
                        <div class="carousel-caption d-none d-md-block text-start bg-dark bg-opacity-50 p-3 rounded-3 animate__animated animate__fadeInUp">
                            <span class="badge bg-primary mb-2">${product.category}</span>
                            <h3 class="fw-bold">${product.product_name}</h3>
                            <p class="fs-5 text-warning">â‚±${product.price}</p>
                            
                        </div>
                    </div>
                </div>
            `;
        });
        
        // Initialize Bootstrap carousel
        const carousel = document.getElementById('advertisementCarousel');
        const carouselInstance = new bootstrap.Carousel(carousel, {
            interval: 5000
        });
    }
    
    // Function to handle adding product to cart
    window.addToCart = function(productId) {
        // You can implement your cart functionality here
        console.log("Adding product ID: " + productId + " to cart");
        
        // Example implementation - replace with your actual cart logic
        fetch('cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Product added to cart!');
                // Update cart count indicator if you have one
            } else {
                alert('Failed to add product to cart: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };
    
    // Add animation when switching slides
    const carousel = document.getElementById('advertisementCarousel');
    carousel.addEventListener('slide.bs.carousel', function () {
        const captions = document.querySelectorAll('.carousel-caption');
        captions.forEach(caption => {
            caption.classList.remove('animate__fadeInUp');
            setTimeout(() => {
                caption.classList.add('animate__fadeInUp');
            }, 50);
        });
    });
});
</script>

<script>
    
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
