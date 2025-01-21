<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require 'config2.php';  // Include your database configuration file
include 'header.php';

// Initialize the search and filter variables
$search_investigator = $_GET['investigator'] ?? '';
$search_case = $_GET['case_name'] ?? '';
$case_status_filter = $_GET['case_status'] ?? '';

// Initialize status counts
$status_counts = [
    'Open' => 0,
    'In Progress' => 0,
    'Closed' => 0,
];

// Fetch statistics for case statuses
$status_query = "SELECT case_status, COUNT(*) AS count FROM crime_records GROUP BY case_status";
$status_result = $conn->query($status_query);
while ($row = $status_result->fetch_assoc()) {
    $status_counts[$row['case_status']] = $row['count'];
}

// Build the base query for cases
$query = "SELECT cr.*, u.username AS investigator_name 
          FROM crime_records cr 
          LEFT JOIN users u ON cr.assigned_investigator = u.user_id";

// Add conditions for searching
$where_conditions = [];
if ($search_investigator) {
    $where_conditions[] = "u.username LIKE '%$search_investigator%'";
}
if ($search_case) {
    $where_conditions[] = "cr.crime_name LIKE '%$search_case%'";
}
if ($case_status_filter) {
    $where_conditions[] = "cr.case_status = '$case_status_filter'";
}

// If there are any search/filter conditions, add them to the query
if (!empty($where_conditions)) {
    $query .= " WHERE " . implode(' AND ', $where_conditions);
}

// Fetch the cases based on the constructed query
$cases = $conn->query($query);

// Update investigator functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['case_id']) && isset($_POST['new_investigator'])) {
    $case_id = $_POST['case_id'];
    $new_investigator = $_POST['new_investigator'];
    $conn->query("UPDATE crime_records SET assigned_investigator = '$new_investigator' WHERE id = '$case_id'");
    header("Location: assign_case.php"); // Refresh page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Case Management</title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-left: 250px;
            padding: 0;
        }
        .container { max-width: 1000px; margin: 30px auto; padding: 10px; background-color: #fff; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
        h1, h2 { text-align: center; color: #333; }
        .stats { display: flex; justify-content: space-around; margin-bottom: 20px; }
        .stat { padding: 15px; border-radius: 5px; background-color: #e8f5e9; color: #333; font-weight: bold; cursor: pointer; }
        .stat:hover { background-color: #c8e6c9; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #333; color: #fff; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .search-bar { margin-bottom: 20px; }
        input[type="text"], select { padding: 8px; border-radius: 4px; border: 1px solid #ccc; width: 200px; }
        button { padding: 10px 20px; background-color: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s; font-size: 16px; }
        button:hover { background-color: #ff4d4d; }
    </style>
</head>
<body>

<div class="container">
    <h1>Case Management</h1>

    <!-- Case Status Statistics -->
    <div class="stats">
        <div class="stat" onclick="filterCases('Open')">Open: <?= $status_counts['Open'] ?></div>
        <div class="stat" onclick="filterCases('In Progress')">In Progress: <?= $status_counts['In Progress'] ?></div>
        <div class="stat" onclick="filterCases('Closed')">Closed: <?= $status_counts['Closed'] ?></div>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="get" action="assign_case.php">
            <input type="text" name="investigator" placeholder="Search Investigator" value="<?= htmlspecialchars($search_investigator) ?>">
            <input type="text" name="case_name" placeholder="Search Case" value="<?= htmlspecialchars($search_case) ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Case Table -->
    <table>
        <tr>
            <th>Case Name</th>
            <th>Investigator</th>
            <th>Case Status</th>
            <th>Crime Name</th>
            <th>Actions</th>
        </tr>
        <?php while ($case = $cases->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($case['crime_name']) ?></td>
                <td><?= htmlspecialchars($case['investigator_name']) ?></td>
                <td><?= htmlspecialchars($case['case_status']) ?></td>
                <td><?= htmlspecialchars($case['crime_name']) ?></td>
                <td>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="case_id" value="<?= $case['id'] ?>">
                        <select name="new_investigator">
                            <?php
                            $investigators = $conn->query("SELECT * FROM users WHERE role = 'investigator'");
                            while ($investigator = $investigators->fetch_assoc()): ?>
                                <option value="<?= $investigator['user_id'] ?>" <?= $case['assigned_investigator'] == $investigator['user_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($investigator['username']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit">Reassign</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    function filterCases(status) {
        window.location.href = `assign_case.php?case_status=${status}`;
    }
</script>

</body>
</html>

<?php $conn->close(); ?>
