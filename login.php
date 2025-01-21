<?php
// Include the database configuration file
require 'config.php';

session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Prepare a SQL query to fetch user with the provided role and username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND role = :role");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables for logged-in user
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($role === 'admin') {
            header("Location: crime_dashboard.php");
        } elseif ($role === 'investigator') {
            header("Location: investigator_dashboard.php");
        }
        exit();
    } else {
        // Invalid credentials message
        echo "<p style='color: red; text-align: center;'>Invalid username or password</p>";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php?'; }, 500);</script>";
    }
}
?>
