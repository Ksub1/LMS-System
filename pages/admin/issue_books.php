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

$books = [];
$users = [];

$booksQuery = "SELECT title FROM books";
$usersQuery = "SELECT username FROM user";

$booksResult = $conn->query($booksQuery);
$usersResult = $conn->query($usersQuery);

if ($booksResult && $booksResult->num_rows > 0) {
    while ($row = $booksResult->fetch_assoc()) {
        $books[] = $row['title'];
    }
}

if ($usersResult && $usersResult->num_rows > 0) {
    while ($row = $usersResult->fetch_assoc()) {
        $users[] = $row['username'];
    }
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookTitle = $_POST['book_title'];
    $issuedTo = $_POST['issued_to'];
    $dueDate = $_POST['due_date'];

    // Validate inputs
    if (!empty($bookTitle) && !empty($issuedTo) && !empty($dueDate)) {
        $issuedDate = date('Y-m-d'); // Current date
        $sql = "INSERT INTO issued_books (book_title, issued_to, issued_date, due_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $bookTitle, $issuedTo, $issuedDate, $dueDate);

        if ($stmt->execute()) {
            $message = "Book issued successfully!";
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
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Book</title>
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
        <h1>Issue a Book</h1>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="book_title">Book Title</label>
                <select name="book_title" id="book_title" required>
                    <option value="">Select a Book</option>
                    <?php foreach ($books as $book): ?>
                        <option value="<?php echo htmlspecialchars($book); ?>"><?php echo htmlspecialchars($book); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="issued_to">Issued To</label>
                <select name="issued_to" id="issued_to" required>
                    <option value="">Select a User</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo htmlspecialchars($user); ?>"><?php echo htmlspecialchars($user); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date" required>
            </div>
            <button type="submit">Issue Book</button>
        </form>
    </div>
    
</body>
</html>
