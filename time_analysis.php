<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require 'config.php';
include 'header.php';

// Default filter to show crime trends by year
$time_filter = isset($_GET['time_filter']) ? $_GET['time_filter'] : 'YEAR';

// Fetch crime data (crime count by year or month)
if ($time_filter === 'YEAR') {
    $sql = "SELECT YEAR(time_of_occurrence) AS year, COUNT(id) AS crime_count 
            FROM crime_records 
            GROUP BY YEAR(time_of_occurrence) 
            ORDER BY year DESC";
} elseif ($time_filter === 'MONTH') {
    $sql = "SELECT MONTH(time_of_occurrence) AS month, YEAR(time_of_occurrence) AS year, COUNT(id) AS crime_count 
            FROM crime_records 
            GROUP BY YEAR(time_of_occurrence), MONTH(time_of_occurrence) 
            ORDER BY year DESC, month DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute();
$crime_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for the chart
$labels = [];
$crime_counts = [];

foreach ($crime_data as $data) {
    if ($time_filter === 'YEAR') {
        $labels[] = $data['year'];
    } elseif ($time_filter === 'MONTH') {
        $labels[] = $data['month'] . '/' . $data['year'];
    }
    $crime_counts[] = $data['crime_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Time Analysis</title>
    <link rel="stylesheet" href="style/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="form-container">
        <h1>Crime Trends Over Time</h1>

        <!-- Filter Form (optional) -->
        <form method="GET" action="time_analysis.php">
            <label for="time_filter">Select Time Period</label>
            <select name="time_filter" id="time_filter">
                <option value="YEAR" <?php echo $time_filter === 'YEAR' ? 'selected' : ''; ?>>Yearly</option>
                <option value="MONTH" <?php echo $time_filter === 'MONTH' ? 'selected' : ''; ?>>Monthly</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <!-- Crime Trend Chart -->
        <canvas id="crimeChart" width="400" height="200"></canvas>

        <h2>Crime Records Over Time</h2>
        <table>
            <thead>
                <tr>
                    <th>Time Period</th>
                    <th>Crime Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crime_data as $data): ?>
                    <tr>
                        <td>
                            <?php
                            if ($time_filter === 'YEAR') {
                                echo htmlspecialchars($data['year']);
                            } elseif ($time_filter === 'MONTH') {
                                echo htmlspecialchars($data['month']) . '/' . htmlspecialchars($data['year']);
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($data['crime_count']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Render Chart.js chart with crime trends data
        var ctx = document.getElementById('crimeChart').getContext('2d');
        var crimeChart = new Chart(ctx, {
            type: 'line', // You can change this to 'bar' for bar chart
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Crime Count',
                    data: <?php echo json_encode($crime_counts); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
