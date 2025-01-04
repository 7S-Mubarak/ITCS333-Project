<?php
    session_start(); // Start the session
    $userid=$_SESSION['userId'];
    $username=$_SESSION['username']; 

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

    // Create orders table if not exists
    $orders_table = "CREATE TABLE IF NOT EXISTS orders (
        orderId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        userId INT(6) UNSIGNED,
        FOREIGN KEY (userId) REFERENCES users(id),
        orderDetails TEXT,
        status VARCHAR(20) DEFAULT 'acknowledged',
        totalprice DECIMAL(10, 2),
        orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($orders_table) === false) {
        die("Error creating table: " . $conn->error);
    }

    // Get the category from the request
    $categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'all';

    // Build SQL query based on the category filter
    if ($categoryFilter == 'all') {
        $sql = "SELECT productname, category, details, pic, qty, price FROM products";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT productname, category, details, pic, qty, price FROM products WHERE category = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoryFilter);
    }
    if ($stmt) {
        // Execute the statement
        $stmt->execute();
        // Bind the results to variables
        $stmt->bind_result($productname, $category, $details, $pic, $qty, $price);
        // Fetch the results
        $products = array();
        while ($stmt->fetch()) {
            // Create an associative array to store the fetched order data
            $product = array(
                'productname' => $productname,
                'category' => $category,
                'details' => $details,
                'pic' => $pic,
                'qty' => $qty,
                'price' => $price 
            );
            // Appending
            $products[] = $product;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="images/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Supermarket Home</title>
    <style>
        /* Resetting default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            line-height: 1.6;
        }

        /* Header styles */
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .navbar ul {
            list-style-type: none;
            display: flex;
        }

        .navbar ul li {
            margin-right: 20px;
        }

        .search input[type="text"] {
        
            border: none;
            border-radius: 5px;
        }

        .search button {
        
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Slideshow styles */
        .slideshow-container,.mySlides {
            max-width: 1000px;
            position: relative;
            margin: auto;
            border-radius: 10%;
        }

        .mySlides {
            display: none;
        }

        .fade {
            animation: fade 2s infinite;
        }

        @keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }

        /* Container styles */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; /* Adjust alignment as needed */
        }

        /* Product styles */
        .product {
            width: calc(33.33% - 20px); /* Adjust width to fit three products per row */
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }

        /* Product section styles */
        .container section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; /* Adjust alignment as needed */
        }

        /* Adjustments for smaller screens */
        @media (max-width: 768px) {
            .product {
                width: calc(50% - 20px); /* Adjust width to fit two products per row */
            }
        }

        @media (max-width: 480px) {
            .product {
                width: 100%; /* Adjust width to fit one product per row */
            }
        }


        .product img {
            width: 100%;
            border-radius: 5px;
        }

        .price {
            font-weight: bold;
        }

        .qyt {
            margin-top: 10px;
        }

        .productbtns {
            margin-top: 10px;
        }

        /* Footer styles */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        .socialIcons a {
            color: #fff;
            margin-right: 10px;
            font-size: 20px;
        }

        .designer {
            font-weight: bold;
        }

        /* Dropdown menu styles */
        .dropdown{
            position: relative;
            display: inline-block;
        }

        .dropbtn,.custom-button,.searchbtn{
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }
    </style>
</head>

<body>
    <script>
        function searchCategory(category) {
            var selectedCategory = category || document.getElementById("searchInput").value.trim();
            // Send the selected category to PHP using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "search.php?category=" + encodeURIComponent(selectedCategory) + "&ajax=1", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Update the product section with the filtered products returned from PHP
                    document.getElementById("productSection").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
        // Get the greeting element
        const greetingElement = document.getElementById('greeting');

        // Set a timeout to hide the greeting after 5 seconds
        setTimeout(() => {
        greetingElement.style.display = 'none';
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
    
<header>
    <div class="navbar">
        <div class="dropdown">
            <!-- Dropdown menu styles -->
        <div class="dropdown">
            <button class="dropbtn">Categories</button>
            <div class="dropdown-content">
                <a href="#" onclick="searchCategory('all')">All</a>
                <a href="#" onclick="searchCategory('fruits&vegetables')">Vegetables & Fruits</a>
                <a href="#" onclick="searchCategory('Electronics')">Electronics</a>
                <a href="#" onclick="searchCategory('Beverages')">Beverages</a>
                <a href="#" onclick="searchCategory('Bakery')">Bakery</a>
                <a href="#" onclick="searchCategory('Meat')">Meat</a>
            </div>
        </div>

            </div>
            <div class="search">
                <input type="text" id="searchInput" placeholder="What are you looking for?">
                <button onclick="searchCategory(document.getElementById('searchInput').value)" class="searchbtn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <!-- Your Cart Button -->
            <nav>
                <a class="custom-button" href="cart.php">Your Cart</a>
            </nav>

            <nav>
                <a class="custom-button" href="previous_order.php">Previous Order</a>
            </nav>
        </div>
    </header>
    <div id="greeting">
        <div class="name">
            <h3 style="color: #517238; font-family: 'Courier New', Courier, monospace; font-weight: 300;">
                Welcome <?php echo $_SESSION['username'];?>, have a nice day!
            </h3>
        </div>
    </div>
    <div class="slideshow-container" style="margin-top: 50px;">
        <div class="mySlides fade">
            <div class="numbertext">1 / 5</div>
            <img src="images/slide1.jpg" style="width: 100%">
        </div>
        <div class="mySlides fade">
            <div class="numbertext">2 / 5</div>
            <img src="images/slide2.jpg" style="width: 100%">
        </div>
        <div class="mySlides fade">
            <div class="numbertext">3 / 5</div>
            <img src="images/slide3.jpg" style="width: 100%">
        </div>
        <div class="mySlides fade">
            <div class="numbertext">4 / 5</div>
            <img src="images/slide4.jpg" style="width: 100%">
        </div>
        <div class="mySlides fade">
            <div class="numbertext">5 / 5</div>
            <img src="images/slide5.jpg" style="width: 100%">
        </div>
    </div>
    <br>

    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
        <span class="dot" onclick="currentSlide(4)"></span>
        <span class="dot" onclick="currentSlide(5)"></span>
    </div>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) { slideIndex = 1 }
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 4500); 
        }
    </script>

    <div class="container">
        <h2 id="products">Products</h2> <br><br><br>
        <section id="productSection">
            <!-- Iterate through products -->
            <?php foreach ($products as $product): ?>
            <div class="product">
                <form action="cart.php" method="post">
                    <img src="images/<?php echo htmlspecialchars($product['pic']); ?>" alt="<?php echo htmlspecialchars($product['productname']); ?>" class="product-image">
                    <h3><?php echo htmlspecialchars($product['productname']); ?></h3>
                    <p class="price">BHD <?php echo htmlspecialchars($product['price']); ?></p>
                    <input type="hidden" name="productname" value="<?php echo htmlspecialchars($product['productname']); ?>">
                    <input type="hidden" name="details" value="<?php echo htmlspecialchars($product['details']); ?>">
                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <div class="qty">
                        <label for="quantity">Quantity:</label>
                        <input type="number" value="1" min="1" max="<?php echo htmlspecialchars($product['qty']); ?>" name="quantity">
                    </div>
                    <div class="details-container">
                        <br><p><b>Details:</b> <?php echo htmlspecialchars($product['details']); ?></p>
                    </div>
                    <div class="productbtns">
                        <input type="submit" value="Add to Cart" class="btn" name="btn">
                    </div>
                </form>
            </div>
            <?php endforeach; ?>
        </section>
    </div>
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