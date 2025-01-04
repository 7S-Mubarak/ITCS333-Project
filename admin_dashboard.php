<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="images/logo.png">
    <title>Supermarket Home</title>
</head>

<body>

    <header>
        <div class="navbar">
            <div class="logo">
                <span>
                    <img style="width:300px; height:100px;" src="images/logo2.png"/>
                </span>
            </div>
            <nav>
                <a class="custom-button" href="signin.php">Logout</a>
            </nav>
        </div>
    </header>

    <main style="min-width:700px; border-radius:20px; background-color:#F5FFFA" class="container">
        <div class="button">
        <h2 style="text-align:center;" >Add/Delete Staff</h2><br>
            <form action="" method="post">
            <div style="text-align:center; margin-bottom: 30px;" ><input style="font-size:20px; padding: 20px 110px;" class="custom-button"type="submit" value="Add staff" name="btn"></div>
            <div style="text-align:center"><input style="font-size:20px; padding: 20px 100px;"class="custom-button"type="submit" value="Delete staff" name="btn"></div>
        </form>
        </div>
    </main>    
    <?php
    session_start(); // Start the session

    $servername = "localhost";
    $username = "root"; // Default MySQL username
    $password = ""; // Default MySQL password 
    $database = "SupermarketDb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
        }

    // Select database
    $db='SupermarketDb';
    $db_selected = mysqli_select_db($conn, $db);

    if (!$db_selected) {
    // If the database doesn't exist, create it
    $db_create_query = "CREATE DATABASE IF NOT EXISTS $db";
    if ($conn->query($db_create_query) === false) {
        die ("Error creating database: " . $conn->error) ;
    } 
    }
    // Create orders table if not exists
    $products_table = "CREATE TABLE IF NOT EXISTS products (
    productId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    productname VARCHAR(50) ,
    category VARCHAR(50),
    details TEXT,
    pic VARCHAR(20) ,
    qty INT(6),
    price DECIMAL(10, 2),
    productDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    //table creation error handling
    if ($conn->query($products_table) === false) {
    die("Error creating table: " . $conn->error);
    }
    if(isset($_POST['btn']))
    {
        if($_POST['btn']=='Add staff')
        {
            header("location:add_user.php");
        }
        elseif($_POST['btn']=='Delete staff')
        {
            header("location:delete_user.php");
        }
    }
    ?>
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
            <p>Copyright &copy;2024 Designed by <span class="designer">AQ</span></p>
        </div>
    </footer>
    
</body>
</html>