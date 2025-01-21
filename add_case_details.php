<?php
session_start();
require 'config.php';
include 'header2.php';

// Check if the investigator is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'investigator') {
    header("Location: login.php");
    exit();
}

// Get the case_id from the URL
if (!isset($_GET['case_id'])) {
    echo "No case selected.";
    exit();
}
$case_id = $_GET['case_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $victim_name = $_POST['victim_name'];
    $victim_statement = $_POST['victim_statement'];
    $witness_statement = $_POST['witness_statement'];
    $crime_report = $_POST['crime_report'];
    $crime_time = $_POST['crime_time'];
    $crime_background = $_POST['crime_background'];
    $criminal_intent = $_POST['criminal_intent'];
    $case_status = $_POST['case_status'];

    // Insert data into investigator_cases table
    $stmt = $pdo->prepare("
        INSERT INTO investigator_cases 
        (case_id, victim_name, victim_statement, witness_statement, crime_report, crime_time, crime_background, criminal_intent, case_status) 
        VALUES 
        (:case_id, :victim_name, :victim_statement, :witness_statement, :crime_report, :crime_time, :crime_background, :criminal_intent, :case_status)
    ");
    $stmt->execute([
        ':case_id' => $case_id,
        ':victim_name' => $victim_name,
        ':victim_statement' => $victim_statement,
        ':witness_statement' => $witness_statement,
        ':crime_report' => $crime_report,
        ':crime_time' => $crime_time,
        ':crime_background' => $crime_background,
        ':criminal_intent' => $criminal_intent,
        ':case_status' => $case_status
    ]);

    echo "Case details added successfully!";
    echo "<script>setTimeout(function(){ window.location.href = 'add_case_details.php?case_id=$case_id'; }, 1000);</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Case Details</title>
    <style>
            /* Basic styling for add crime record page */
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

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    h2 {
        color: #ff4d4d;
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        color: #333;
        display: block;
        margin-bottom: 5px;
    }

    .form{
        text-align: center;
        width: 98%;
        padding:;
    }

    input[type="text"],
    input[type="date"],
    input[type="datetime-local"],
    textarea,
    select,
    input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    button[type="submit"],
    .btn {
        padding: 10px 20px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }

    button[type="button"],
    {
        padding: 10px 20px;
        max-width: 20%;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }

    button[type="submit"]:hover,
    button[type="button"]:hover,
    .btn:hover {
        background-color: #ff4d4d;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-container form {
        display: flex;
        flex-direction: column;
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
<div class="form-container">
    <h1>Add Case Details</h1>

    <form class ="form" action="add_case_details.php?case_id=<?= $case_id; ?>" method="POST">
        <input type="hidden" name="case_id" value="<?= $case_id; ?>">

        <label for="victim_name">Victim Name:</label>
        <input type="text" id="victim_name" name="victim_name" required>

        <label for="victim_statement">Victim Statement:</label>
        <textarea id="victim_statement" name="victim_statement" required></textarea>

        <label for="witness_statement">Witness Statement:</label>
        <textarea id="witness_statement" name="witness_statement" required></textarea>

        <label for="crime_report">Crime Report:</label>
        <textarea id="crime_report" name="crime_report" required></textarea>

        <label for="crime_time">Crime Time:</label>
        <input type="datetime-local" id="crime_time" name="crime_time" required>

        <label for="crime_background">Crime Background:</label>
        <textarea id="crime_background" name="crime_background" required></textarea>

        <label for="criminal_intent">Criminal Intent:</label>
        <textarea id="criminal_intent" name="criminal_intent" required></textarea>

        <label for="case_status">Case Status:</label>
        <select id="case_status" name="case_status" required>
            <option value="Open">Open</option>
            <option value="In Progress">In Progress</option>
            <option value="Closed">Closed</option>
        </select>

        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
