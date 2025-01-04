<?php
    session_start(); 
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
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // gather the form data
        $name = $_SESSION['pname'];
        $details = $_SESSION['pdetails'];
        $image = $_SESSION['img'];
        $quantity = $_SESSION['qty'];
        $price = $_SESSION['price'];

        if (!empty($_POST['action'])) {
            if ($_POST['action'] == 'yes') {
                // Construct the SQL query to update the product information
                $sql = "UPDATE products SET details = ?, pic = ?, qty = ?, price = ? WHERE productname = ?";
                // Prepare the SQL statement
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    echo "Error preparing statement: " . $conn->error;
                } else {
                    // Bind parameters
                    $stmt->bind_param('sssss', $details, $image, $quantity, $price, $name);
                    // Execute the update query
                    if ($stmt->execute()) {
                        echo '<div class="success"><p>Product info replaced successfully!</p></div>';
                    } else {
                        echo "Error updating product information: " . $stmt->error;
                    }
                    $stmt->close(); // Close the statement
                }
            } else if ($_POST['action'] == 'no') {
                echo '<div class="success"><p>No changes made.</p></div>';
            }
        }
        else{
            echo "<br>action not found";
        }
        $conn->close();
    }
?>