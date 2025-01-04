<?php
// Start the session
session_start();

$registrationMessage = "";
$loginMessage = "";
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = ""; // Default MySQL password 

// Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Select database
$db = 'SupermarketDb';
$db_selected = mysqli_select_db($conn, $db);
if (!$db_selected) {
    // If the database doesn't exist, create it
    $db_create_query = "CREATE DATABASE IF NOT EXISTS $db";
    if ($conn->query($db_create_query) === false) {
        die("Error creating database: " . $conn->error);
    }
    // Select the newly created database
    mysqli_select_db($conn, $db);
}

// Create users table if it doesn't exist
$users_table = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Increased length for hashed passwords
    role VARCHAR(10) NOT NULL
)";
if ($conn->query($users_table) === false) {
    die("Error creating table: " . $conn->error);
}

// Admin user setup
$email = 'admin@gmail.com';
$username = 'admin';
$password = 'abc123';
$role = 'admin';

// Check if admin user already exists
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($userCount);
$stmt->fetch();
$stmt->close();

// If admin user doesn't exist, insert it
if ($userCount == 0) {
    $sql = "INSERT IGNORE INTO users (email, username, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $email, $username, $password, $role);
    if (!$stmt->execute()) {
        die("Error inserting admin user: " . $stmt->error);
    }
    $stmt->close();
}

//================REGISTER=======================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Securing users' input
    $regUsername = filter_input(INPUT_POST, 'reg_username', FILTER_SANITIZE_STRING);
    $regEmail = filter_input(INPUT_POST, 'reg_email', FILTER_SANITIZE_EMAIL);
    $regPassword = filter_input(INPUT_POST, 'reg_password', FILTER_SANITIZE_STRING);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    // Validate the password
    if ($regPassword !== $confirmPassword) {
        $registrationMessage = "Passwords do not match.";
    } elseif (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $regPassword)) {
        $registrationMessage = "Password must contain both letters and numbers.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->bind_param('s', $regEmail);
        $stmt->execute();
        $stmt->bind_result($emailCount);
        $stmt->fetch();
        $stmt->close();

        //here will check the email if it is already exist in the db
        if ($emailCount > 0) {
            $registrationMessage = "The email address is already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, 'customer')");
            $stmt->bind_param('sss', $regEmail, $regUsername, $regPassword);
            if ($stmt->execute()) {
                $_SESSION['userId'] = $conn->insert_id; // Save the user ID in the session
                $_SESSION['username'] = $regUsername; // Save the username in the session
                // Redirect after registration is successful
                header('Location: customer_dashboard.php');
                exit();
            } else {
                $registrationMessage = "Registration failed: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

//================LOGIN=======================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Securing users' input
    $loginEmail = filter_input(INPUT_POST, 'login_email', FILTER_SANITIZE_EMAIL);
    $loginPassword = filter_input(INPUT_POST, 'login_password', FILTER_SANITIZE_STRING);
    
    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param('ss', $loginEmail, $loginPassword);
    $stmt->execute();
    $stmt->bind_result($userId, $username, $role);
    $stmt->fetch();
    $stmt->close();
    
    if ($userId) {
        $_SESSION['userId'] = $userId; // Save the user ID in the session
        $_SESSION['username'] = $username; // Save the username in the session
        header('Location: ' . $role . '_dashboard.php');
        exit();
    } else {
        $loginMessage = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Supermarket Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="images/logo.png">
</head>
<body>
<header style="background-color: #333">
    <div class="navbar">
        <div>
            <img style="width:600px; height:100px;" src="images/logo2.png"/>
        </div>
    </div>
</header>

<h1 class="heading">Welcome to Supermarket Please Login / Sign Up</h1>
<div class="Lcontainer">
    <div class="slider"></div>
    <div class="btn">
        <button class="login">Login</button>
        <button class="signup">Signup</button>
    </div>
    <div class="form-section">
        <div class="login-box">
            <?php if ($loginMessage) : ?>
                <p><?php echo $loginMessage; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="text" class="email ele" name="login_email" placeholder="Enter your email" required>
                <input type="password" class="password ele" name="login_password" placeholder="Enter your password" required>
                <button type="submit" class="clkbtn" name="login">Login</button>
            </form>
        </div>

        <div class="signup-box">
            <?php if ($registrationMessage) : ?>
                <p><?php echo $registrationMessage; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="text" class="name ele" name="reg_username" placeholder="Enter your username" required>
                <input type="email" class="email ele" name="reg_email" placeholder="youremail@email.com" required>
                <input type="password" class="password ele" name="reg_password" placeholder="Enter your password" required>
                <input type="password" name="confirm_password" class="password ele" placeholder="Confirm">
                <button type="submit" class="clkbtn" name="register">Register</button>
            </form>
        </div>
    </div>
</div>

<script src="login.js"></script>

<footer>
    <div class="footerContainer">
        <div class="socialIcons">
            <a href=""><i class="fa-brands fa-facebook"></i></a>
            <a href=""><i class="fa-brands fa-instagram"></i></a>
            <a href=""><i class="fa-brands fa-twitter"></i></a>
            <a href=""><i class="fa-brands fa-google-plus"></i></a>
            <a href=""><i class="fa-brands fa-youtube"></i></a>
        </div>
    </div>
    <div class="footerBottom">
        <p>Copyright &copy;2024 Designed by <span class="designer">Husain</span></p>
    </div>
</footer>

</body>
</html>