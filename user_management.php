<?php
session_start();

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require 'config.php'; // Include your database connection file
include 'header.php';

// Fetch all users
try {
    $sql = "SELECT * FROM users"; // Assuming the users table is called 'users'
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if data is fetched
    if (empty($users)) {
        throw new Exception("No users found in the database.");
    }
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

if (isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Debugging output to ensure role is received
    if (empty($role)) {
        echo "<div class='error-message'>Role is missing.</div>";
    } else {
        var_dump($_POST); // Check the role and other form data

        // Insert the new user into the database
        try {
            $sql_insert = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$username, $password, $role]);

            header("Location: user_management.php");
            exit();
        } catch (PDOException $e) {
            die("Error inserting user: " . $e->getMessage());
        }
    }
}

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    try {
        // Use the correct column name in your DELETE query
        $sql_delete = "DELETE FROM users WHERE user_id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$user_id]);

        header("Location: user_management.php");
        exit();
    } catch (PDOException $e) {
        die("Error deleting user: " . $e->getMessage());
    }
}

// Handle user update
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    try {
        $sql_update = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$username, $role, $user_id]);

        header("Location: user_management.php");
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
    <title>User Management</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        /* General page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-left: 20;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            
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

        .form-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .form-container button:hover {
            background-color: #218838;
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
        <h1>User Management</h1>

        <!-- Form to add new user -->
            <h2>Create New User</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role" required>
                    <option value="admin">admin</option>
                    <option value="user">investigator</option>
                </select>
                <button type="submit" name="create_user">Create User</button>
             </form>

        <!-- Table to display users -->
        <h2>Existing Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($users)) {
                    foreach ($users as $user): 
                ?>
                    <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <!-- Edit User -->
                                <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="edit-button">Edit</a>
                                <!-- Delete User -->
                                <a href="?delete=<?php echo $user['user_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                    </tr>
                <?php 
                    endforeach; 
                } else {
                    echo "<tr><td colspan='4'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
