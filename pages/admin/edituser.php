<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: admin.php");
    exit();
}
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'library';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
if (!isset($_GET['user_id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = intval($_GET['user_id']);
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_role = trim($_POST['role']);

    if (!empty($new_username) && !empty($new_email) && !empty($new_role)) {
        $sql = "UPDATE user SET username = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $new_username, $new_email, $new_role, $user_id);

        if ($stmt->execute()) {
            $message = "<p class='success'>User details updated successfully.</p>";
        } else {
            $message = "<p class='error'>Failed to update user details. Please try again.</p>";
        }

        $stmt->close();
    } else {
        $message = "<p class='error'>All fields are required.</p>";
    }
}
$username = $_SESSION['username'];


$sql = "SELECT username, email, role FROM user WHERE id = ?";
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
    <title>Edit User - Library Management System</title>
    <link rel="stylesheet" href="../../assests/css/admindb_style.css">
    <link rel="stylesheet" href="../../assests/css/profile.css">
</head>
<body>
    <div class="navbar">
        <div><a href="admin_dashboard.php">Library Management System</a></div>
        <div>
        <span class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
            <a href="manage_user.php">Back</a>
        </div>
    </div>

    <div class="container">
        <h2>Edit User</h2>
        <?php echo $message; ?>
        <?php if ($user): ?>
            <form action="edituser.php?user_id=<?php echo htmlspecialchars($user_id); ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="User" <?php if ($user['role'] === 'User') echo 'selected'; ?>>User</option>
                        <option value="Admin" <?php if ($user['role'] === 'Admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-submit">Update User</button>
            </form>
        <?php else: ?>
            <p class="error">User not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
