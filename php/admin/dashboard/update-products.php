<?php
session_start();
include('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

// Fetch all products from the database
$productsSql = "SELECT * FROM products";
$productsResult = $conn->query($productsSql);
$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/panel.css">
    <link rel="icon" type="image/jpg/png" href="../../../img/logo.png">
    <title>PC ZONE - View Products</title>
    <style>
        /* Additional styles for the product grid */
        .product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columns per row */
            gap: 20px; /* Space between boxes */
            margin: 20px;
        }
        .product-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: white;
            text-align: center;
        }
        .product-box h3 {
            margin: 0 0 10px;
        }
        .product-box p {
            margin: 5px 0;
        }
        .product-box img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }
        .action-buttons a {
            padding: 5px 10px;
            border: 1px solid #333;
            border-radius: 3px;
            text-decoration: none;
            color: #333;
            font-size: 0.9em;
        }
        .action-buttons a:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
    <a href="../admin-panel.html" class="brand">
            <p>PC ZONE</p>
        </a>
        <ul class="nav-menu">
            <li><a href="../dashboard.php"><span class="text">Dashboard</span></a></li>
            <li><a href="../add-products.php"><span class="text">Add Products</span></a></li>
            <li><a href="../view-orders.php"><span class="text">View Orders</span></a></li>
            <li><a href="../accounts.php"><span class="text">Accounts</span></a></li>
            <li><a href="logout.php" class="logout"><span class="text">Logout</span></a></li>
        </ul>
    </nav>
    <!-- NAVBAR -->

    <!-- CONTENT -->
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>View  And Update Products</h1>
                </div>
            </div>

            <!-- Product List Section -->
            <div class="product-list">
                <?php if ($productsResult->num_rows > 0): ?>
                    <?php while ($product = $productsResult->fetch_assoc()): ?>
                        <div class="product-box">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <p><strong>Price:</strong> Npr <?php echo htmlspecialchars($product['price']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($product['quantity']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($product['status']); ?></p>
                            <div class="action-buttons">
                                <a href="view-product.php?id=<?php echo $product['id']; ?>">View</a>
                                <a href="edit-product.php?id=<?php echo $product['id']; ?>">Edit</a>
                                <a href="delete-product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>
            <!-- End of Product List Section -->

        </main>
    </section>
    <!-- CONTENT -->
</body>
</html>
