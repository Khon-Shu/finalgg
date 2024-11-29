<?php
session_start();
include('../connect.php');  // Database connection

// Ensure all necessary details are available
if (isset($_POST['pay']) && isset($_SESSION['product_id']) && isset($_SESSION['quantity']) && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];  // Assuming this is retrieved when the user logs in
    $productId = $_SESSION['product_id'];
    $quantity = $_SESSION['quantity'];
    $status = 'Pending';
    $orderDate = date('Y-m-d H:i:s');

    // Check if the user has shipping information in the shipping_info table
    $checkShippingSql = "SELECT user_id FROM shipping_info WHERE user_id = '$userId'";
    $shippingResult = $conn->query($checkShippingSql);

    if ($shippingResult && $shippingResult->num_rows > 0) {
        // Insert the order without updating the product quantity
        $insertOrderSql = "INSERT INTO orders (product_id, user_id, quantity, status, order_date) 
                           VALUES ('$productId', '$userId', '$quantity', '$status', '$orderDate')";

        if ($conn->query($insertOrderSql) === TRUE) {
            // Successfully inserted the order
            echo "<script>alert('Order placed successfully!');</script>";
            header("Location: confirmation.php");
            exit();
        } else {
            echo "<script>alert('Error placing order: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please complete your shipping information before placing an order.');</script>";
        header("Location: shipping.php");  // Redirect to shipping info page
        exit();
    }
} elseif (isset($_POST['pay'])) {
    echo "<script>alert('Order details are incomplete. Please try again.');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="confirmation.css">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
</head>
<body>
    <nav class="nav">
        <a href="home.php" id="logo-link">
            <img src="../../img/logo.png" alt="logo" id="logo-image">
        </a>
        <div class="nav-links">
            <a href="user-profile.php"><i class="bi bi-person"></i></a>
            <a href="cart.php"><i class="bi bi-cart"></i></a>
            <a href="../logout.php" class="btn">Log Out</a>
        </div>
    </nav>

    <div class="container">
        <h1>Thank You for Your Order!</h1>
        <p>Your order has been successfully placed. Here are your order details:</p>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($orderDate); ?></p>
            <p><strong>Order Status:</strong> <?= htmlspecialchars($status); ?></p>

            <h3>Product Details</h3>
            <p><strong>Product Name:</strong> <?= htmlspecialchars($productName); ?></p>
            <p><strong>Quantity:</strong> <?= htmlspecialchars($quantity); ?></p>
            <p><strong>Price per Item:</strong> $<?= htmlspecialchars(number_format($productPrice, 2)); ?></p>
            <p><strong>Total Price:</strong> $<?= htmlspecialchars(number_format($productPrice * $quantity, 2)); ?></p>

            <h3>Shipping Information</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($fname) . ' ' . htmlspecialchars($lname); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($address) . ', ' . htmlspecialchars($city) . ', ' . htmlspecialchars($province); ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($phone); ?></p>
        </div>

        <button onclick="window.location.href='home.html'" class="btn">Back to Home</button>
    </div>
</body>
</html>