<?php
session_start();
require 'config.php'; // Database configuration file
include 'header2.php';

// Check if the investigator is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'investigator') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['case_id'])) {
    echo "Case ID not provided.";
    exit();
}

$case_id = $_GET['case_id'];

// Fetch all case details for the given case_id
$stmt = $pdo->prepare("SELECT * FROM investigator_cases WHERE case_id = :case_id");
$stmt->bindParam(':case_id', $case_id, PDO::PARAM_INT);
$stmt->execute();
$caseDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$caseDetails) {
    echo "No details found for this case.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Case Details</title>
    <style>
        /* Add your form-container styling from above here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-left: 250px;
            padding: 0;
        }

        .form-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #ff4d4d;
        }

        .case-detail {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fafafa;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #ff4d4d;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Case Details for Case ID: <?= htmlspecialchars($case_id); ?></h1>

        <?php foreach ($caseDetails as $index => $case): ?>
            <div class="case-detail">
                <h2>Record <?= $index + 1 ?></h2>
                <strong>Victim Name:</strong> <?= htmlspecialchars($case['victim_name']); ?><br>
                <strong>Victim Statement:</strong> <?= nl2br(htmlspecialchars($case['victim_statement'])); ?><br>
                <strong>Witness Statement:</strong> <?= nl2br(htmlspecialchars($case['witness_statement'])); ?><br>
                <strong>Crime Report:</strong> <?= nl2br(htmlspecialchars($case['crime_report'])); ?><br>
                <strong>Crime Time:</strong> <?= htmlspecialchars($case['crime_time']); ?><br>
                <strong>Crime Background:</strong> <?= nl2br(htmlspecialchars($case['crime_background'])); ?><br>
                <strong>Criminal Intent:</strong> <?= nl2br(htmlspecialchars($case['criminal_intent'])); ?><br>
                <strong>Case Status:</strong> <?= htmlspecialchars($case['case_status']); ?><br>
            </div>
        <?php endforeach; ?>

        <a href="investigator_dashboard.php" class="btn">Back to Cases Overview</a>
    </div>
</body>
</html>
