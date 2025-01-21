<?php
// Include the configuration file to connect to the database
require_once 'config.php';

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch the user data from the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die('User not found.');
        }
    } catch (PDOException $e) {
        die("Error fetching user: " . $e->getMessage());
    }
} else {
    die('User ID not provided.');
}

// Handle form submission for updating user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated user data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; // New field for password confirmation
    $role = $_POST['role'];

    // Check if password and confirm password match
    if (!empty($password) && $password !== $confirm_password) {
        die('Passwords do not match.');
    }

    // If password is provided, hash it before saving
    $hashed_password = $user['password']; // Default to current password if no new password is provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Update the user data in the database
    try {
        $update_stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password, role = :role WHERE user_id = :user_id");
        $update_stmt->bindParam(':username', $username);
        $update_stmt->bindParam(':password', $hashed_password);
        $update_stmt->bindParam(':role', $role);
        $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $update_stmt->execute();

        // Redirect to the user list page after successful update
        header("Location: user_list.php");
        exit();
    } catch (PDOException $e) {
        die("Error updating user: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style/style.css"> <!-- Optional for styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
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
        p {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            width: 300px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            color: #333;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            font-size: 14px;
        }

        button:hover {
            background-color: #ff4d4d;
            color: #fff;
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
    </style>
</head>
<body>
    <div class ="container">
    <h1>Edit User</h1>
    <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter new password (leave empty to keep current)" value="">
        </div>

        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password">
        </div>

        <div>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="investigator" <?php echo ($user['role'] === 'investigator') ? 'selected' : ''; ?>>Investigator</option>
            </select>
        </div>
        <br>
        <div>
            <button type="submit">Update User</button>
        </div>
    </form>
        <p><a href="user_management.php">Back to User List</a></p>
    </div>
</body>
</html>
