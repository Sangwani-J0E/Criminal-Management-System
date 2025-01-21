<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Criminal Record Management System</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleRole(role) {
            const adminSection = document.getElementById('admin-login');
            const investigatorSection = document.getElementById('investigator-login');
            if (role === 'admin') {
                adminSection.style.display = 'block';
                investigatorSection.style.display = 'none';
            } else {
                adminSection.style.display = 'none';
                investigatorSection.style.display = 'block';
            }
        }
    </script>
    <style>
        * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    }

    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-color: #f4f4f4;
    }

    .login-container {
        width: 100%;
        max-width: 400px;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h1 {
        color: #333;
        margin-bottom: 20px;
    }

    .role-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .role-btn {
        flex: 1;
        padding: 10px;
        font-size: 16px;
        background-color: #333;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .role-btn:hover {
        background-color: #ff4d4d;
    }

    .login-section {
        display: none;
    }

    h2 {
        color: #ff4d4d;
        margin-bottom: 15px;
    }

    label {
        display: block;
        text-align: left;
        color: #333;
        margin-top: 10px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .login-btn {
        width: 100%;
        padding: 10px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 15px;
        transition: background-color 0.3s;
    }

    .login-btn:hover {
        background-color: #ff4d4d;
    }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Criminal Record Management System</h1>
        <div class="role-selector">
            <button onclick="toggleRole('admin')" class="role-btn">Administrator</button>
            <button onclick="toggleRole('investigator')" class="role-btn">Investigator</button>
        </div>
        
        <!-- Administrator Login Section -->
        <div id="admin-login" class="login-section">
            <h2>Administrator Login</h2>
            <form action="login.php" method="post">
                <input type="hidden" name="role" value="admin">
                <label for="admin-username">Username</label>
                <input type="text" id="admin-username" name="username" required>
                
                <label for="admin-password">Password</label>
                <input type="password" id="admin-password" name="password" required>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
        
        <!-- Investigator Login Section -->
        <div id="investigator-login" class="login-section" style="display:none;">
            <h2>Investigator Login</h2>
            <form action="login.php" method="post">
                <input type="hidden" name="role" value="investigator">
                <label for="investigator-username">Username</label>
                <input type="text" id="investigator-username" name="username" required>
                
                <label for="investigator-password">Password</label>
                <input type="password" id="investigator-password" name="password" required>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
