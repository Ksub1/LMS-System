<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];

    $sql = "SELECT id, return_date, DATEDIFF(CURDATE(), return_date) AS overdue_days 
            FROM issued_books 
            WHERE issued_to = ? AND id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $username, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        $overdue_days = $book['overdue_days'];

    
        $fine_per_day = 20; 
        $fine_amount = ($overdue_days > 0) ? ($overdue_days * $fine_per_day) : 0;

      
        $new_return_date = date('Y-m-d', strtotime('+7 days'));

        $update_sql = "UPDATE issued_books SET return_date = ?, fine_amount = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sdi", $new_return_date, $fine_amount, $book_id);

        if ($update_stmt->execute()) {
            $message = "<p class='success'>Book renewed successfully. New return date: $new_return_date. Fine: $$fine_amount</p>";
        } else {
            $message = "<p class='error'>Error renewing book. Please try again.</p>";
        }
        $update_stmt->close();
    } else {
        $message = "<p class='error'>No valid book found to renew.</p>";
    }
    
    $stmt->close();
}


$sql_books = "SELECT ib.id, ib.book_title, ib.return_date, ib.due_date, ib.fine_amount 
              FROM issued_books ib 
              WHERE ib.issued_to = ?";
$stmt_books = $conn->prepare($sql_books);
$stmt_books->bind_param("s", $username);
$stmt_books->execute();
$result_books = $stmt_books->get_result();

$borrowed_books = [];
if ($result_books->num_rows > 0) {
    while ($row = $result_books->fetch_assoc()) {
        $borrowed_books[] = $row;
    }
}

$stmt_books->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renew Book - Library Management System</title>
    <link rel="stylesheet" href="../assests/css/browsebook.css">
</head>
<body>
    <div class="navbar">
        <div><a href="user_dashboard.php">Library Management System</a></div>
        <div><a href="user_dashboard.php">Back</a></div>
    </div>

    <div class="container">
        <h1>Renew Book</h1>
        <?php echo $message; ?>

        <h2>Borrowed Books</h2>
        <?php if (count($borrowed_books) > 0): ?>
            <form action="renewbook.php" method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Fine Amount</th>
                            <th>Renew</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($borrowed_books as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['book_title']); ?></td>
                                <td><?php echo htmlspecialchars($book['due_date']); ?></td>
                                <td><?php echo htmlspecialchars($book['return_date']); ?></td>
                                <td><?php echo ($book['fine_amount'] > 0) ? "NRP" . $book['fine_amount'] : "No Fine"; ?></td>
                                <td>
                                    <button type="submit" name="book_id" value="<?php echo $book['id']; ?>">Renew</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?php else: ?>
            <p>No books available for renewal.</p>
        <?php endif; ?>
    </div>
</body>
</html>
