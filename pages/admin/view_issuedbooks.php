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

// Handle book return (if Return button is clicked)
if (isset($_POST['return'])) {
    $book_id = $_POST['book_id'];
    
    // Update the book to mark it as returned (set return_date)
    $sql = "UPDATE issued_books SET return_date = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    
    if ($stmt->execute()) {
      
        echo "Book returned successfully!";
    } else {
        echo "Error returning the book: " . $stmt->error;
    }
    $stmt->close();
}

$sql = "SELECT id, book_title, issued_to, issued_date, due_date, return_date FROM issued_books WHERE return_date IS NULL ORDER BY issued_date DESC";
$result = $conn->query($sql);
$username = $_SESSION['username'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Books - Library Management System</title>
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
        <h2>Issued Books</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Book Title</th>
                    <th>Issued To</th>
                    <th>Issued Date</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['issued_to']); ?></td>
                            <td><?php echo htmlspecialchars($row['issued_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No issued books found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
