<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Library Management System</title>
   
    <link rel="stylesheet" href="../assests/css/admindb_style.css">
       
</head>
<body>
    <div class="navbar">
        <div><a href="user_dashboard.php">Library Management System</a></div>
        <div>
            <span class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
            <a href="Userprofile.php">Profile</a>
            <a href="usersetting.php">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

  
    <div class="container">
        <div class="sidebar">
            <h2>Library Services</h2>
            <ul>
                <li>📚 Browse Books</li>
                <li>📅 Check Due Dates</li>
                <li>🔄 Renew Books</li>
            </ul>
        </div>

        <div class="main-content">
            <h2>User Dashboard</h2>
            <p>Welcome to the Library Management System. Explore, reserve, and manage your library resources here.</p>
            <div class="actions">
                <div class="action-card">
                    <a href="browse_books.php">📚 Browse Books</a>
                </div>
                <div class="action-card">
                    <a href="viewborrowed.php">📋 View Borrowed Books</a>
                </div>
                <div class="action-card">
                    <a href="renewbook.php">🔄 Renew Book</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>