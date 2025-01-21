<?php
// Start the session to access session variables
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect the user to the login page or home page
header("Location: index.php"); // Or you can change it to index.php if you want the homepage
exit();
?>