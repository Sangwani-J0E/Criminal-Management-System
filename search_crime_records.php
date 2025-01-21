<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require 'config.php';  // Include your database configuration file
include 'header.php';

// Prepare the search criteria
$crime_name = isset($_GET['crime_name']) ? $_GET['crime_name'] : '';
$severity = isset($_GET['severity']) ? $_GET['severity'] : '';
$place_of_crime = isset($_GET['place_of_crime']) ? $_GET['place_of_crime'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Building the SQL query based on the criteria
$sql = "SELECT * FROM crime_records WHERE 1=1";  // '1=1' is used to simplify adding conditions dynamically

// Add conditions to the query if the corresponding input fields are filled
if ($crime_name !== '') {
    $sql .= " AND crime_name LIKE :crime_name";
}
if ($severity !== '') {
    $sql .= " AND severity = :severity";
}
if ($place_of_crime !== '') {
    $sql .= " AND place_of_crime LIKE :place_of_crime";
}
if ($start_date !== '' && $end_date !== '') {
    $sql .= " AND time_of_occurrence BETWEEN :start_date AND :end_date";
} elseif ($start_date !== '') {
    $sql .= " AND time_of_occurrence >= :start_date";
} elseif ($end_date !== '') {
    $sql .= " AND time_of_occurrence <= :end_date";
}

// Prepare and execute the query
$stmt = $pdo->prepare($sql);

if ($crime_name !== '') {
    $stmt->bindValue(':crime_name', '%' . $crime_name . '%');
}
if ($severity !== '') {
    $stmt->bindValue(':severity', $severity);
}
if ($place_of_crime !== '') {
    $stmt->bindValue(':place_of_crime', '%' . $place_of_crime . '%');
}
if ($start_date !== '') {
    $stmt->bindValue(':start_date', $start_date);
}
if ($end_date !== '') {
    $stmt->bindValue(':end_date', $end_date);
}

$stmt->execute();
$crime_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Crime Records</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-left: 280px;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
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

        .toggle-button{
            color: #333;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            font-size: 14px;
        }

        .toggle-button:hover{
            background-color: #ff4d4d;
            color: #fff;
        }

        .edit-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .delete-button {
            background-color: #d32f2f;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .edit-button:hover {
            background-color: #388e3c;
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
        <h1>Search Crime Records</h1>

        <!-- Search Form -->
        <form method="GET" action="search_crime_records.php">
            <label for="crime_name">Crime Name</label>
            <input type="text" id="crime_name" name="crime_name" value="<?php echo htmlspecialchars($crime_name); ?>">

            <label for="severity">Severity</label>
            <select name="severity" id="severity">
                <option value="">Select Severity</option>
                <option value="Low" <?php echo $severity === 'Low' ? 'selected' : ''; ?>>Low</option>
                <option value="Moderate" <?php echo $severity === 'Moderate' ? 'selected' : ''; ?>>Moderate</option>
                <option value="High" <?php echo $severity === 'High' ? 'selected' : ''; ?>>High</option>
                <option value="Severe" <?php echo $severity === 'Severe' ? 'selected' : ''; ?>>Severe</option>
            </select>

            <label for="place_of_crime">Place of Crime</label>
            <input type="text" id="place_of_crime" name="place_of_crime" value="<?php echo htmlspecialchars($place_of_crime); ?>">

            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">

            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">

            <button type="submit">Search</button>
        </form>

        <!-- Crime Records Table -->
        <?php if (count($crime_records) > 0): ?>
            <h2>Search Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Crime Name</th>
                        <th>Severity</th>
                        <th>Place of Crime</th>
                        <th>Date of Occurrence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($crime_records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($record['crime_name']); ?></td>
                            <td><?php echo htmlspecialchars($record['severity']); ?></td>
                            <td><?php echo htmlspecialchars($record['place_of_crime']); ?></td>
                            <td><?php echo htmlspecialchars($record['time_of_occurrence']); ?></td>
                            <td>
                                <button class="toggle-button" onclick="toggleMoreInfo(<?php echo $record['id']; ?>)">Details</button>
                                <a href="edit_crime_record.php?id=<?php echo $record['id']; ?>">Edit</a> |
                                <a href="delete_crime_record.php?id=<?php echo $record['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                        <tr id="more-info-<?php echo $record['id']; ?>" class="more-info" style="display: none;">
                        <td colspan="6">
                            <strong>Gender:</strong> <?php echo htmlspecialchars($record['gender']); ?><br>
                            <strong>Nationality:</strong> <?php echo htmlspecialchars($record['nationality']); ?><br>
                            <strong>District of Origin:</strong> <?php echo htmlspecialchars($record['district_of_origin']); ?><br>
                            <strong>Date of Birth:</strong> <?php echo htmlspecialchars($record['date_of_birth']); ?><br>
                            <strong>Identification Type:</strong> <?php echo htmlspecialchars($record['identification_type']); ?><br>
                            <strong>Identification Number:</strong> <?php echo htmlspecialchars($record['identification_number']); ?><br>
                            <strong>Address:</strong> <?php echo htmlspecialchars($record['address']); ?><br>
                            <strong>Place of Crime:</strong> <?php echo htmlspecialchars($record['place_of_crime']); ?><br>
                            <strong>Victims:</strong> <?php echo htmlspecialchars($record['victims']); ?><br>
                            <strong>Evidence:</strong> <?php echo htmlspecialchars($record['evidence']); ?><br>
                            <strong>Potential Charge:</strong> <?php echo htmlspecialchars($record['potential_charge']); ?><br>
                            <strong>Time Served:</strong> <?php echo htmlspecialchars($record['time_served']); ?><br>
                            <strong>Image:</strong> <?php echo htmlspecialchars($record['image_path']); ?><br>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No crime records found matching your criteria.</p>
        <?php endif; ?>
    </div>
    <script>
        function toggleMoreInfo(id) {
            var moreInfo = document.getElementById("more-info-" + id);
            moreInfo.style.display = moreInfo.style.display === "none" ? "table-row" : "none";
        }
    </script>
</body>
</html>