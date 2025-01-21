<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require 'config.php';

// Check if id is provided
if (!isset($_GET['id'])) {
    die("Record ID is required.");
}

$crime_id = $_GET['id'];

// SQL Delete Query
$sql = "DELETE FROM crime_records WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $crime_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Redirect to the view_crime_records.php page after deletion
    header("Location: view_crime_records.php");
    exit();
} else {
    echo "<p>Error deleting record: " . $stmt->errorInfo()[2] . "</p>";
}
?>
