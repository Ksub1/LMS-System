<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: Admin.php");
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

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $year = intval($_POST['year']);

    if (!empty($title) && !empty($author) && !empty($genre) && !empty($year)) {
        $sql = "INSERT INTO books (title, author, genre, year) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $author, $genre, $year);

        if ($stmt->execute()) {
            $message = "Book added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "All fields are required!";
    }
}

$username = $_SESSION['username'];

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="../../assests/css/add_user.css">      
</head>
<body>
   
     <div class="navbar">
        <div><a href="admin_dashboard.php">Library Management System</a></div>
        <div>
        <span class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</span>

            <a href="profile.php">Profile</a>
            <a href="admin_settings.php">Settings</a>
            <a href="admin_dashboard.php">Back</a>
        </div>
        
    </div>
    <div class="container">
        <h1>Add a New Book</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" required>
            </div>
            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" name="genre" id="genre" required>
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <input type="number" name="year" id="year" required>
            </div>
            <div class="form-group">
                <button type="submit">Add Book</button>
            </div>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
