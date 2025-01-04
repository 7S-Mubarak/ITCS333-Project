<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="images/logo.png">
    <title>Supermarket Home</title>
    <style>
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
            <div class="logo">  
                <span>
                    <img style="width:300px; height:100px;" src="images/logo2.png"/>
                </span>
            </div>
            <nav>
                <a class="custom-button" href="signin.php">Signin</a>
            </nav>
        </div>
    </header>
    <main style="min-width:700px; border-radius:20px; background-color:#F5FFFA" class="container">
        <div style="text-align:center;"class="button">
        <h2 style="text-align:center;" >Add Staff</h2><br>
        <form action="" method="post" enctype="multipart/form-data">
            <label style="font-size:20px; margin-right:20px" for="product_name">Username:</label>
            <input class="name ele" type="text" id ="product_name" name="user_name" placeholder="Enter username" required><br><br>

            <label style="font-size:20px; margin-right:20px" for="email">Email:</label>
            <input class="email ele" type="email" id="email" name="email" placeholder="youremail@gmail.com" required></input><br><br>

            <label style="font-size:20px; margin-right:20px" for="password">password:</label>
            <input class="password ele" type="text" id="password" name="password" placeholder="Enter password" required><br><br>


            <input style="font-size:20px;" class="clkbtn" type="submit" value="Submit" name="btn">
        </form>
        </div>
        <?php
        //start the session 
        session_start();
        if(isset($_POST['btn'])){
            $name=$_POST['user_name'];
            $email=$_POST['email'];
            $user_password=$_POST['password'];
            $role='staff';
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
                $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->bind_result($usercount);
                $stmt->fetch();
                $stmt->close();
                // If product doesn't exist, insert it
                if ($usercount == 0) {
                    $sql = "INSERT INTO users (email, username,password,  role) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssss', $email, $name, $user_password, $role);
                    if (!$stmt->execute()) {
                        // Handle the case where the execution was not successful
                        echo "Error executing the query: " . $stmt->error;}
                    else{
                        ?><div class="success">
                            <h3 class="conform"><b>User added successfully!</b></>
                        </div>
                    <?php }
                }
                // If product exists
                elseif ($usercount > 0) {
                    ?>
                    <div class="conform"  >
                        <h3 class="found">User already exists</h3>
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