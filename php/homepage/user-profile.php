<?php 
session_start();
include('../connect.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch current user information
$result = $conn->query("SELECT firstname, lastname, email FROM users WHERE email = '$email'");
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare new data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $newEmail = $user['email']; // Retain the current email
    $password = $_POST['password'];

    // Validate first name and last name length (max 15 characters)
    if (strlen($firstname) > 15) {
        echo "<script>alert('First Name must be 15 characters or less.');</script>";
    } elseif (strlen($lastname) > 15) {
        echo "<script>alert('Last Name must be 15 characters or less.');</script>";
    } elseif ( strlen($password) > 10) {
        // Validate password length (min 10 characters)
        echo "<script>alert('Password must not exceed 10 characters.');</script>";
    } else {
        // Hash the password if it's provided
        if (!empty($password)) {
            $hashedPassword = md5($password); // Using MD5 as requested
            // Update user information with new password
            $updateSql = "UPDATE users SET firstname = '$firstname', lastname = '$lastname', password = '$hashedPassword' WHERE email = '$email'";
        } else {
            // Update user information without changing the password
            $updateSql = "UPDATE users SET firstname = '$firstname', lastname = '$lastname' WHERE email = '$email'";
        }

        // Execute the update
        if ($conn->query($updateSql) === TRUE) {
            echo "<script>alert('Profile updated successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
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
    <link rel="stylesheet" href="../../css/profile.css">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>PC Zone</title>
</head>
<body>
    <nav>
        <a href="home.html" class="brand">
            <img src="../../img/logo.png" alt="Logo" class="logo-img">
        </a>
        
        <ul class="nav-menu">
            <li><a href="home.html">Home</a></li>
           
            <li><a href="shop.php">Shop</a></li>
            <li><a class="link" href="order-placed.php">View Order</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="cart.php"><i class="bi bi-cart"></i></a></li> <!-- Icon only for Cart -->
        </ul>
    
        <!-- User Menu -->
        <div class="user-menu">
            <a href="user-profile.php" class="user">
                <i class="bi bi-person"></i> <!-- Icon only for User -->
            </a>
            <a href="../logout.php" class="logout">Logout</a> <!-- Keep text for Logout -->
        </div>
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
                <form method="post" action="">
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