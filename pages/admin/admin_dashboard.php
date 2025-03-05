<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Admin.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Library Management System</title>
    <link rel="stylesheet" href="../../assests/css/admindb_style.css">
</head>
<body>
   
    <div class="navbar">
        <div><a href="admin_dashboard.php">Library Management System</a></div>
        <div>
        <span class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</span>

            <a href="profile.php">Profile</a>
            <a href="admin_settings.php">Settings</a>
            <a href="admin_logout.php">Logout</a>
        </div>
    </div>

    
    <div class="container">
      
        <div class="sidebar">
            <h2>Admin Menu</h2>
            <ul>
                <li><a href="manage_books.php">📚 Managing  Books</a></li>
                <li><a href="manage_user.php">👥 Manage Users</a></li>
                <li><a href="view_issuedbooks.php">📝 View Issued Books</a></li>
                <li><a href="manage_fines.php">💰 Manage Fines</a></li>
                
            </ul>
        </div>
        <div class="main-content">
           
            <p>Welcome to the Library Management System Admin Dashboard. Use the menu or quick actions below to manage library resources.</p>
            <div class="actions">
                <div class="action-card">
                    <a href="add_book.php">📖 Add New Book</a>
                </div>
                <div class="action-card">
                    <a href="view_books.php">📚 View Books</a>
                </div>
                <div class="action-card">
                    <a href="add_user.php">👤 Add New User</a>
                </div>
                <div class="action-card">
                    <a href="view_user.php">👥 View All Users</a>
                </div>
                <div class="action-card">
                    <a href="issue_books.php">📝 Issue Book</a>
                </div>
                <div class="action-card">
                    <a href="return_book.php">🔄 Return Book</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
