<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
require 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Criminal Record Management System</title>
    <style>
        /* Basic styling for dashboard */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .dashboard-container {
            max-width: 600px;
            border-radius: 20px;
            margin-left: 300px;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .section {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            color: #ff4d4d;
            margin-bottom: 15px;
        }

        .btn {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #ff4d4d;
        }
    </style>

</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="crime_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="user_management.php"><i class="fas fa-user"></i> Manage Users</a></li>
            <li><a href="view_crime_records.php"><i class="fas fa-file-alt"></i> Crime Records</a></li>
            <li><a href="add_crime_record.php"><i class="fas fa-plus-square"></i> Add Crime Record</a></li>
            <li><a href="time_analysis.php"><i class="fa-solid fa-clock"></i> Time Analysis</a></li>
            <li><a href="location_tracking.php"><i class="fa-solid fa-location-dot"></i> Location Tracking</a></li>
            <li><a href="search_crime_records.php"><i class="fa-solid fa-magnifying-glass"></i> Search Crime Records</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="dashboard-container">
        <h1>Admin Dashboard - Criminal Record Management System</h1>

        <!-- Crime Record Entry and Management -->
        <div class="section">
            <h2>Crime Record Entry and Management</h2>
            <button class="btn" onclick="location.href='add_crime_record.php'">Add Crime Record</button>
            <button class="btn" onclick="location.href='view_crime_records.php'">View/Edit/Delete Crime Records</button>
        </div>

        <!-- Location Tracking -->
        <div class="section">
            <h2>Location Tracking</h2>
            <p>Record and track where crimes occur, categorize them by location, and monitor trends.</p>
            <button class="btn" onclick="location.href='location_tracking.php'">Track Locations</button>
        </div>

        <!-- Time-Based Analysis -->
        <div class="section">
            <h2>Time-Based Analysis</h2>
            <p>Analyze crime rates over time and visualize crime trends year by year.</p>
            <button class="btn" onclick="location.href='time_analysis.php'">Analyze Crime Rates</button>
        </div>

        <!-- Search Capabilities -->
        <div class="section">
            <h2>Search Capabilities</h2>
            <p>Quickly locate crime records based on various criteria such as crime type, date, location, or suspect details.</p>
            <button class="btn" onclick="location.href='search_crime_records.php'">Search Records</button>
        </div>

        <!-- Dashboard Summary and Visualizations -->
        <div class="section">
            <h2>Dashboard Summary and Visualizations</h2>
            <p>Summarize crime data and view visual representations of crime trends.</p>
            <button class="btn" onclick="location.href='crime_dashboard.php'">View Dashboard</button>
        </div>

        <!-- User Authentication and Security -->
        <div class="section">
            <h2>User Authentication and Security</h2>
            <p>Only authorized users can access or manipulate data. Manage user access and monitor system operations.</p>
            <button class="btn" onclick="location.href='user_management.php'">Manage Users</button>
        </div>
        
        <button class="btn" onclick="location.href='logout.php'" style="background-color: red; margin-top: 20px;">Logout</button>
    </div>
</body>
</html>
