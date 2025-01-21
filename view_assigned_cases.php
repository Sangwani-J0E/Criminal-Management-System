<?php
session_start(); // Only call this once at the top of the script

// Check if the session is set properly and if the user is an investigator
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'investigator') {
    header("Location: index.php");
    exit();
}

include('config.php');

$user_id = $_SESSION['user_id']; // The logged-in user's ID

// Fetch assigned cases
$sql = "SELECT * FROM crime_records WHERE assigned_investigator = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cases = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investigator Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Assigned Cases</h1>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Case Name</th>
                    <th>Crime</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cases)): ?>
                    <tr>
                        <td colspan="4">No cases assigned.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cases as $case): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($case['crime_name']); ?></td>
                        <td><?php echo htmlspecialchars($case['crime_name']); ?></td>
                        <td><?php echo htmlspecialchars($case['case_status']); ?></td>
                        <td>
                            <a href="view_case.php?id=<?php echo $case['id']; ?>">View Details</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
