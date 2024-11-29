<?php
session_start();
include('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

// Fetch all active products from the database
$activePostsSql = "SELECT * FROM products WHERE status = 'active'"; 
$activePostsResult = $conn->query($activePostsSql);

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/panel.css">
   <link rel="icon" type="image/jpg/png" href="../../../img/logo.png">
       <title>PC ZONE - Active Posts</title>
    <style>
        .post-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columns per row */
            gap: 20px;
            margin: 20px;
        }
        .post-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: white;
            text-align: center;
        }
        .post-box h3 {
            margin: 0 0 10px;
        }
        .post-box p {
            margin: 5px 0;
        }
        .post-box img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
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
                    <h1>Active Posts</h1>
                </div>
            </div>

            <!-- Active Post List Section -->
            <div class="post-list">
                <?php if ($activePostsResult->num_rows > 0) { 
                    while ($post = $activePostsResult->fetch_assoc()) { ?>
                        <div class="post-box">
                            <h3><?= htmlspecialchars($post['name']); ?></h3>
                            <img src="../uploads/<?= htmlspecialchars($post['image']); ?>" alt="<?= htmlspecialchars($post['name']); ?>">
                            <p><?= htmlspecialchars($post['description']); ?></p> <!-- Ensure 'description' is the correct column name -->
                            <p><strong>Status:</strong> <?= htmlspecialchars($post['status']); ?></p>
                            <p><strong>Quantity:</strong> <?= htmlspecialchars($post['quantity']); ?></p>
                            <p><strong>Price:</strong> $<?= htmlspecialchars($post['price']); ?></p>
                        </div>
                <?php }
                } else { ?>
                    <p>No active posts found.</p>
                <?php } ?>
            </div>
            <!-- End of Active Post List Section -->

        </main>
    </section>
    <!-- CONTENT -->
</body>
</html>
