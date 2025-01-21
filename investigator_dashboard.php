<?php
session_start();
require 'config.php'; // Database configuration file
include 'header2.php';

// Check if the investigator is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'investigator') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch the user_id of the logged-in investigator
$userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username AND role = 'investigator'");
$userQuery->bindParam(':username', $username);
$userQuery->execute();
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found or not authorized.";
    exit();
}

$user_id = $user['user_id'];

// Fetch cases assigned to the logged-in investigator
$stmt = $pdo->prepare("
    SELECT * FROM crime_records
    WHERE assigned_investigator = :user_id
");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$cases = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investigator Dashboard - Overview of Cases</title>
    <style>
         /* Basic styling for the view crime records page */
         body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-left: 20;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #ff4d4d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 4px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 16px;
        }

        table th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #333;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            font-size: 14px;
        }

        a:hover {
            background-color: #ff4d4d;
            color: #fff;
        }

        .toggle-button{
            color: #333;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            font-size: 14px;
        }

        .toggle-button:hover{
            background-color: #ff4d4d;
            color: #fff;
        }

        .edit-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .delete-button {
            background-color: #d32f2f;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .edit-button:hover {
            background-color: #388e3c;
        }

        .delete-button:hover {
            background-color: #c62828;
        }

        .success-message,
        .error-message {
            text-align: center;
            font-size: 16px;
            padding: 10px;
            margin-top: 15px;
            border-radius: 4px;
        }

        .success-message {
            color: #2e7d32;
            background-color: #e8f5e9;
        }

        .error-message {
            color: #d32f2f;
            background-color: #ffebee;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Investigator Dashboard</h1>
        <h2>Overview of Assigned Cases</h2>

        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Case ID</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Nationality</th>
                <th>Date of Birth</th>
                <th>Crime Name</th>
                <th>Severity</th>
                <th>Time of Occurrence</th>
                <th>Place of Crime</th>
                <th>Case Status</th>
                <th>Actions</th>
            </tr>

            <?php if (count($cases) > 0): ?>
                <?php foreach ($cases as $case): ?>
                    <tr>
                        <td><?= htmlspecialchars($case['id']); ?></td>
                        <td><?= htmlspecialchars($case['full_name']); ?></td>
                        <td><?= htmlspecialchars($case['gender']); ?></td>
                        <td><?= htmlspecialchars($case['nationality']); ?></td>
                        <td><?= htmlspecialchars($case['date_of_birth']); ?></td>
                        <td><?= htmlspecialchars($case['crime_name']); ?></td>
                        <td><?= htmlspecialchars($case['severity']); ?></td>
                        <td><?= htmlspecialchars($case['time_of_occurrence']); ?></td>
                        <td><?= htmlspecialchars($case['place_of_crime']); ?></td>
                        <td><?= htmlspecialchars($case['case_status']); ?></td>
                        <td>
                            <a href="add_case_details.php?case_id=<?= $case['id']; ?>" class="toggle-button">Add_Details</a><br>
                            <a href="view_case.php?case_id=<?= $case['id']; ?>" class="toggle-button">View_Case_Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" style="text-align: center;">No cases assigned to you.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
