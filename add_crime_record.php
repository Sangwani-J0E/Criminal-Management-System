<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Include the config file
require 'config2.php';
include 'header.php';

$investigators = [];
$sql = "SELECT user_id, username FROM users WHERE role = 'investigator'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $investigators[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $district_of_origin = $_POST['district_of_origin'];
    $date_of_birth = $_POST['date_of_birth'];
    $identification_type = $_POST['identification_type'];
    $identification_number = $_POST['identification_number'];
    $address = $_POST['address'];

    // Upload Image
    $image_path = null;
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    // Retrieve crime information and convert arrays to strings
    $crime_name = $_POST['crime_name'];
    $severity = $_POST['severity'];
    $time_of_occurrence = $_POST['time_of_occurrence'];
    $place_of_crime = $_POST['place_of_crime'];
    $victims = implode(", ", $_POST['victims']); // Convert array to string
    $evidence = implode(", ", $_POST['evidence']); // Convert array to string // Convert array to string
    if (isset($_POST['potential_charge'])) {
        $potential_charge = implode(", ", $_POST['potential_charge']);
    } else {
        // Handle the case when the key is not set
        echo "Potential charge not provided.";
    }
    $time_served = $_POST['time_served'];
    $case_status = $_POST['case_status'];
    $assigned_investigator = isset($_POST['investigator_id']) ? $_POST['investigator_id'] : null;

    // SQL Insert Query with placeholders
    $sql = "INSERT INTO crime_records (
        full_name, gender, nationality, district_of_origin, date_of_birth, 
        identification_type, identification_number, address, image_path, 
        crime_name, severity, time_of_occurrence, place_of_crime, victims, 
        evidence, potential_charge, time_served, case_status, assigned_investigator
    ) VALUES (
        :full_name, :gender, :nationality, :district_of_origin, :date_of_birth,
        :identification_type, :identification_number, :address, :image_path,
        :crime_name, :severity, :time_of_occurrence, :place_of_crime, :victims,
        :evidence, :potential_charge, :time_served, :case_status, :assigned_investigator
    )";

    // Prepare the statement using PDO
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':nationality', $nationality);
    $stmt->bindParam(':district_of_origin', $district_of_origin);
    $stmt->bindParam(':date_of_birth', $date_of_birth);
    $stmt->bindParam(':identification_type', $identification_type);
    $stmt->bindParam(':identification_number', $identification_number);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':image_path', $image_path);
    $stmt->bindParam(':crime_name', $crime_name);
    $stmt->bindParam(':severity', $severity);
    $stmt->bindParam(':time_of_occurrence', $time_of_occurrence);
    $stmt->bindParam(':place_of_crime', $place_of_crime);
    $stmt->bindParam(':victims', $victims);
    $stmt->bindParam(':evidence', $evidence);
    $stmt->bindParam(':potential_charge', $potential_charge);
    $stmt->bindParam(':time_served', $time_served);
    $stmt->bindParam(':case_status', $case_status);
    $stmt->bindParam(':assigned_investigator', $assigned_investigator);

    // Execute the prepared statement
    try {
        $stmt->execute();
        echo "<p>Record added successfully!</p>";
    } catch (PDOException $e) {
        echo "<p>Error adding record: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Crime Record</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        /* Basic styling for add crime record page */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin-left: 250px;
        padding: 0;
    }

    .form-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 10px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    h2 {
        color: #ff4d4d;
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        color: #333;
        display: block;
        margin-bottom: 5px;
    }

    .form{
        text-align: center;
        width: 80%;
    }

    input[type="text"],
    input[type="date"],
    input[type="datetime-local"],
    textarea,
    select,
    input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    button[type="submit"],
    .btn {
        padding: 10px 20px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }

    button[type="button"],
    {
        padding: 10px 20px;
        max-width: 20%;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }

    button[type="submit"]:hover,
    button[type="button"]:hover,
    .btn:hover {
        background-color: #ff4d4d;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-container form {
        display: flex;
        flex-direction: column;
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
    <div class="form-container">
        <h1>Add Crime Record</h1>
        <form class ="form" action="add_crime_record.php" method="post" enctype="multipart/form-data">
            <!-- Personal Information Section -->
            <h2>Personal Information</h2>
            <label>Full Name</label>
            <input type="text" name="full_name" required>
            
            <label>Gender</label>
            <select name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label>Nationality</label>
            <input type="text" name="nationality" required>

            <label>District of Origin</label>
            <input type="text" name="district_of_origin" required>

            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" required>

            <label>Identification Type</label>
            <select name="identification_type" required>
                <option value="Driver's License">Driver's License</option>
                <option value="National ID">National ID</option>
            </select>

            <label>Identification Number</label>
            <input type="text" name="identification_number" required>

            <label>Address</label>
            <textarea name="address" required></textarea>

            <label>Upload Image</label>
            <input type="file" name="image" accept="image/*">

            <!-- Crime Information Section -->
            <h2>Crime Information</h2>
            <label>Crime Name</label>
            <input type="text" name="crime_name" required>

            <label>Severity</label>
            <select name="severity" required>
                <option value="Low">Low</option>
                <option value="Moderate">Moderate</option>
                <option value="High">High</option>
                <option value="Severe">Severe</option>
            </select>

            <label>Time of Occurrence</label>
            <input type="datetime-local" name="time_of_occurrence" required>

            <label>Place of Crime</label>
            <input type="text" name="place_of_crime" required>

            <!-- Dynamic List Sections for Victims, Evidence, and Charges -->
            <label>victims</label>
            <div id="victims">
                <div class="dynamic-field">
                    <input type="text" name="victims[]" required>
                </div>
            </div>
            <button type="button" onclick="addField('victims')">+ Victim</button>
            <br>

            <label>evidence</label>
            <div id="evidence">
                <div class="dynamic-field">
                    <input type="text" name="evidence[]" required>
                </div>
            </div>
            <button type="button" onclick="addField('evidence')">+ Evidence</button>
            <br>

            <label>Charges</label>
            <div id="potential_charge">
                <div class="dynamic-field">
                    <input type="text" name="potential_charge[]" required>
                </div>
            </div>
            <button type="button" onclick="addField('potential_charge')">+ Charge</button>
            <br>

            <script>
                function addField(sectionId) {
                    const container = document.getElementById(sectionId);
                    const newField = document.createElement("div");
                    newField.classList.add("dynamic-field");
                    newField.innerHTML = `
                        <input type="text" name="${sectionId}[]" required>
                        <button type="button" onclick="removeField(this)">-</button>
                    `;
                    container.appendChild(newField);
                }

                function removeField(button) {
                    const field = button.parentNode;
                    field.parentNode.removeChild(field);
                }
            </script>

            <label>Time Served</label>
            <input type="text" name="time_served">

            <!-- Case Status -->
            <label>Case Status</label>
            <select name="case_status" required>
                <option value="Open">Open</option>
                <option value="In Progress">In Progress</option>
                <option value="Closed">Closed</option>
            </select>

            <!-- Assigned Investigator -->
            <label for="investigator">Assign to Investigator:</label>
            <select id="investigator" name="investigator_id" required>
                <option value="">Assign Investigator</option>
                <?php foreach ($investigators as $investigator): ?>
                    <option value="<?= $investigator['user_id']; ?>"><?= htmlspecialchars($investigator['username']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Add Record</button>
            <br>
        </form>
    </div>
</body>
</html>