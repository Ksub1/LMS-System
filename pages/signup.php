
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Library Management System</title>
   
      
    <link rel="stylesheet" href="../assests/css/signup_style.css"> 
</head>
<body>
    <div class="navbar">
        <div><a href="index.php">Library Management System</a></div>
        <div>
            <a href="index.php">User Login</a>
            <a href="../pages/admin/Admin.php">Admin Login</a>
        </div>
    </div>

    <div class="container">
    <div class="signup-form">
    <h2>Create an Account</h2>
    <form action="../register.php" method="post" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email ID" required>
        <input type="tel" name="phone" placeholder="Phone Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Sign Up</button>
    </form>
    <p><a href="index.php">U have already registered?</a></p>
</div> 
</body>
</html>