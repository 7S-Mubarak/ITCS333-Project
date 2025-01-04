<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="style.css">
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
        <h2 style="text-align:center;" >Delete Staff</h2><br>
        <div style="text-align:center;" class="button">
        <form action="" method="post" enctype="multipart/form-data">
            <label style="font-size:20px; margin-right:20px"  for="email">Email:</label>
            <input class="email ele" type="email" id="email" name="email" placeholder="youremail@gmail.com" required></input><br><br>

            <input class="clkbtn" type="submit" value="Submit" name="btn">
        </form>
        </div>
        <?php
        //start the session 
        session_start();
        if(isset($_POST['btn'])){
            $email=$_POST['email'];

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
            // check if the user exists...
                $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->bind_result($usercount);
                $stmt->fetch();
                $stmt->close();

                // If user exist, update it
                if ($usercount > 0) {
                    $sql = "DELETE FROM users WHERE email = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $email);
                    if (!$stmt->execute()) {
                        // Handle the case where the execution was not successful
                        echo "Error executing the query: " . $stmt->error;}
                    else{
                        ?><div class="success">
                            <p>User deleted successfully!</p>
                        </div>
                    <?php }
                }
                // If user doesn't exists
                else {
                    echo"
                    <div>
                        <h2>user doesnt exist!</h2>
                    </div>   " ;
                }
                $conn->close();
        }?>
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