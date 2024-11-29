<?php
session_start();
include('../connect.php');

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Handle Order Cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];

    // Update the status of the order to "Cancelled" only if it's still pending
    $sql = "UPDATE orders SET status = 'Cancelled' WHERE id = '$order_id' AND user_id = '$user_id' AND status = 'Pending'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Order cancelled successfully!');</script>";
        header("Location: order-placed.php");
        exit();
    } else {
        echo "<script>alert('Error cancelling order: " . $conn->error . "');</script>";
    }
}

// Fetch all orders placed by the user
$sql = "SELECT o.id AS order_id, o.status, o.order_date, o.product_id, o.quantity, p.name, p.price, p.image 
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.user_id = '$user_id'
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
$orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Zone - My Orders</title>
    <link rel="stylesheet" href="../../css/order-placed1.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
</head>
<body>
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo">
                <a href="home.html"><img src="../../img/logo.png" alt="Logo" class="logo-img"></a>
            </div>
            <ul class="nav-links">
                <li>
                    <a class="link" href="home.html">Home</a>
                    <a class="link" href="shop.php">Shop</a>
                    <a class="link" href="order-placed.php">View Order</a>
                    <a class="link" href="about.html">About Us</a>
                    <a href="cart.php"><i class="bi bi-cart"></i></a>
                    <a href="update-profile.php"><i class="bi bi-user"></i></a>
                </li>
            </ul>
            <div class="nav-buttons">
                <a href="../logout.php" class="btn">Log out</a>
            </div>
        </div>
    </nav>

    <h1>My Orders</h1>
    <div class="order-container">
        <?php if (!empty($orders)): ?>
            <?php 
            $currentOrderId = null; // Track current order to group products
            foreach ($orders as $order): 
                if ($currentOrderId !== $order['order_id']):
                    if ($currentOrderId !== null): ?>
                        </table>
                        <?php if ($currentOrderStatus === 'Pending'): ?>
                            <form method="post" action="order-placed.php">
                                <input type="hidden" name="order_id" value="<?php echo $currentOrderId; ?>">
                                <button type="submit" name="cancel_order" class="cancel-btn">Cancel Order</button>
                            </form>
                        <?php endif; ?>
                        <hr>
                    <?php endif; ?>

                    <div class="order-details">
                        <h3>Order #<?php echo $order['order_id']; ?></h3>
                        <p>Status: <strong><?php echo $order['status']; ?></strong></p>
                        <p>Order Date: <?php echo $order['order_date']; ?></p>
                        <p>The product will be delivered within 5 days after contacting the customer</p>
                        <table style="background-color: white; color:black;">
                            <tr>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                <?php 
                $currentOrderId = $order['order_id'];
                $currentOrderStatus = $order['status'];
                endif;
                ?>
                            <tr>
                                <td><?php echo $order['name']; ?></td>
                                <td><img src="../admin/uploads/<?php echo $order['image']; ?>" alt="<?php echo $order['name']; ?>" width="100"></td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td>Npr <?php echo number_format($order['price'], 2); ?></td>
                                <td>Npr <?php echo number_format($order['price'] * $order['quantity'], 2); ?></td>
                            </tr>
            <?php endforeach; ?>
                    </table>
                    <?php if ($currentOrderStatus === 'Pending'): ?>
                        <form method="post" action="order-placed.php">
                            <input type="hidden" name="order_id" value="<?php echo $currentOrderId; ?>">
                            <button type="submit" name="cancel_order" class="cancel-btn">Cancel Order</button>
                        </form>
                    <?php endif; ?>
                    <hr>
        <?php else: ?>
            <p>You have not placed any orders yet!</p>
        <?php endif; ?>
     

</body>
</html>
