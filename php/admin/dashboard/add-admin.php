<?php
session_start();

// Include database connection
include('connect.php');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation
    $errors = [];

    // Validate first name (3 to 15 characters)
    if (strlen($firstname) < 3 || strlen($firstname) > 15) {
        $errors[] = "First name must be at least 3 characters and not exceed 15 characters.";
    }

    // Validate last name (3 to 15 characters)
    if (strlen($lastname) < 3 || strlen($lastname) > 15) {
        $errors[] = "Last name must be at least 3 characters and not exceed 15 characters.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password length
    if (strlen($password) > 10) {
        $errors[] = "Password must not exceed 10 characters.";
    }

    // Check if the email already exists in the `admin` table
    $emailCheckSql = "SELECT * FROM admin WHERE email = '$email'";
    $result = $conn->query($emailCheckSql);

    if ($result->num_rows > 0) {
        // Email already exists
        $errors[] = "Error: Email already exists. Please use a different email.";
    }

    // If there are no errors, proceed to insert the new admin
    if (empty($errors)) {
        // Hash the password using MD5
        $hashedPassword = md5($password);

        // Prepare SQL statement to insert new admin
        $insertSql = "INSERT INTO admin (firstname, lastname, email, password) 
                      VALUES ('$firstname', '$lastname', '$email', '$hashedPassword')";

        // Execute the insert
        if ($conn->query($insertSql) === TRUE) {
            echo "<script>alert('Admin added successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        // If there are validation errors, display them
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
    <title>PC ZONE - Add Admin</title>
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
    
    <!-- CONTENT -->
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Add Admin</h1>
                </div>
            </div>
            <div class="wrapper" id="add-admin">
                <form method="post" action="add-admin.php">
                    <div class="input-box">
                        <p>First Name</p>
                        <input type="text" name="firstname" required>
                    </div>
                    <div class="input-box">
                        <p>Last Name</p>
                        <input type="text" name="lastname" required>
                    </div>
                    <div class="input-box">
                        <p>Email</p>
                        <input type="email" name="email" required>
                    </div>
                    <div class="input-box">
                        <p>Password</p>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="btn" name="add-admin">Add Admin</button>
                </form>
            </div>
        </main>
    </section>

    <script src="script.js"></script>
</body>
</html>
