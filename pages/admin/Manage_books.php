<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: Admin.php");
    exit();
}

$host = "localhost";
$dbname = "library";
$username = "root"; 
$password = ""; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT id, title, author, genre, year FROM books");
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['id'])) {
        $book_id = $_GET['id'];
        $delete_stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
        $delete_stmt->bindParam(':id', $book_id);
        if ($delete_stmt->execute()) {
            $message = "Book deleted successfully.";
        } else {
            $message = "Failed to delete the book.";
        }

        echo "<script>
                alert('$message');
                window.location.href = 'Manage_books.php';
              </script>";
        exit();
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link rel="stylesheet" href="../../assests/css/admindb_style.css">
    <link rel="stylesheet" href="../../assests/css/manage_user.css">
</head>
<body>
    <div class="navbar">
        <div><a href="admin_dashboard.php">Library Management System</a></div>
        <div>
            <a href="profile.php">Profile</a>
            <a href="admin_settings.php">Settings</a>
            <a href="admin_dashboard.php">Back</a>
        </div>
    </div>

    <div class="container">
        <h2>All Books</h2>
        <?php if (!empty($books)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Year</th>
                        <th>Actions</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['id']); ?></td>
                            <td><?= htmlspecialchars($book['title']); ?></td>
                            <td><?= htmlspecialchars($book['author']); ?></td>
                            <td><?= htmlspecialchars($book['genre']); ?></td>
                            <td><?= htmlspecialchars($book['year']); ?></td>
                            <td>
                                <a href="Manage_books.php?id=<?= $book['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No books found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
