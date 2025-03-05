<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die('Error: Passwords do not match.');
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'library');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $profile_picture = "uploads/default.png";

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_size = $_FILES['profile_picture']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_extensions)) {
            if ($file_size <= 5000000) { 
                $new_file_name = "uploads/" . uniqid() . "." . $file_ext;
                move_uploaded_file($file_tmp, $new_file_name);
                $profile_picture = $new_file_name;
            } else {
                die('Error: Image file is too large (Max 5MB).');
            }
        } else {
            die('Error: Invalid file format. Only JPG, JPEG, PNG, and GIF allowed.');
        }
    }
    $stmt = $conn->prepare("INSERT INTO user (username, email, phone, password, profile_picture) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare Failed: ' . $conn->error);
    }

    $stmt->bind_param("sssss", $username, $email, $phone, $hashed_password, $profile_picture);
    
    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: pages/success.php"); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: pages/signup.php"); 
    exit();
}
?>
