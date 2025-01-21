<?php
session_start();
if ($_SESSION['role'] !== 'investigator') {
    header("Location: index.php");
    exit();
}

require 'config.php'; // Include your database configuration file
include 'header2.php';

// Fetch crime data for the dashboard
$sql_severity = "SELECT severity, COUNT(*) AS count FROM crime_records GROUP BY severity";
$stmt_severity = $pdo->query($sql_severity);
$severity_data = $stmt_severity->fetchAll(PDO::FETCH_ASSOC);

$sql_place_of_crime = "SELECT place_of_crime, COUNT(*) AS count FROM crime_records GROUP BY place_of_crime";
$stmt_place_of_crime = $pdo->query($sql_place_of_crime);
$place_of_crime_data = $stmt_place_of_crime->fetchAll(PDO::FETCH_ASSOC);

$sql_time_trends = "SELECT DATE(time_of_occurrence) AS date, COUNT(*) AS count FROM crime_records GROUP BY date ORDER BY date ASC";
$stmt_time_trends = $pdo->query($sql_time_trends);
$time_trends_data = $stmt_time_trends->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for charts
$severity_labels = [];
$severity_counts = [];
foreach ($severity_data as $row) {
    $severity_labels[] = $row['severity'];
    $severity_counts[] = $row['count'];
}

$place_labels = [];
$place_counts = [];
foreach ($place_of_crime_data as $row) {
    $place_labels[] = $row['place_of_crime'];
    $place_counts[] = $row['count'];
}

$time_labels = [];
$time_counts = [];
foreach ($time_trends_data as $row) {
    $time_labels[] = $row['date'];
    $time_counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Dashboard</title>
    <link rel="stylesheet" href="style/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <h1>Crime Dashboard</h1>

        <!-- Crime Count by Severity -->
        <div class="chart-container">
            <h2>Crime Count by Severity</h2>
            <canvas id="severityChart"></canvas>
        </div>

        <!-- Crime Count by Place of Crime -->
        <div class="chart-container">
            <h2>Crime Count by Place of Crime</h2>
            <canvas id="placeChart"></canvas>
        </div>

        <!-- Crime Trends Over Time -->
        <div class="chart-container">
            <h2>Crime Trends Over Time</h2>
            <canvas id="timeChart"></canvas>
        </div>
    </div>

    <script>
        // Crime Count by Severity Chart
        var ctx1 = document.getElementById('severityChart').getContext('2d');
        var severityChart = new Chart(ctx1, {
            type: 'pie', // Pie chart
            data: {
                labels: <?php echo json_encode($severity_labels); ?>,
                datasets: [{
                    label: 'Crime Count by Severity',
                    data: <?php echo json_encode($severity_counts); ?>,
                    backgroundColor: ['#FF5733', '#FF8D1A', '#FFC300', '#DAF7A6'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            }
        });

        // Crime Count by Place of Crime Chart
        var ctx2 = document.getElementById('placeChart').getContext('2d');
        var placeChart = new Chart(ctx2, {
            type: 'bar', // Bar chart
            data: {
                labels: <?php echo json_encode($place_labels); ?>,
                datasets: [{
                    label: 'Crime Count by Place of Crime',
                    data: <?php echo json_encode($place_counts); ?>,
                    backgroundColor: '#4CAF50',
                    borderColor: '#2e7d32',
                    borderWidth: 1
                }]
            }
        });

        // Crime Trends Over Time Chart
        var ctx3 = document.getElementById('timeChart').getContext('2d');
        var timeChart = new Chart(ctx3, {
            type: 'line', // Line chart
            data: {
                labels: <?php echo json_encode($time_labels); ?>,
                datasets: [{
                    label: 'Crime Count Over Time',
                    data: <?php echo json_encode($time_counts); ?>,
                    fill: false,
                    borderColor: '#3e95cd',
                    tension: 0.1
                }]
            }
        });
    </script>
</body>
</html>
