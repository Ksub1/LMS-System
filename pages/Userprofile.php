<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
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

$user_id = $_SESSION['user_id'];
$sql = "SELECT id, username, email, phone FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Library Management System</title>
    <link rel="stylesheet" href="../assests/css/admindb_style.css">
    <link rel="stylesheet" href="../assests/css/profile.css">
</head>
<body>
    <div class="navbar">
        <div><a href="user_dashboard.php">Library Management System</a></div>
        <div>
            <span class="welcome">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
            <a href="usersetting.php">Settings</a>
            <a href="user_dashboard.php">Back</a>
        </div>
    </div>

    <div class="container">
        <h1>User Profile</h1>

        <div class="profile-card">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        </div>
    </div>
</body>
</html>
