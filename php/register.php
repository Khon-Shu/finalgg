<?php 

session_start(); // Start the session at the beginning of the script

include 'connect.php';

// Sign Up Logic
if (isset($_POST['signUp'])) {
    $firstName = $_POST['fname'];
    $lastName = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpass = $_POST['cpass'];

    // Define an array for warning messages
    $warning_msg = array();

    // Validation for First Name and Last Name (3 to 15 characters)
    if (strlen($firstName) < 3 || strlen($firstName) > 15 || strlen($lastName) < 3 || strlen($lastName) > 15) {
        $warning_msg[] = "First name and last name must be between 3 and 15 characters!";
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $warning_msg[] = "Invalid email address!";
    }

    // Password validation (up to 10 characters)
    if (strlen($password) > 10) {
        $warning_msg[] = "Password must be up to 10 characters!";
    }

    // Check if the password and confirm password match
    if ($password !== $cpass) {
        $warning_msg[] = "Passwords do not match!";
    }

    // Display warning messages if any
    if (!empty($warning_msg)) {
        $allWarnings = implode("\\n", $warning_msg); // Combine all warnings with line breaks
        echo "<script>alert('$allWarnings'); window.location.href='index.php';</script>";
        exit();
    }

    // Hash the password using MD5 (consider using a stronger hash algorithm like password_hash for better security)
    $hashedPassword = md5($password);

    // Check if email already exists in the `users` or `admin` table
    $checkEmailUsers = "SELECT * FROM users WHERE email='$email'";
    $resultUsers = $conn->query($checkEmailUsers);

    $checkEmailAdmin = "SELECT * FROM admin WHERE email='$email'";
    $resultAdmin = $conn->query($checkEmailAdmin);

    if ($resultUsers->num_rows > 0 || $resultAdmin->num_rows > 0) {
        echo "<script>alert('Email Address Already Exists!'); window.location.href='index.php';</script>";
    } else {
        // Insert query if all validations pass
        $insertQuery = "INSERT INTO users (firstname, lastname, email, password)
                        VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
        if ($conn->query($insertQuery) === TRUE) {
            echo "<script>alert('Registration successful!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='index.php';</script>";
        }
    }
}

// Sign In Logic
if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = md5($password); // Hash the password

    // Check in the `users` table first
    $sqlUser = "SELECT * FROM users WHERE email='$email' AND password='$hashedPassword'";
    $resultUser = $conn->query($sqlUser);

    if ($resultUser === false) {
        echo "SQL Error: " . $conn->error;
    } else if ($resultUser->num_rows > 0) {
        // User login successful
        $row = $resultUser->fetch_assoc();
        
        // Store user information in session
        $_SESSION['user_id'] = $row['Id'];   // Store the user ID from the `users` table
        $_SESSION['email'] = $row['email'];

        // Redirect to the user homepage
        header("Location: homepage/home.html");
        exit();
    } else {
        // Check in the `admin` table
        $sqlAdmin = "SELECT * FROM admin WHERE email='$email' AND password='$hashedPassword'";
        $resultAdmin = $conn->query($sqlAdmin);

        if ($resultAdmin && $resultAdmin->num_rows > 0) {
            // Admin login successful
            $row = $resultAdmin->fetch_assoc();
            
            // Store admin information in session
            $_SESSION['admin_id'] = $row['Id'];   // Store the admin ID from the `admin` table
            $_SESSION['email'] = $row['email'];

            // Redirect to the admin panel
            header("Location: admin/admin-panel.html");
            exit();
        } else {
            // Incorrect email or password
            echo "<script type='text/javascript'>alert('Incorrect Email or Password');window.location.href = 'index.php';</script>";
        }
    }
}
?>
