<?php
// config.php

// MySQLi connection
$servername = "localhost"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "1234";            // Replace with your database password
$dbname = "CMSDataBase";   // Replace with your database name

// Create MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check MySQLi connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO connection failed: " . $e->getMessage());
}
?>
