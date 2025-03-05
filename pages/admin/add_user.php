<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: Admin.php");
    exit();
}

$servername = "localhost"; 
$username = "root";         
$password = "";             
$dbname = "library"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role = $_POST['role']; 
    
    $sql = "INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            $success_message = "User added successfully!";
        } else {
            $error_message = "Error adding user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Database error: " . $conn->error;
    }
    $conn->close();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="../../assests/css/add_user.css"> 
</head>
<body>
    <div class="navbar">
        <div><a href="admin_dashboard.php">Library Management System</a></div>
        <div>
        <span class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</span>

            <a href="profile.php">Profile</a>
            <a href="admin_settings.php">Settings</a>
            <a href="admin_dashboard.php">Back</a>
        </div>
</div>
    <div class="container">
        <h2>Add New User</h2>
        <?php if (isset($success_message)): ?>
            <div class="success"><?= $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="error"><?= $error_message; ?></div>
        <?php endif; ?>

       
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <button type="submit">Add User</button>
        </form>
    </div>
  </body>
</html>
