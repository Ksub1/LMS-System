<?php
session_start(); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


    $conn = new mysqli('localhost','root', '', 'library'); 
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE email = ?");
    if (!$stmt) {
        die('Prepare Failed: ' . $conn->error);
    }
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $stmt->store_result(); 
    $stmt->bind_result($id, $username, $hashed_password); 

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            header("Location: user_dashboard.php"); 
            exit();
        } else {
        
            $error_message = "Incorrect password!";
        }
    } else {
      
        $error_message = "No user found with this email address.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management System</title>

    <link rel="stylesheet" href="../assests/css/index_style.css">
</head>
<body>
    <div class="navbar">
        <div><a href="index.php">Library Management System</a></div>
        <div>
            <a href="../pages/admin/Admin.php">Admin Login</a>
            <a href="signup.php">Signup</a>
        </div>
    </div>

    <div class="container">
       
        <div class="sidebar">
            <h2>Library Timing</h2>
            <p>Opening: 7:00 AM</p>
            <p>Closing: 4:00 PM</p>
            <br>
            <h2>What We Provide?</h2>
            <ul>
                <li>AC Rooms</li>
                <li>Free Wi-Fi</li>
                <li>Learning Environment</li>
                <li>Discussion Room</li>
                <li>Free Electricity</li>
            </ul>
        </div>

     
        <div class="login-form">
            <h2>User Login</h2>
            <form action="" method="post">
                <input type="text" name="email" placeholder="👤Email ID" required>
                <input type="password" name="password" placeholder="🔒Password" required>
                <button type="submit">Login</button>
            </form>

            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <p><a href="signup.php">Signup now !!</a></p>
        </div>
    </div>

</body>
</html>
