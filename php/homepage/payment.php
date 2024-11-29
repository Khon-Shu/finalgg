<?php
// Enable error reporting to display errors on the screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('../connect.php');  // Database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Initialize variables from session (with debugging output)
$userId = $_SESSION['user_id'];
$productId = isset($_SESSION['product_id']) ? $_SESSION['product_id'] : null;
$quantity = isset($_SESSION['quantity']) ? $_SESSION['quantity'] : null;
$status = 'Pending';
$orderDate = date('Y-m-d');  // Current date for the order date

// Debugging output to check values
echo "User ID: " . htmlspecialchars($userId) . "<br>";
echo "Product ID: " . htmlspecialchars($productId) . "<br>";
echo "Quantity: " . htmlspecialchars($quantity) . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    if ($productId && $quantity) {
        $checkShippingSql = "SELECT user_id FROM shipping_info WHERE user_id = '$userId'";
        $shippingResult = $conn->query($checkShippingSql);

        if ($shippingResult && $shippingResult->num_rows > 0) {
            // Step 1: Insert a new record in the `orders` table
            $insertOrderSql = "INSERT INTO orders (user_id, status, order_date) 
                               VALUES ('$userId', '$status', '$orderDate')";
            
            if ($conn->query($insertOrderSql) === TRUE) {
                // Retrieve the last inserted order ID
                $orderId = $conn->insert_id;

                // Step 2: Loop through products to add each item to `order_items`
                foreach ($_SESSION['cart'] as $productId => $quantity) {
                    $insertOrderItemSql = "INSERT INTO order_items (order_id, product_id, quantity) 
                                           VALUES ('$orderId', '$productId', '$quantity')";
                    $conn->query($insertOrderItemSql);

                    // Step 3: Update product quantity in the `products` table
                    $updateProductSql = "UPDATE products SET quantity = quantity - $quantity 
                                         WHERE id = '$productId'";
                    $conn->query($updateProductSql);
                }

                echo "<script>alert('Order placed successfully!');</script>";
                header("Location: confirmation.php");
                exit();
            } else {
                echo "<script>alert('Error placing order: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Please complete your shipping information before placing an order.');</script>";
            header("Location: shipping.php");
            exit();
        }
    } else {
        echo "<script>alert('Order details are incomplete. Please try again.');</script>";
    }
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC ZONE - Payment Gateway</title>
    <link rel="stylesheet" href="shipping.css">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
</head>
<body>
    <nav class="nav">
        <a href="home.html" id="logo-link">  
            <img src="../../img/logo.png" alt="logo" name="logo" id="logo-image">
        </a>
        <div class="nav-links">
            <a href="user-profile.php" class="user">
                <i class="bi bi-person"></i> <!-- Icon only for User -->
            </a>
            <a href="cart.php"><i class="bi bi-cart"></i></a>
            <a href="../logout.php" class="btn">Log Out</a>
        </div>
    </nav>

    <div class="container">
        <div class="head-title">
            <h1>Payment Gateway</h1>
        </div>

        <form id="form" method="post" action="">
            <p>Select payment method</p>
            <label>
                <input type="radio" name="payment" value="esewa" required>
                <span>ESEWA</span>
                <img src="../../../img/payment/esewa.webp" alt="ESEWA">
            </label>
            <label>
                <input type="radio" name="payment" value="khalti" required>
                <span>Khalti</span>
                <img src="../../../img/payment/khalti.png" alt="Khalti">
            </label>
            <label>
                <input type="radio" name="payment" value="visa" required>
                <span>Visa card</span>
                <img src="../../../img/payment/visa.png" alt="Visa">
            </label>
            <button type="submit" id="pay" name="pay">Pay</button>
        </form>
    </div>
</body>
</html>
