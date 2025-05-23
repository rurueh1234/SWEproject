
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Metro Status - MetroX</title>
    <link rel="stylesheet" href="styles.css" />

    <style>
      .metro-status-table tr:last-child td {
        border-bottom: none;
      }

      .status-indicator {
        display: inline-flex;
        align-items: center;
      }

      .status-indicator::before {
        content: "";
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
      }

      /* Color coding for status indicator */
      .status-on-time::before {
        background: #27ae60; /* Green */
      }

      .status-delayed::before {
        background: #f1c40f; /* Yellow */
      }

      .status-cancelled::before {
        background: #c0392b; /* Red */
      }

      .metro-status-table td:last-child,
      .metro-status-table th:last-child {
        border-left: none;
        border-right: none;
        border-top: none;
      }
    </style>
  </head>

  <body>
   
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
          <li><a href="searchStation.php">Stations</a></li>
          <li><a href="alerts.php">Alerts</a></li>
          <li><a href="buyTicket.php">Buy tickets</a></li>
          <li><a href="account.php">My account</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>

    <main>
      <h2>Live Metro Updates</h2>
      <p>Check real-time metro arrival times, delays, and disruptions.</p>

      <table class="metro-status-table">
        <thead>
          <tr>
            <th>Station</th>
            <th>Next Arrival</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="metro-status-list">
          <!-- Metro status updates will be dynamically loaded here -->
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
      document.addEventListener("DOMContentLoaded", function () {
        if (document.getElementById("metro-status-list")) {
          loadMetroStatus();
        }
        if (
          document.getElementById("starting-station") &&
          document.getElementById("destination-station")
        ) {
          loadStations();
        }
      });

      
      function loadMetroStatus() {
        fetch('get_metro_status.php')
          .then(response => response.json())
          .then(data => {
            const metroStatusList = document.getElementById("metro-status-list");
            metroStatusList.innerHTML = "";

            data.forEach(entry => {
              const row = document.createElement("tr");
              let statusClass = "";

              if (entry.metroStatus === "On Time") {
                statusClass = "status-on-time";
              } else if (entry.metroStatus === "Delayed") {
                statusClass = "status-delayed";
              } else if (entry.metroStatus === "Cancelled") {
                statusClass = "status-cancelled";
              }

              row.innerHTML = `
                <td>${entry.name}</td>
              <td>${entry.nextArrival ?? '-'}</td>

                <td class="status-indicator ${statusClass}">
                    ${entry.metroStatus}
                </td>
              `;
              metroStatusList.appendChild(row);
            });
          })
          .catch(error => {
            console.error("Error fetching metro status:", error);
          });
      }

       

  

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
            window.location.href = "login.php"; // Redirect to login page
          });
        }
      });
    </script>
  </body>
</html>
