<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="style.css">
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
    </style>
</head>

<body>

    <header style="background-color: #333">
        <div class="navbar">
            <div class="logo">
                <span>
                    <img style="width:300px; height:60px;" src="images/logo2.png"/>
                </span>
            </div>
            <nav>
                <a class="custom-button" href="signin.php" style="text-decoration:none; margin-left:1320px;">Logout</a>
            </nav>
            <nav>
                <a style="text-decoration:none" class="custom-button" href="customer_dashboard.php">Back to shopping</a>
            </nav>
        </div>
    </header>
    <main class="container" style="border-radius:20px; background-color:#F5FFFA">
        <!-- Add  form -->
        <?php
        //start the session 
        session_start();

        $userid=$_SESSION['userId'];
        $username=$_SESSION['username'];
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
        // If the database doesn't exist, create it
        if (!$db_selected) {
            $db_create_query = "CREATE DATABASE IF NOT EXISTS $db";
            if ($conn->query($db_create_query) === false) {
                die ("Error creating database: " . $conn->error) ;
            }
        }
        // Prepare a SQL statement with a placeholder for the data
        $sql = "SELECT orderId, orderDetails, status, totalprice FROM orders WHERE userId = $userid";
        $stmt = $conn->prepare($sql);
        // Check if the statement was prepared successfully
        if ($stmt) {
            // Execute the statement
            $stmt->execute();
            // Bind the results to variables
            $stmt->bind_result($orderId, $orderDetails, $status, $totalprice);
            // Fetch the results
            $orders = array();
            while ($stmt->fetch()) {
                // Create an associative array to store the fetched order data
                $order = array(
                    'orderId' => $orderId,
                    'orderDetails' => $orderDetails,
                    'status' => $status,
                    'totalprice' => $totalprice
                );
                // Append the order to the $orders array
                $orders[] = $order;
            }
        }?>
        <h2 style="text-align: center;"> My Orders </h2> <br>
        <table class="table table-striped" width="100%"  >
            <tr class="thead-dark"> 
                <th>Order Id</th>
                <th>Order Details</th>
                <th>Order Status</th>
                <th>Total Price</th>
            </tr>
            <?php 
                foreach ($orders as $order){
                    $orderId = $order['orderId'];
                    $orderDetails = $order['orderDetails'];
                    $status = $order['status'];
                    $totalPrice = $order['totalprice'];
                    echo "
                        <tr>
                        <tr >
                        <td>$orderId</td>
                        <td>$orderDetails</td>
                        <td>$status</td>
                        <td>BHD $totalPrice</td>
                        </tr>
                    ";
                }    
            ?>
        </table>
            <?php 
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Check if a status has been selected and submitted
                    if (isset($_POST['order_status']) && in_array($_POST['order_status'], ['acknowledged', 'in process', 'completed'])) {
                        // Update the status in the database
                        $newStatus = $_POST['order_status'];
                        // order id
                        $orderId = $_POST['orderId'];
                        // Update the status in the database using a prepared statement
                        $sql = "UPDATE orders SET status = ? WHERE orderId = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("si", $newStatus, $orderId); 
                        if ($stmt->execute()) {
                            echo "Status updated successfully";
                        } else {
                            echo "Error updating status: " . $conn->error;
                        }
                        $stmt->close();
                    }
                }
            $conn->close(); ?>
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
            <p>Copyright &copy;2024 Designed by <span class="designer">Abdul Qadeer</span></p>
        </div>
    </footer>
    
</body>
</html>