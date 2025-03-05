<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: Admin.php");
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


$sql = "SELECT issued_books.id, issued_books.book_title, issued_books.issued_to, issued_books.due_date, DATEDIFF(CURDATE(), issued_books.due_date) AS overdue_days
        FROM issued_books
        WHERE DATEDIFF(CURDATE(), issued_books.due_date) > 0 AND issued_books.return_date IS NULL";
$result = $conn->query($sql);


$username = $_SESSION['username'];


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fines - Library Management System</title>
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
        <h2>Manage Fines</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Book Title</th>
                    <th>Issued To</th>
                    <th>Due Date</th>
                    <th>Overdue Days</th>
                    <th>Fine Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            
                            $fine_per_day = 20;
                            $fine_amount = $row['overdue_days'] * $fine_per_day;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['issued_to']); ?></td>
                            <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['overdue_days']); ?> days</td>
                            <td><?php echo 'NPR' . number_format($fine_amount, 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No overdue fines to display.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
