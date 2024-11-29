<?php

session_start();
include('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Update order status if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];

    // Update the order status
    $updateSql = "UPDATE orders SET status = '$newStatus' WHERE id = $orderId AND status != 'Cancelled'";
    if ($conn->query($updateSql) === TRUE) {
        echo "<script>alert('Order status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating order status: " . $conn->error . "');</script>";
    }
}

// Fetch orders and shipping information
$ordersSql = "SELECT DISTINCT o.id, o.quantity, o.status, o.order_date, p.name AS product_name, p.price,
                     s.fname, s.lname, s.address, s.province, s.city, s.phone
              FROM orders o
              JOIN products p ON o.product_id = p.id
              JOIN shipping_info s ON s.user_id = (SELECT user_id FROM orders WHERE id = o.id)
              ORDER BY o.order_date DESC";

$ordersResult = $conn->query($ordersSql);

// Check if the query was successful
if (!$ordersResult) {
    die("Query failed: " . $conn->error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
    <link rel="stylesheet" href="../../css/panel.css">
    <title>PC-ZONE - View Orders</title>
    <style>
        .order-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .order-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            flex: 1 1 calc(30% - 20px);
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        }
        .btn {
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <nav>
        <a href="admin-panel.html" class="brand"><p>Cloud Art Gallery</p></a>
        <ul class="nav-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="add-products.php">Add Products</a></li>
            <li><a href="view-orders.php">View Orders</a></li>
            <li><a href="accounts.php">Accounts</a></li>
            <li><a href="logout.php" class="logout">Logout</a></li>
        </ul>
    </nav>

    <section id="content">
        <main>
            <div class="head-title">
                <h1>View Orders</h1>
            </div>
            <div class="order-list">
                <?php if ($ordersResult->num_rows > 0) { 
                    while ($order = $ordersResult->fetch_assoc()) {
                        $totalPrice = $order['quantity'] * $order['price']; ?>
                        <div class="order-box">
                            <h3>Order ID: <?= htmlspecialchars($order['id']); ?></h3>
                            <p><strong>Product Name:</strong> <?= htmlspecialchars($order['product_name']); ?></p>
                            <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']); ?></p>
                            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']); ?></p>
                            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']); ?></p>
                            <p><strong>Price per Item:</strong> Npr <?= htmlspecialchars(number_format($order['price'], 2)); ?></p>
                            <p><strong>Total Price:</strong> Npr <?= htmlspecialchars(number_format($totalPrice, 2)); ?></p>

                            <!-- Display Shipping Info -->
                            <h4>Shipping Information</h4>
                            <p><strong>Name:</strong> <?= htmlspecialchars($order['fname']) . ' ' . htmlspecialchars($order['lname']); ?></p>
                            <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) . ', ' . htmlspecialchars($order['city']) . ', ' . htmlspecialchars($order['province']); ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']); ?></p>

                            <?php if ($order['status'] !== 'Cancelled') { ?>
                                <!-- Update Status Form -->
                                <form method="post" action="">
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']); ?>">
                                    <label for="status">Change Status:</label>
                                    <select name="status" required>
                                        <option value="Pending" <?= ($order['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Completed" <?= ($order['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                    <button type="submit" class="btn">Update Status</button>
                                </form>
                            <?php } else { ?>
                                <p><strong>Note:</strong> Status is "Cancelled" and cannot be changed.</p>
                            <?php } ?>
                        </div>
                <?php }
                } else { ?>
                    <p>No orders found.</p>
                <?php } ?>
            </div>
        </main>
    </section>
</body>
</html>
