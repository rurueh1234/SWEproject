<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: login.php");
  exit();
}

require_once "config.php";

//(Haversine formula)
function haversine($lat1, $lon1, $lat2, $lon2) {
  $earth_radius = 6371;
  $dLat = deg2rad($lat2 - $lat1);
  $dLon = deg2rad($lon2 - $lon1);
  $a = sin($dLat/2) * sin($dLat/2) +
       cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
       sin($dLon/2) * sin($dLon/2);
  $c = 2 * atan2(sqrt($a), sqrt(1-$a));
  return $earth_radius * $c;
}

$stationResult = null;

if (isset($_GET["lat"]) && isset($_GET["lon"])) {
  $userLat = floatval($_GET["lat"]);
  $userLon = floatval($_GET["lon"]);

  $sql = "SELECT name, latitude, longitude, status, metroStatus FROM station";
  $result = $conn->query($sql);

  $closest = null;
  $shortest = PHP_FLOAT_MAX;

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $dist = haversine($userLat, $userLon, $row["latitude"], $row["longitude"]);
      if ($dist < $shortest) {
        $shortest = $dist;
        $closest = $row;
        $closest["distance"] = round($dist, 2);
      }
    }
    $stationResult = $closest;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Search Station - MetroX</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    .status-indicator::before {
      content: "";
      width: 12px;
      height: 12px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 8px;
    }
    .status-on-time::before { background-color: #27ae60; }
    .status-delayed::before { background-color: #f1c40f; }
    .status-cancelled::before { background-color: #c0392b; }
  </style>
</head>
<body>

<header>
  <img src="logo.png" alt="MetroX Logo" style="width: 400px; height: 100px;" />
  <nav>
    <ul>
      
      <li><a href="homepage.php">Home</a></li>
          <li><a href="viewTickets.php">My tickets</a></li>
          <li><a href="journey.php">Journey Planning</a></li>
          <li><a href="metroStatus.php">Status</a></li>
          <li><a href="searchStation.php">Stations</a></li>
          <li><a href="alerts.php">Alerts</a></li>
          <li><a href="buyTicket.php">Buy tickets</a></li>
          <li><a href="account.php">My account</a></li>
          <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<main>
  <h2>Find the Nearest Metro Station</h2>
  <form method="get" action="searchStation.php">
    <label for="lat">Latitude:</label>
    <input type="text" id="lat" name="lat" required>
    <label for="lon">Longitude:</label>
    <input type="text" id="lon" name="lon" required>
    <button type="submit">Find Station</button>
    <button type="button" onclick="useMyLocation()">Use My Location</button>
  </form>

  <div id="search-result">
    <?php if ($stationResult): ?>
      <h3>Nearest Station</h3>
      <p><strong>Name:</strong> <?= $stationResult["name"] ?></p>
      <p><strong>Status:</strong> <?= ucfirst($stationResult["status"]) ?></p>
      <p><strong>Metro Status:</strong>
        <span class="status-indicator status-<?= strtolower(str_replace(' ', '-', $stationResult["metroStatus"])) ?>">
          <?= $stationResult["metroStatus"] ?>
        </span>
      </p>
      <p><strong>Distance:</strong> <?= $stationResult["distance"] ?> km</p>
    <?php elseif (isset($_GET["lat"])): ?>
      <p>No station found.</p>
    <?php endif; ?>
  </div>
</main>

<script>
function useMyLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      document.getElementById("lat").value = position.coords.latitude;
      document.getElementById("lon").value = position.coords.longitude;
    }, () => {
      alert("Failed to get your location.");
    });
  } else {
    alert("Geolocation is not supported by your browser.");
  }
}
</script>

</body>
</html>
