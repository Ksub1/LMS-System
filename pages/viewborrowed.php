<?php
session_start();
if (!isset($_SESSION['username'])) { // Ensure user is logged in
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user's username
$username = $_SESSION['username']; 

// Query to get books issued to the logged-in user
$sql = "SELECT id, book_title, issued_date, due_date, return_date, fine_amount 
        FROM issued_books 
        WHERE issued_to = ? 
        ORDER BY issued_date DESC"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$borrowed_books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $borrowed_books[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Borrowed Books</title>
    <link rel="stylesheet" href="../assests/css/admindb_style.css">
    <link rel="stylesheet" href="../assests/css/manage_user.css">
</head>
<body>
    <div class="navbar">
        <div><a href="user_dashboard.php">Library Management System</a></div>
        <div>
            <span class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
            <a href="profile.php">Profile</a>
            <a href="user_dashboard.php">Back</a>
        </div>
    </div>

    <div class="container">
        <h2>Your Borrowed Books</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Book Title</th>
                    <th>Issued Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Fine Amount (NRP)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($borrowed_books) > 0): ?>
                    <?php foreach ($borrowed_books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['id']); ?></td>
                            <td><?php echo htmlspecialchars($book['book_title']); ?></td>
                            <td><?php echo htmlspecialchars($book['issued_date']); ?></td>
                            <td><?php echo htmlspecialchars($book['due_date']); ?></td>
                            <td><?php echo $book['return_date'] ? htmlspecialchars($book['return_date']) : "Not Returned"; ?></td>
                            <td><?php echo "NRP" . number_format($book['fine_amount'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No books borrowed yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
