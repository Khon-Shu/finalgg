<?php
session_start();
include('../connect.php');

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.*, p.price, p.quantity AS product_quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$user_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Insert each item into the orders table and update product quantity
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $status = 'Pending'; // Set initial status for the order
        $order_date = date('Y-m-d H:i:s');
        $product_quantity = $row['product_quantity']; // Quantity in stock

        // Check if enough quantity is available in stock
        if ($product_quantity >= $quantity) {
            // Insert the order into the orders table
            $sql_order = "INSERT INTO orders (user_id, product_id, quantity, status, order_date) 
                          VALUES ('$user_id', '$product_id', '$quantity', '$status', '$order_date')";
            if ($conn->query($sql_order) === FALSE) {
                echo "<script>alert('Error placing order.'); window.location.href='cart.php';</script>";
                exit();
            }

            // Reduce the product quantity in the products table
            $new_quantity = $product_quantity - $quantity;
            $sql_update_product = "UPDATE products SET quantity = '$new_quantity' WHERE id = '$product_id'";
            if ($conn->query($sql_update_product) === FALSE) {
                echo "<script>alert('Error updating product quantity.'); window.location.href='cart.php';</script>";
                exit();
            }

            // If the updated quantity is zero, set the product status to inactive
            if ($new_quantity == 0) {
                $sql_update_status = "UPDATE products SET status = 'inactive' WHERE id = '$product_id'";
                if ($conn->query($sql_update_status) === FALSE) {
                    echo "<script>alert('Error updating product status.'); window.location.href='cart.php';</script>";
                    exit();
                }
            }
        } else {
            // If there is not enough quantity available
            echo "<script>alert('Not enough quantity available for product ID $product_id.'); window.location.href='cart.php';</script>";
            exit();
        }
    }

    // Clear the cart after order is processed
    $sql_clear_cart = "DELETE FROM cart WHERE user_id = '$user_id'";
    if ($conn->query($sql_clear_cart) === FALSE) {
        echo "<script>alert('Error clearing the cart.'); window.location.href='cart.php';</script>";
        exit();
    }

    // Successfully placed order
    echo "<script>alert('Order has been placed successfully!'); window.location.href='home.html';</script>";
} else {
    // If cart is empty
    echo "<script>alert('Your cart is empty.'); window.location.href='cart.php';</script>";
}
?>