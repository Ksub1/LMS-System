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

// Fetch all users
$sql = "SELECT id, username, email, role FROM user ORDER BY role, username";
$result = $conn->query($sql);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    if ($user_id == $_SESSION['user_id']) {
        $message = "You cannot delete your own account.";
    } else {
        $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $message = "User deleted successfully.";
        } else {
            $message = "Failed to delete the user.";
        }

        $stmt->close();
    }
    echo "<script>
        alert('$message');
        window.location.href = 'manage_user.php';
    </script>";
    exit();
}

$username = $_SESSION['username'];
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Library Management System</title>
    <link rel="stylesheet" href="../../assests/css/admindb_style.css">
    <link rel="stylesheet" href="../../assests/css/manage_user.css">
</head>
<body>
    <div class="navbar">
        <div><a href="admin_dashboard.php">Library Management System</a></div>
        <div>
            <span class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
            <a href="profile.php">Profile</a>
            <a href="admin_dashboard.php">Back</a>
        </div>
    </div>

    <div class="container">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                            <td>
                             
                                <form action="edituser.php" method="get" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-edit">Edit</button>
                                </form>
                                <form action="" method="post" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-delete" 
                                        <?php if ($row['id'] == $_SESSION['user_id']) echo 'disabled'; ?>
                                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
