<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
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
$sql = "SELECT id, title, author, genre, year FROM books";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link rel="stylesheet" href="../assests/css/browsebook.css">
</head>
<body>
<div class="navbar">
        <div><a href="user_dashboard.php">Library Management System</a></div>
        <div>
            <a href="user_dashboard.php">Back</a>
        </div>
    </div>
    <div class="container">
        <h1>Browse Books</h1>
        <ul class="book-list">
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <li class="book-item">
                        <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
                        <div class="book-details">
                            Author: <?php echo htmlspecialchars($book['author']); ?> | 
                            Genre: <?php echo htmlspecialchars($book['genre']); ?> | 
                            Year: <?php echo htmlspecialchars($book['year']); ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No books found.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>