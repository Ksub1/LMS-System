<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: admin.php");
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


$books = [];
$sql = "SELECT id, book_title, issued_to FROM issued_books WHERE return_date IS NULL";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

// Handle return book form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['book_id']);
    $returnDate = $_POST['return_date']; 

    if (!empty($bookId) && !empty($returnDate)) {
        $sql = "UPDATE issued_books SET return_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $returnDate, $bookId);

        if ($stmt->execute()) {
            $message = "Book returned successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Please select a book and a return date.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
    <link rel="stylesheet" href="../../assests/css/add_user.css">
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
        <h1>Return a Book</h1>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="book_id">Select Book</label>
                <select name="book_id" id="book_id" required>
                    <option value="">Select a Book</option>
                    <?php foreach ($books as $book): ?>
                        <option value="<?php echo htmlspecialchars($book['id']); ?>">
                            <?php echo htmlspecialchars($book['book_title']) . " (Issued to: " . htmlspecialchars($book['issued_to']) . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="return_date">Return Date</label>
                <input type="date" name="return_date" id="return_date" required>
            </div>

            <button type="submit">Return Book</button>
        </form>
    </div>
</body>
</html>
