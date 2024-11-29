<?php
session_start();
include('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php"); // Redirect to login if not logged in
    exit();
}

// Fetch product data if an ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $productSql = "SELECT * FROM products WHERE id = $id";
    $productResult = $conn->query($productSql);
    $product = $productResult->fetch_assoc();
} else {
    header("Location: update-products.php"); // Redirect if no ID is provided
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $quantity = $_POST['quantity'];

    // Update the product
    $updateSql = "UPDATE products SET name='$name', description='$description', price='$price', status='$status', quantity='$quantity' WHERE id=$id";

    if ($conn->query($updateSql) === TRUE) {
        echo "<script>alert('Product updated successfully!'); window.location.href='update-products.php';</script>";
    } else {
        echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg/png" href="../../../img/logo.png">
    <link rel="stylesheet" href="../../../css/panel.css">
    <title>PC ZONE - Edit Product</title>
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
            <li><a href="../view-posts.php"><span class="text">View Posts</span></a></li>
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
                    <h1>Edit Product</h1>
                </div>
            </div>

            <!-- Edit Product Form -->
            <div class="wrapper">
                <form method="post" action="">
                    <div class="input-box">
                        <p>Name</p>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="input-box">
                        <p>Description</p>
                        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    <div class="input-box">
                        <p>Price</p>
                        <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </div>
                    <div class="input-box">
                        <p>Status</p>
                        <select name="status" required>
                            <option value="active" <?php echo ($product['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($product['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <p>Quantity</p>
                        <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                    </div>
                    <button type="submit" class="btn">Update Product</button>
                </form>
            </div>
            <!-- End of Edit Product Form -->

        </main>
    </section>
    <!-- CONTENT -->
</body>
</html>
