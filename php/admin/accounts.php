<?php
session_start();

// Include database connection
include('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

// Fetch total number of users
$totalUsersSql = "SELECT COUNT(*) as total FROM users";
$result = $conn->query($totalUsersSql);
$totalUsers = $result->fetch_assoc()['total'];

// Fetch user details
$usersSql = "SELECT firstname, lastname, email FROM users";
$usersResult = $conn->query($usersSql);

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/panel.css">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
    <title>PC ZONE - Accounts</title>
    <style>
        /* Additional styles for the user list */
        .user-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 boxes per row */
            gap: 20px; /* Space between boxes */
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .user-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: white;
        }
        .user-box p {
            margin: 5px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; /* Prevent line breaks */
        }
    </style>
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
    <!-- NAVBAR -->

    <!-- CONTENT -->
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>User Accounts</h1>
                    <p>Total Users: <?php echo $totalUsers; ?></p>
                </div>
            </div>

            <!-- User List Section -->
            <div class="user-list">
                <?php if ($usersResult->num_rows > 0) {
                    while ($user = $usersResult->fetch_assoc()) { ?>
                        <div class="user-box">
                            <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['firstname']); ?></p>
                            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['lastname']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    <?php }
                } ?>

                <?php if ($usersResult->num_rows === 0) { ?>
                    <p>No users found.</p>
                <?php } ?>
            </div>
            <!-- End of User List Section -->

        </main>
    </section>
    <!-- CONTENT -->
</body>
</html>
