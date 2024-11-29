<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user data from session or database
$email = $_SESSION['email'];
$firstname = "Admin"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/panel.css">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
    
    <title>PC ZONE</title>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
    <a href="admin-panel.html" class="brand">

            <p>PC ZONE</p>
        </a>
        <ul class="nav-menu">
            <li>
                <a href="dashboard.php">
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="add-products.php">
                    <span class="text">Add Products</span>
                </a>
            </li>
            <li>
                <a href="view-orders.php">
                    <span class="text">View Orders</span>
                </a>
            </li>
            <li>
                <a href="accounts.php">
                    <span class="text">Accounts</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </nav>
    <!-- NAVBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                </div>
            </div>

            <!-- Boxes Section -->
            <div class="boxes">
                <div class="box">
                    <h2>Hello <?php echo htmlspecialchars($firstname); ?></h2>
                    <p>Click to <a href="dashboard/update-profile.php">update your profile</a>.</p>
                </div>
                <div class="box">
                    <h2>Add Admins</h2>
                    <p>Click to <a href="dashboard/add-admin.php">add new admins</a>.</p>
                </div>
                <div class="box">
                    <h2>Products Added</h2>
                    <p>Click to <a href="dashboard/update-products.php">view and update added products</a>.</p>
                </div>
                <div class="box">
                    <h2>Active Posts</h2>
                    <p>Click to <a href="dashboard/view-active-posts.php">view active posts</a>.</p>
                </div>
            </div>
            <!-- End of Boxes Section -->

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
</body>
</html>
