<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'library'; 


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


$error = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

   
    $stmt = $conn->prepare("SELECT id, username, password, role FROM user WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
       
        $user = $result->fetch_assoc();

        
        if (password_verify($user_password, $user['password'])) {
        
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: admin_dashboard.php"); 
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password or user is not an admin.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Library Management System</title>
    <link rel="stylesheet" href="../../assests/css/admin_style.css">
</head>
<body>
    <div class="navbar">
        <div><a href="../index.php">Library Management System</a></div>
        <div>
            <a href="../index.php">Home</a>
            <a href="../signup.php">Signup</a>
        </div>
    </div>
    <div class="container">
        <div class="admin-form">
            <h2>Admin Login</h2>
            <?php if ($error): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <input type="text" name="user_email" placeholder=" 👤Email ID" required>
                <input type="password" name="user_password" placeholder="🔒Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
