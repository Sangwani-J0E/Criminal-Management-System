<?php
$host = 'localhost';
$dbname = 'CMSDataBase';
$username = 'root';  // Replace with your database username
$password = '1234';      // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
