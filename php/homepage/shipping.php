<?php
session_start(); // Start the session

include '../connect.php'; // Connect to practicedb

if (isset($_POST['next'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];

    // Retrieve user_id from the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // This is the logged-in user's ID
        
        // Check if the user already has an entry in shipping_info
        $checkQuery = "SELECT * FROM shipping_info WHERE user_id = '$user_id'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            // Update existing shipping info
            $updateQuery = "UPDATE shipping_info SET fname = '$fname', lname = '$lname', address = '$address', province = '$province', city = '$city', phone = '$phone' WHERE user_id = '$user_id'";
            $result = mysqli_query($conn, $updateQuery);
            if ($result) {
                echo "<script>alert('Shipping Information Updated!'); window.location.href = 'order-summary.php';</script>";
            } else {
                echo "Update Error: " . mysqli_error($conn);
            }
        } else {
            // Insert new shipping info
            $insertQuery = "INSERT INTO shipping_info (user_id, fname, lname, address, province, city, phone) 
                            VALUES ('$user_id', '$fname', '$lname', '$address', '$province', '$city', '$phone')";
            $result = mysqli_query($conn, $insertQuery);
            if ($result) {
                echo "<script>alert('Shipping Information Saved!'); window.location.href = 'order-summary.php';</script>";
            } else {
                echo "Insert Error: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error: User is not logged in.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC ZONE - Shipping Information</title>
    <link rel="stylesheet" href="../../css/shipping.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/jpg/png" href="../../img/logo.png">
</head>
<body>
    <nav class="nav">
        <a href="home.html" id="logo-link">  
            <img src="../../img/logo.png" alt="logo" name="logo" id="logo-image">
        </a>
        <div class="nav-links">
            <a href="user-profile.php" class="user">
                <i class="bi bi-person"></i> <!-- Icon only for User -->
            </a>
            <a href="cart.php"><i class="bi bi-cart"></i></a>
            <a href="../logout.php" class="btn">Log Out</a>
        </div>
    </nav>

    <div class="container">
        <div class="head-title">
            <h1>Shipping Information</h1>
        </div>

        <form id="form" method="post" action="">
            <div class="input-box">
                <label for="fname">First Name</label>
                <input type="text" name="fname" id="fname" required>
            </div>

            <div class="input-box">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname" required>
            </div>

            <div class="input-box">
                <label for="address">Street Address</label>
                <input type="text" name="address" id="address" required>
            </div>

            <div class="input-box">
                <label for="province">State/Province</label>
                <select name="province" id="province" required>
                    <option value="" selected>Please choose your province</option>
                    <option value="p1">Province 1/ Koshi Province</option>
                    <option value="p2">Province 2/ Madhesh Pradesh</option>
                    <option value="p3">Province 3/ Bagmati Province</option>
                    <option value="p4">Province 4/ Gandaki Province</option>
                    <option value="p5">Province 5/ Lumbini Province</option>
                    <option value="p6">Province 6/ Karnali Pradhesh</option>
                    <option value="p7">Province 7/ Sudurpashchim Pradesh</option>
                </select>
            </div>

            <div class="input-box">
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
            </div>

            <div class="input-box">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <button type="submit" id="next" name="next">Next</button>
        </form>
    </div>
</body>
</html>
