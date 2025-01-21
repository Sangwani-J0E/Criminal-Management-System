<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require 'config2.php';
include 'header.php';

// Check if id is provided
if (!isset($_GET['id'])) {
    die("Record ID is required.");
}

$crime_id = $_GET['id'];

// Fetch the record
$sql = "SELECT * FROM crime_records WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $crime_id, PDO::PARAM_INT);
$stmt->execute();
$record = $stmt->fetch(PDO::FETCH_ASSOC);

// If the record doesn't exist, show an error
if (!$record) {
    die("Record not found.");
}

// Fetch investigators
$investigators = [];
$sql = "SELECT user_id, username FROM users WHERE role = 'investigator'";
$stmt2 = $pdo->query($sql);  // Ensure you're using PDO, not MySQLi
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $investigators[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated data from form
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $district_of_origin = $_POST['district_of_origin'];
    $date_of_birth = $_POST['date_of_birth'];
    $identification_type = $_POST['identification_type'];
    $identification_number = $_POST['identification_number'];
    $address = $_POST['address'];

    // Upload Image
    $image_path = $record['image_path'];
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    // Retrieve crime information
    $crime_name = $_POST['crime_name'];
    $severity = $_POST['severity'];
    $time_of_occurrence = $_POST['time_of_occurrence'];
    $place_of_crime = $_POST['place_of_crime'];
    $victims = implode(", ", $_POST['victims']); // Convert array to string
    $evidence = implode(", ", $_POST['evidence']); // Convert array to string
    $potential_charge = isset($_POST['potential_charge']) ? implode(", ", $_POST['potential_charge']) : null;
    $time_served = $_POST['time_served'];
    $case_status = $_POST['case_status'];
    $assigned_investigator = isset($_POST['investigator_id']) ? $_POST['investigator_id'] : null;

    // SQL Update Query
    $sql = "UPDATE crime_records SET 
        full_name = :full_name,
        gender = :gender, 
        nationality = :nationality, 
        district_of_origin = :district_of_origin, 
        date_of_birth = :date_of_birth, 
        identification_type = :identification_type, 
        identification_number = :identification_number, 
        address = :address, 
        image_path = :image_path, 
        crime_name = :crime_name, 
        severity = :severity, 
        time_of_occurrence = :time_of_occurrence, 
        place_of_crime = :place_of_crime, 
        victims = :victims, 
        evidence = :evidence, 
        potential_charge = :potential_charge, 
        time_served = :time_served,
        case_status = :case_status, 
        assigned_investigator = :assigned_investigator
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
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
    $stmt->bindParam(':id', $crime_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: view_crime_records.php");
        exit();
    } else {
        echo "<p>Error updating record: " . $stmt->errorInfo()[2] . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Crime Record</title>
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Edit Crime Record</h1>
        <form class ="form" method="POST" enctype="multipart/form-data">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($record['full_name']); ?>" required>
            
            <label>Gender</label>
            <select name="gender" required>
                <option value="Male" <?php echo $record['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $record['gender'] == 'Famale' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $record['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>

            <label>Nationality</label>
            <input type="text" name="nationality" value="<?php echo htmlspecialchars($record['nationality']); ?>" required>

            <label>District of Origin</label>
            <input type="text" name="district_of_origin" value="<?php echo htmlspecialchars($record['district_of_origin']); ?>" required>

            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($record['date_of_birth']); ?>" required>

            <label>Identification Type</label>
            <select name="identification_type" required>
                <option value="Driver's License">Driver's License</option>
                <option value="National ID">National ID</option>
            </select>

            <label>Identification Number</label>
            <input type="text" name="identification_number" value="<?php echo htmlspecialchars($record['identification_number']); ?>" required>

            <label>Address</label>
            <textarea name="address"><?php echo htmlspecialchars($record['address']); ?></textarea>

            <label>Upload Image</label>
            <?php if ($record['image_path']): ?>
                <div>
                    <img src="<?php echo htmlspecialchars($record['image_path']); ?>" alt="Current Image" style="width: 70px; height: auto; border-radius: 20%;">
                    <p>Click to change image</p>
                </div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">

            <!-- Crime Information Section -->
            <h2>Crime Information</h2>
            <label>Crime Name</label>
            <input type="text" name="crime_name" value="<?php echo htmlspecialchars($record['crime_name']); ?>" required>

            <label>Severity</label>
            <select name="severity" required>
                <option value="Low" <?php echo $record['severity'] == 'Low' ? 'selected' : ''; ?>>Low</option>
                <option value="Moderate" <?php echo $record['severity'] == 'Moderate' ? 'selected' : ''; ?>>Moderate</option>
                <option value="High" <?php echo $record['severity'] == 'High' ? 'selected' : ''; ?>>High</option>
                <option value="Severe" <?php echo $record['severity'] == 'Severe' ? 'selected' : ''; ?>>Severe</option>
            </select>

            <label>Time of Occurrence</label>
            <input type="datetime-local" name="time_of_occurrence" value="<?php echo htmlspecialchars($record['time_of_occurrence']); ?>" required>

            <label>Place of Crime</label>
            <input type="text" name="place_of_crime" value="<?php echo htmlspecialchars($record['place_of_crime']); ?>" required>

            <!-- Dynamic List Sections for Victims, Evidence, and Charges -->
            <label>Victims</label>
            <div id="victims">
                <?php 
                $victims = explode(", ", $record['victims']); // Convert string to array
                foreach ($victims as $victim): ?>
                    <div class="dynamic-field">
                        <input type="text" name="victims[]" value="<?php echo htmlspecialchars($victim); ?>" required>
                        <button class="btn" type="button" onclick="removeField(this)">-</button>
                        <br>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn" type="button" onclick="addField('victims')">+ Victim</button>
            <br>

            <label>Evidence</label>
            <div id="evidence">
                <?php 
                $evidenceList = explode(", ", $record['evidence']); // Convert string to array
                foreach ($evidenceList as $evidence): ?>
                    <div class="dynamic-field">
                        <input type="text" name="evidence[]" value="<?php echo htmlspecialchars($evidence); ?>" required>
                        <br>
                        <button class="btn" type="button" onclick="removeField(this)">-</button>
                        <br>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn" type="button" onclick="addField('evidence')">+ Evidence</button>
            <br>

            <label>Potential Charge</label>
            <div id="potential_charge">
                <?php 
                $charges = explode(", ", $record['potential_charge']); // Convert string to array
                foreach ($charges as $charge): ?>
                    <div class="dynamic-field">
                        <input type="text" name="potential_charge[]" value="<?php echo htmlspecialchars($charge); ?>" required>
                        <br>
                        <button class="btn" type="button" onclick="removeField(this)">-</button>
                        <br>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn" type="button" onclick="addField('potential_charge')">+ Charge</button>
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
            <input type="text" name="time_served" value="<?php echo htmlspecialchars($record['time_served']); ?>">

            <!-- Case Status -->
            <label>Case Status</label>
            <select name="case_status" required>
                <option value="Open" <?php echo $record['case_status'] == 'Open' ? 'selected' : ''; ?>>Open</option>
                <option value="In Progress" <?php echo $record['case_status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="Closed" <?php echo $record['case_status'] == 'Low' ? 'Close' : ''; ?>>Closed</option>
            </select>

            <!-- Assigned Investigator -->
            <label for="investigator">Assign to Investigator:</label>
            <select id="investigator" name="investigator_id" required>
                <option value="">Assign Investigator</option>
                <?php foreach ($investigators as $investigator): ?>
                    <option value="<?= $investigator['user_id']; ?>"><?= htmlspecialchars($investigator['username']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Update Record</button>
            <br>
        </form>
    </div>
</body>
</html>