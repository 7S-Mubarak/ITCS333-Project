<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="images/logo.png">
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
    <main style="min-width:700px; border-radius:20px; background-color:#F5FFFA" class="container">
        <div style="text-align:center;" class="button">
        <h1 style="text-align:center;" >Add Product</h1><br>
        <form action="" method="post" enctype="multipart/form-data">
            <label style="font-size:20px; margin-right:20px" for="product_name">Product Name:</label>
            <input class="name ele" type="text" id="product_name" name="product_name" placeholder="Enter product name" required><br><br>
            <label ><h3> Category: </h3></label>

        
            <select id="category" name="category">
                <option value="fruits&vegetables">Fruits and vegetables </option>
                <option value="meat">Meat</option>
                <option value="bakery">Bakery</option>
                <option value="beverages">Beverages</option>
                <option value="Electronics">Electronics</option>
            </select> <br><br>
        
            <div>
                <h3>Product Details:</h3>
                <textarea id="product_details" name="product_details" rows="4" cols="40" placeholder="Enter product details" required></textarea><br><br>
            </div>
            <label for="product_image"><h3>Upload Picture:</h3></label>
            <input type="file" id="product_image" name="product_image" accept="image/*" required><br><br>
            
            <label style="font-size:20px; margin-right:20px" for="quantity_available">Quantity Available:</label>
            <input class="name ele" type="number" id="quantity_available" name="quantity_available" placeholder="Enter quantity available" required><br><br>

            <label style="font-size:20px; margin-right:20px" for="price_per_qty">Price Per Quantity:</label>
            <input class="name ele" type="number" id="price_per_qty" name="price_per_qty" step="0.001" placeholder="Enter price per quantity" required><br><br>

            <input class="clkbtn" type="submit" value="Submit" name="btn">
        </form>
        </div>
        <?php
        //start the session 
        session_start();
        if(isset($_POST['btn'])){
            $name = $_POST['product_name'];
            $_SESSION['pname'] = $name;
            $category = $_POST['category'];
            $_SESSION['category'] = $category;
            $details = $_POST['product_details'];
            $_SESSION['pdetails'] = $details;
            if (isset($_FILES['product_image'])) {
                $image= $_FILES['product_image']['name'];
                $_SESSION['img'] = $image;
            } else {
                die("Error: Image not provided or empty");
            }
            $quantity = $_POST['quantity_available'];
            $_SESSION['qty'] = $quantity;
            $price = $_POST['price_per_qty'];
            $_SESSION['price'] = $price;
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
                // If product doesn't exist, insert it
                if ($productCount == 0) {
                    $sql = "INSERT INTO products (productname, category,details, pic, qty, price) VALUES (?, ?, ?, ?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssssss', $name, $category ,$details, $image, $quantity, $price);
                    if (!$stmt->execute()) {
                        // Handle the case where the execution was not successful
                        echo "Error executing the query: " . $stmt->error;}
                    else{
                        ?><div class="success">
                            <p class="conform">Product added successfully!</p>
                        </div>
                    <?php }
                }
                // If product exists
                elseif ($productCount > 0) {
                    ?>
                    <div class="conform"  >
                        <h2>Product Update Confirmation</h2>
                            <label class="found">The product already exists. Do you want to replace it?</label><br>
                        <form action="update_product.php" method="post">
                            <button type="submit" name="action" value="yes">Yes</button>
                            <button type="submit" name="action" value="no">No</button>
                        </form>
                    </div>
                    <?php   
                }
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