<?php
session_start();

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
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']); 

    if (!empty($new_username) && !empty($new_email) && !empty($new_phone)) {
        $sql = "UPDATE user SET username = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $new_username, $new_email, $new_phone, $user_id);

        if ($stmt->execute()) {
            $message = "<p class='success'>Profile updated successfully.</p>";
        } else {
            $message = "<p class='error'>Error updating profile. Please try again.</p>";
        }

        $stmt->close();
    } else {
        $message = "<p class='error'>All fields are required.</p>";
    }
}

$sql = "SELECT username, email, phone FROM user WHERE id = ?";
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
    <title>User Settings - Library Management System</title>
    <link rel="stylesheet" href="../assests/css/admindb_style.css">
    <link rel="stylesheet" href="../assests/css/profile.css">
</head>
<body>
    <div class="navbar">
        <div><a href="user_dashboard.php">Library Management System</a></div>
        <div>
            <span class="welcome">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
            <a href="user_dashboard.php">Back</a>
        </div>
    </div>

    <div class="container">
        <h1>User Settings</h1>
        <div class="profile-card">
            <?php echo $message; ?>
            <form action="usersetting.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label><br>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>
