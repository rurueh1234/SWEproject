<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Metro Alerts - MetroX</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
      .msg {
        background-color: #c6c6f833;
      }

      .alert-message {
        font-size: 15px;
        font-weight: 500;
        padding: 12px 18px;
        border-radius: 8px;
        background: #f9f9f9;
        color: #333;
        box-shadow: inset 0px 0px 8px rgba(0, 0, 0, 0.08);
      }

      .alert-table tbody tr:hover {
        background-color: rgba(0, 71, 171, 0.1);
        transition: background-color 0.3s ease;
      }

      .alert-table th,
      .alert-table td {
        padding: 12px;
        font-size: 14px;
      }

      .alert-type {
        min-width: 100px;
        padding: 6px 12px;
        font-size: 12px;
      }
    </style>
  </head>
  <body>
    <script>
      if (localStorage.getItem("isLoggedIn") !== "true") {
        window.location.href = "login.html"; // Redirect to login if not logged in
      }
    </script>
  <header>
      <img
        src="logo.png"
        alt="MetroX Logo"
        style="width: 400px; height: 100px; object-fit: contain"
      />
      <nav>
        <ul>
          <li><a href="homepage.php">Home</a></li>
          <li><a href="viewTickets.php">My tickets</a></li>
          <li><a href="journey.php">Journey Planning</a></li>
          <li><a href="metroStatus.php">Status</a></li>
          <li>
            <a href="searchStation.php">Stations</a>
          </li>
          <li><a href="alerts.html">Alerts</a></li>
          <li><a href="buyTicket.php">Buy tickets</a></li>
          <li><a href="account.php">My account</a></li>
          <li><a href="#" id="logout-link">Logout</a></li>
        </ul>
      </nav>
    </header>

    <main>
      <h2>Live Metro Alerts</h2>
      <p>Stay informed about station closures and schedule changes.</p>

      <table class="alert-table">
        <thead>
          <tr>
            <th>Station</th>
            <th>Alert Message</th>
          </tr>
        </thead>
        <tbody id="alert-table-body">
<?php
session_start();
if(!isset($_SESSION['user_id'])){
  echo"<script>window.location.href='login.php';</script>"
}
$connection = mysqli_connect("localhost", "root", "", "metrox");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT stationName, message, alertType FROM alerts ORDER BY createdAt DESC";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['stationName']) . "</td>";
        echo "<td class='msg'>" . htmlspecialchars($row['message']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>No alerts found.</td></tr>";
}

mysqli_close($connection);
?>
</tbody>

      </table>
    </main>

    <footer>
      <div class="footer-content">
        <div class="footer-section about">
          <h3>About MetroX</h3>
          <p>
            MetroX is dedicated to providing real-time metro updates, journey
            planning, and ticket management for a seamless travel experience.
          </p>
        </div>

        <div class="footer-section contact">
          <h3>Contact Us</h3>
          <p>Email: support@metrox.com</p>
          <p>Phone: +1 (123) 456-7890</p>
        </div>

        <div class="footer-section social">
          <h3>Follow Us</h3>
          <a href="#" target="_blank">Facebook</a>
          <a href="#" target="_blank">Twitter</a>
          <a href="#" target="_blank">Instagram</a>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 MetroX. All rights reserved.</p>
      </div>
    </footer>

    <script>
      
      function loadStations() {
        const stations = [
          "Riyadh Season Station",
          "Kingdom Centre Station",
          "Central Station",
          "KAFD",
        ];

        const startingStationSelect =
          document.getElementById("starting-station");
        const destinationStationSelect = document.getElementById(
          "destination-station"
        );

        stations.forEach((station) => {
          let option1 = document.createElement("option");
          option1.value = station;
          option1.textContent = station;
          startingStationSelect.appendChild(option1);

          let option2 = document.createElement("option");
          option2.value = station;
          option2.textContent = station;
          destinationStationSelect.appendChild(option2);
        });

        console.log("Stations loaded in dropdowns.");
      }
      document.addEventListener("DOMContentLoaded", function () {
        const logoutButton = document.getElementById("logout-link");

        if (logoutButton) {
          logoutButton.addEventListener("click", function (event) {
            event.preventDefault();
            localStorage.clear(); // Clear login session
            window.location.href = "login.html"; // Redirect to login page
          });
        }
      });
    </script>
  </body>
</html>
