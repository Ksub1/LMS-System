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


$sql = "SELECT id, username, email, role FROM user ORDER BY role, username";
$result = $conn->query($sql);


$username = $_SESSION['username'];


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users - Library Management System</title>
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
        <h2>View Users</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
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
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
