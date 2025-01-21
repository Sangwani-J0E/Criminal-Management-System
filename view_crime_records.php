<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require 'config.php';  // Include your database configuration file
include 'header.php';

// Search functionality
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $sql = "SELECT crime_records.*, users.username AS investigator_name 
            FROM crime_records 
            LEFT JOIN users ON crime_records.assigned_investigator = users.user_id
            WHERE full_name LIKE :searchTerm OR 
                  crime_name LIKE :searchTerm OR
                  identification_number LIKE :searchTerm OR 
                  time_of_occurrence LIKE :searchTerm OR 
                  place_of_crime LIKE :searchTerm";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
} else {
    $sql = "SELECT crime_records.*, users.username AS investigator_name 
            FROM crime_records 
            LEFT JOIN users ON crime_records.assigned_investigator = users.user_id";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$crime_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if there are records
if (empty($crime_records)) {
    echo "<p>No crime records found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Crime Records</title>
    <style>
        /* Basic styling for the view crime records page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-left: 20;
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

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #ff4d4d;
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
        <h1>Crime Records</h1>

        <!-- Search form -->
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search by name, crime, criminal-ID, date, or location" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Crime Name</th>
                    <th>Severity</th>
                    <th>Time of Occurrence</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crime_records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['id']); ?></td>
                        <td><?php echo htmlspecialchars($record['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['crime_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['severity']); ?></td>
                        <td><?php echo htmlspecialchars($record['time_of_occurrence']); ?></td>
                        <td>
                            <button class="toggle-button" onclick="toggleMoreInfo(<?php echo $record['id']; ?>)">Details</button>
                            <a href="edit_crime_record.php?id=<?php echo $record['id']; ?>">Edit</a> |
                            <a href="delete_crime_record.php?id=<?php echo $record['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a> |
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
                            <strong>Case Status:</strong> <?php echo htmlspecialchars($record['case_status']); ?><br>
                            <strong>Assigned Investigator:</strong> <?php echo htmlspecialchars($record['investigator_name']); ?><br>
                            <strong>Image:</strong> <?php echo htmlspecialchars($record['image_path']); ?><br>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        function toggleMoreInfo(id) {
            var moreInfo = document.getElementById("more-info-" + id);
            moreInfo.style.display = moreInfo.style.display === "none" ? "table-row" : "none";
        }
    </script>
</body>
</html>
