<?php
session_start();
include('connect.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch current user information from the `admin` table
$result = $conn->query("SELECT firstname, lastname, email FROM admin WHERE email = '$email'");
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('User not found.');</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];

    $errors = [];

    // Validate first name
    if (strlen($firstname) < 3 || strlen($firstname) > 15) {
        $errors[] = "First Name must be between 3 and 15 characters.";
    }

    // Validate last name
    if (strlen($lastname) < 3 || strlen($lastname) > 15) {
        $errors[] = "Last Name must be between 3 and 15 characters.";
    }

    // Validate password length (if provided)
    if (!empty($password) && strlen($password) > 10) {
        $errors[] = "Password must not exceed 10 characters.";
    }

    if (empty($errors)) {
        // Prepare the update query
        if (!empty($password)) {
            $hashedPassword = md5($password); // Using MD5 as requested
            $updateSql = "UPDATE admin SET firstname = '$firstname', lastname = '$lastname', password = '$hashedPassword' WHERE email = '$email'";
        } else {
            $updateSql = "UPDATE admin SET firstname = '$firstname', lastname = '$lastname' WHERE email = '$email'";
        }

        // Execute the update query
        if ($conn->query($updateSql) === TRUE) {
            echo "<script>alert('Profile updated successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/panel.css">
    <link rel="icon" type="image/jpg/png" href="../../../img/logo.png">
    <title>PC ZONE - Update Profile</title>
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
            <li><a href="../logout.php" class="logout"><span class="text">Logout</span></a></li>
        </ul>
    </nav>
    
    <!-- CONTENT -->
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Update Profile</h1>
                </div>
            </div>
            <div class="wrapper" id="update-profile">
                <form method="post" action="update-profile.php">
                    <div class="input-box">
                        <p>First Name</p>
                        <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                    </div>
                    <div class="input-box">
                        <p>Last Name</p>
                        <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                    </div>
                    <div class="input-box">
                        <p>Email</p>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly required>
                    </div>
                    <div class="input-box">
                        <p>New Password (leave blank to keep current password)</p>
                        <input type="password" name="password">
                    </div>
                    <button type="submit" class="btn" name="update">Update Profile</button>
                </form>
            </div>
        </main>
    </section>
</body>
</html>
