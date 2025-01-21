<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require 'config.php';
include 'header.php';

// Fetch crime data based on location
$sql = "SELECT id, crime_name, place_of_crime, latitude, longitude FROM crime_records";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$crimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Location Tracking</title>
    <link rel="stylesheet" href="style/styles.css">
    <!-- Leaflet CSS for map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map { height: 500px; width: 100%; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Track and Categorize Crimes by Location</h1>

        <!-- Filter Form (if needed) -->
        <form method="GET" action="location_tracking.php">
            <label for="crime_name">Crime Name</label>
            <input type="text" name="crime_name" id="crime_name" placeholder="Search by Crime Name">
            
            <label for="place_of_crime">Place of Crime</label>
            <input type="text" name="place_of_crime" id="place_of_crime" placeholder="Search by Location">

            <button type="submit">Filter</button>
        </form>

        <!-- Map -->
        <div id="map"></div>

        <h2>Crime Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Crime Name</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crimes as $crime): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($crime['crime_name']); ?></td>
                        <td><?php echo htmlspecialchars($crime['place_of_crime']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Leaflet.js for Map -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([51.505, -0.09], 2); // Initial coordinates, you can modify to show the central location of your map.

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Adding markers for each crime location
        <?php foreach ($crimes as $crime): ?>
            var lat = <?php echo $crime['latitude']; ?>;
            var lon = <?php echo $crime['longitude']; ?>;
            var crimeName = "<?php echo htmlspecialchars($crime['crime_name']); ?>";
            var location = "<?php echo htmlspecialchars($crime['place_of_crime']); ?>";

            L.marker([lat, lon]).addTo(map)
                .bindPopup("<b>" + crimeName + "</b><br>" + location)
                .openPopup();
        <?php endforeach; ?>
    </script>
</body>
</html>
