<?php
session_start(); // Start the session
$userid = $_SESSION['userId'];
$username = $_SESSION['username'];

$servername = "localhost";
$dbUsername = "root"; // Default MySQL username
$dbPassword = ""; // Default MySQL password
$database = "SupermarketDb";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

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

// Get the category or search input from the request
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$searchInput = isset($_GET['search']) ? $_GET['search'] : '';

// Build SQL query based on the category filter or search input
if ($categoryFilter === 'all' || $categoryFilter === '') {
    $sql = "SELECT productname, category, details, pic, qty, price FROM products";
} else {
    $sql = "SELECT productname, category, details, pic, qty, price FROM products WHERE category LIKE ? OR productname LIKE ? OR details LIKE ?";
    $searchParam = '%' . $categoryFilter . '%';
}

$stmt = $conn->prepare($sql);

if ($categoryFilter !== 'all' && $categoryFilter !== '') {
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
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

if (isset($_GET['ajax'])) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Container for the products */
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Space between the products */
            justify-content: space-between;
        }

        /* Product styles */
        .product {
            width: calc(33.33% - 20px); /* Adjust width to fit three products per row, taking margin into account */
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        /* Product image styling */
        .product img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        /* Other product styles */
        .price {
            font-size: 1.2em;
            font-weight: bold;
        }

        .details-container {
            margin-top: 10px;
        }

        .productbtns .btn {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

    </style>
</head>
<body>
    <div class="container">
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
    </div>
</body>
</html>
<?php } ?>
