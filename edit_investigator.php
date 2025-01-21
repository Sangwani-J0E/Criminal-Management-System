<?php
session_start();
require 'config.php'; // Database configuration file
include 'header2.php';

// Check if the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'investigator') {
    header("Location: login.php");
    exit();
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validate input
    if (empty($username)) {
        $error = "Username is required.";
    } else {
        // Prepare the update query
        if (!empty($password)) {
            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password WHERE user_id = :user_id AND role = 'investigator'");
            $stmt->bindParam(':password', $hashed_password);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE user_id = :user_id AND role = 'investigator'");
        }
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        // Execute the update
        if ($stmt->execute()) {
            $success = "Investigator details updated successfully.";
        } else {
            $error = "Failed to update investigator details.";
        }
    }
}

// Fetch all investigators to display in the form
$stmt = $pdo->prepare("SELECT user_id, username FROM users WHERE role = 'investigator'");
$stmt->execute();
$investigators = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Investigator User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            margin-left: 250px;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
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

        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #ff4d4d;
        }

        .success-message, .error-message {
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
        <h1>Edit Investigator User Details</h1>

        <?php if (isset($success)): ?>
            <div class="success-message"><?= htmlspecialchars($success); ?></div>
        <?php elseif (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="edit_investigator.php">
            <label for="user_id">Select Investigator:</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- Select Investigator --</option>
                <?php foreach ($investigators as $investigator): ?>
                    <option value="<?= htmlspecialchars($investigator['user_id']); ?>">
                        <?= htmlspecialchars($investigator['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="username">New Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">New Password (Leave blank to keep current password):</label>
            <input type="password" name="password" id="password">

            <button type="submit">Update Investigator</button>
        </form>
    </div>
</body>
</html>
