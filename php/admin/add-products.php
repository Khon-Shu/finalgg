<?php
session_start();
include('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category']; // Category field

    // Handle file upload
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    // Prepare SQL statement to insert the new product
    $sql = "INSERT INTO products (name, description, price, image, status, quantity, category) 
            VALUES ('$name', '$description', '$price', '$image', '$status', '$quantity', '$category')";

    if ($conn->query($sql) === TRUE) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "<script>alert('Product added successfully!');</script>";
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/panel.css">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
    <title>PC ZONE - Add Product</title>
</head>
<body>
    <!-- NAVBAR -->
    <nav>
        <a href="admin-panel.html" class="brand">
            <p>PC ZONE</p>
        </a>
        <ul class="nav-menu">
            <li><a href="dashboard.php"><span class="text">Dashboard</span></a></li>
            <li><a href="add-products.php"><span class="text">Add Products</span></a></li>
            <li><a href="view-orders.php"><span class="text">View Orders</span></a></li>
            <li><a href="accounts.php"><span class="text">Accounts</span></a></li>
            <li><a href="logout.php" class="logout"><span class="text">Logout</span></a></li>
        </ul>
    </nav>
    
    <!-- CONTENT -->
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Add Product</h1>
                </div>
            </div>
            <div class="wrapper">
                <form method="post" action="add-products.php" enctype="multipart/form-data">
                    <div class="input-box">
                        <p>Product Name</p>
                        <input type="text" name="name" required>
                    </div>
                    <div class="input-box">
                        <p>Description</p>
                        <textarea name="description" rows="5" cols="50" style="width: 100%; height: 150px;" required></textarea>
                    </div>
                    <div class="input-box">
                        <p>Price</p>
                        <input type="number" name="price" min="1" required>
                    </div>
                    <div class="input-box">
                        <p>Status</p>
                        <select name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <p>Quantity</p>
                        <input type="number" name="quantity" min="1" required>
                    </div>

                    <!-- New Category Dropdown -->
                    <div class="input-box">
                        <p>Category</p>
                        <select name="category" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="Computer">Laptops and PCs</option>
                            <option value="Miscellaneous">Accesories Collection</option>
                            <option value="Drives and Parts">Drives and Parts</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <p>Product Image</p>
                        <input type="file" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn">Add Product</button>
                </form>
            </div>
        </main>
    </section>
</body>
</html>
