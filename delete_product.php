no<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Supermarket Home</title>
    <style>
        .custom-button{
        background-color: #4CAF50;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        text-decoration: none;
        }
        .navbar {
        display: flex;
        justify-content: center; 
        align-items: center; 
        height: 60px;
        position: sticky;
        top: 0;
        }
        a{
            text-decoration: none;
        }
        .navbar nav {
            margin: 0 10px; 
        }
        .conform{
            color: #4CAF50;
            transition: opacity 1s;
        }
        .found{
            color: #ffd700;
            transition: opacity 1s;
        }
    </style>
</head>

<body>

    <header>
    <div class="navbar">
            <nav>
                <a class="custom-button" href="signin.php">Logout</a>
                <a class="custom-button" href="staff_dashboard.php">Back to main view</a>
            </nav>
        </div>
    </header>
    <main style="min-width:700px; border-radius:20px; background-color:#F5FFFA"class="container">
        <h1 style="text-align:center;" >Delete Product</h1><br>
        <div style="text-align:center;" class="button">
        <form action="" method="post" enctype="multipart/form-data">
            <label style="font-size:20px; margin-right:20px"for="product_name"><b>Product Name:</b></label>
            <input class="name ele" type="text" id="product_name" name="product_name" placeholder="Enter product name" required><br><br>

            <input class="clkbtn" type="submit" value="Submit" name="btn">
        </form>
        </div>
        <?php
        //start the session 
        session_start();
        if(isset($_POST['btn'])){
            $name = $_POST['product_name'];

            //DB info
            $servername = "localhost";
            $username = "root"; // Default MySQL username
            $password = ""; // Default MySQL password 

            // Create connection
            $conn = new mysqli($servername, $username, $password);
            if ($conn->connect_error) {
                die('Database connection failed: ' . $conn->connect_error);
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
            // check if the product exists...
                $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE productname = ?");
                $stmt->bind_param('s', $name);
                $stmt->execute();
                $stmt->bind_result($productCount);
                $stmt->fetch();
                $stmt->close();

                // If product exist, update it
                if ($productCount > 0) {
                    $sql = "DELETE FROM products WHERE productname = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $name);
                    if (!$stmt->execute()) {
                        // Handle the case where the execution was not successful
                        echo "Error executing the query: " . $stmt->error;}
                    else{
                        ?><div class="success">
                            <p class="conform">Product deleted successfully!</p>
                        </div>
                    <?php }
                }
                // If product doesn't exists
                else {
                    echo"
                    <div>
                        <h2 class='found'>Product doesnt exist!</h2>
                    </div>   " ;
                }
                $conn->close();
        }?>
        <script>
        // Get the message elements
        const conformMessage = document.querySelector('.conform');
        const foundMessage = document.querySelector('.found');

        // Set a timeout to hide the messages after 2 seconds
        setTimeout(() => {
            if (conformMessage) {
                conformMessage.style.display = 'none';
            }
            if (foundMessage) {
                foundMessage.style.display = 'none';
            }
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>
    </main>    
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