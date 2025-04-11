<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: login.php");
  exit();
}
require_once "config.php";

$stationResults = [];

if (isset($_GET["neighborhood"])) {
  $neighborhood = $conn->real_escape_string($_GET["neighborhood"]);
  $sql = "SELECT name, status, metroStatus FROM station WHERE neighborhood = '$neighborhood'";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $stationResults[] = $row;
    }
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
  <h2>Search Stations by Neighborhood</h2>
  <form method="get" action="searchStation.php">
    <label for="neighborhood">Select Neighborhood:</label>
    <select id="neighborhood" name="neighborhood" required>
      <option value="">-- Choose --</option>
      <option value="Olaya">Olaya</option>
      <option value="Hittin">Hittin</option>
      <option value="KAFD">KAFD</option>
      <option value="Al-Malaz">Al-Malaz</option>
      <option value="Al Aqeeq">Al Aqeeq</option>
    </select>
    <button type="submit">Find Station</button>
  </form>

  <div id="search-result">
    <?php if (!empty($stationResults)): ?>
      <h3>Stations in <?= htmlspecialchars($_GET["neighborhood"]) ?></h3>
      <?php foreach ($stationResults as $station): ?>
        <p><strong>Name:</strong> <?= $station["name"] ?></p>
        <p><strong>Status:</strong> <?= ucfirst($station["status"]) ?></p>
        <p><strong>Metro Status:</strong>
          <span class="status-indicator status-<?= strtolower(str_replace(' ', '-', $station["metroStatus"])) ?>">
            <?= $station["metroStatus"] ?>
          </span>
        </p>
        <hr>
      <?php endforeach; ?>
    <?php elseif (isset($_GET["neighborhood"])): ?>
      <p>No stations found in this neighborhood.</p>
    <?php endif; ?>
  </div>
</main>

<footer>
  <div class="footer-content">
    <div class="footer-section about">
      <h3>About MetroX</h3>
      <p>MetroX is dedicated to providing real-time metro updates, journey planning, and ticket management for a seamless travel experience.</p>
    </div>
    <div class="footer-section contact">
      <h3>Contact Us</h3>
      <p>Email: support@metrox.com</p>
      <p>Phone: +1 (123) 456-7890</p>
    </div>
    <div class="footer-section social">
      <h3>Follow Us</h3>
      <a href="#">Facebook</a>
      <a href="#">Twitter</a>
      <a href="#">Instagram</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2025 MetroX. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
