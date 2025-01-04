<?php
session_start();
$userid = $_SESSION['userId'];
$username = $_SESSION['username'];

// Initialize the cart array in the session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check if form data has been sent
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['clear_cart'])) {
        // Clear the cart
        $_SESSION['cart'] = array();
        header('Location: cart.php'); // Redirect to avoid form resubmission
        exit;
    } elseif (isset($_POST['checkout'])) {
        // Check if the cart is empty
        if (empty($_SESSION['cart'])) { 
            // Display empty cart message
            $emptyCartMessage = "Your cart is empty. Please add items to your cart before checking out.";
        } else {
            // Process checkout
            $user_id = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
            $products_details = "";
            $total_price = 0;

            foreach ($_SESSION['cart'] as $item) {
                $products_details .= $item['productname'] . " - " . $item['details'] . " (Qty: " . $item['quantity'] . "), ";
            }
            $total_price = $_SESSION['total'];

            // Create connection
            $servername = "localhost";
            $db_username = "root"; // Default MySQL username
            $db_password = ""; // Default MySQL password
            $conn = new mysqli($servername, $db_username, $db_password);

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
            }

            $sql = "INSERT INTO orders (orderDetails, totalprice, userId) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sdi', $products_details, $total_price, $user_id);

            if (!$stmt->execute()) {
                // Handle the case where the execution was not successful
                echo "Error executing the query: " . $stmt->error;
            } else {
                foreach ($_SESSION['cart'] as $item) {
                    $product_name = $item['productname'];
                    $quantity_purchased = $item['quantity'];

                    // Update the quantity
                    $update_sql = "UPDATE products SET qty = qty - ? WHERE productname = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param('is', $quantity_purchased, $product_name);
                    $update_stmt->execute();

                    // Check if the product quantity is zero and remove it
                    $check_sql = "SELECT qty FROM products WHERE productname = ?";
                    $check_stmt = $conn->prepare($check_sql);
                    $check_stmt->bind_param('s', $product_name);
                    $check_stmt->execute();
                    $check_stmt->bind_result($current_qty);
                    $check_stmt->fetch();
                    $check_stmt->close();

                    // Remove the product if its quantity is zero
                    if ($current_qty <= 0) {
                        $delete_sql = "DELETE FROM products WHERE productname = ?";
                        $delete_stmt = $conn->prepare($delete_sql);
                        $delete_stmt->bind_param('s', $product_name);
                        $delete_stmt->execute();
                        $delete_stmt->close();
                    }
                }
            }

            $stmt->close();
            $conn->close();
            unset($_SESSION['cart']);
            header('Location: previous_order.php'); // Redirect to view all previous orders
            exit;
        }
    } else {
        // Retrieve product details from the form submission
        $productname = $_POST['productname'];
        $details = $_POST['details'];
        $quantity = intval($_POST['quantity']);
        $price = floatval($_POST['price']);
        $total_price = $price * $quantity;

        // Check if the product already exists in the cart
        $product_exists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['productname'] == $productname && $item['details'] == $details) {
                // Update the quantity and total price
                $item['quantity'] += $quantity;
                $item['total_price'] += $total_price;
                $product_exists = true;
                break;
            }
        }
        unset($item);

        // If the product does not exist, add it to the cart
        if (!$product_exists) {
            $_SESSION['cart'][] = array(
                'productname' => $productname,
                'details' => $details,
                'quantity' => $quantity,
                'total_price' => $total_price
            );
        }

        // Redirect to avoid form resubmission
        header('Location: customer_dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="images/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
    .error_message {
        transition: opacity 1s; /* Smooth transition over 1 second */
    }
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
    .button-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-top: 20px;
    }
    </style>
</head>
<body>
    <header style="background-color: #333">
        <div class="navbar">
            <nav>
                <a class="custom-button" href="signin.php">Logout</a>
                <a class="custom-button" href="customer_dashboard.php">Back to shopping</a>
            </nav>
        </div>
    </header>
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Details</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) :
                    $total = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $key => $item) :
                        $total += $item['total_price']; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['productname']); ?></td>
                            <td><?php echo htmlspecialchars($item['details']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($item['total_price']); ?></td>
                            <td>
                                <form method="post" action="remove.php">
                                    <input type="hidden" name="remove_key" value="<?php echo $key; ?>">
                                    <button type="submit" name="remove" class="custom-button"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">Total <b>(with 15% VAT)</b>:</td>
                        <td colspan="2"><b>BHD <?php echo round($total + $total * 0.15, 3); ?></b></td>
                        <?php $_SESSION['total'] = round($total + $total * 0.15, 3); ?>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td colspan="5">Your cart is empty.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (isset($emptyCartMessage)) : ?>
            <div id="error">
                <div class="message">
                    <h3 style="color: #a72a2a; font-family: 'Courier New', Courier, monospace; font-weight: 300;">
                        <p class="error_message"><?php echo $emptyCartMessage; ?></p>
                    </h3>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="button-container">
                <a class="custom-button" href="customer_dashboard.php">Continue Shopping</a>
                <form action="cart.php" method="post">
                    <button class="custom-button" type="submit" name="checkout">Checkout</button>
                </form>
                <form action="cart.php" method="post">
                    <button class="custom-button" type="submit" name="clear_cart">Clear Cart</button>
                </form>
            </div>
        </div>
    </div>

    <div style="height:300px"></div>

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
            <p>Copyright &copy;2024 Designed by <span class="designer">Abdul Qadeer</span></p>
        </div>
    </footer>
</body>
</html>

