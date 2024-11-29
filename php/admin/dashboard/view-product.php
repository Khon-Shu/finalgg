<?php
session_start();
include('../connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

// Check if a product ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Get the product ID from the URL
    $productSql = "SELECT * FROM products WHERE id = $id";
    $productResult = $conn->query($productSql);

    // Check if the product exists
    if ($productResult && $productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc(); // Fetch product details
    } else {
        // Redirect to view-products.php if the product does not exist
        header("Location:update-products.php");
        exit();
    }
} else {
    // Redirect to view-products.php if no ID is provided
    header("Location: update-products.php");
    exit();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/panel.css">
    <link rel="icon" type="image/jpg/png" href="../../../img/logo.png">
    <title>PC ZONE - View Product</title>
    <style>
        /* Additional styles for the product details */
        .product-details {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
        }
        .product-details h2 {
            margin-bottom: 20px;
        }
        .product-details img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .product-details p {
            margin: 10px 0;
        }
        .product-details a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .product-details a:hover {
            background-color: #0056b3;
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
                    <h1>Product Details</h1>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="product-details">
                <h2><?= htmlspecialchars($product['name']); ?></h2>
                <img src="../uploads/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($product['description'])); ?></p>
                <p><strong>Price:</strong> Npr <?= htmlspecialchars($product['price']); ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($product['status']); ?></p>
                <p><strong>Quantity:</strong> <?= htmlspecialchars($product['quantity']); ?></p>
                <a href="update-products.php">Back to Products</a>
            </div>
            <!-- End of Product Details Section -->

        </main>
    </section>
    <!-- CONTENT -->
</body>
</html>
