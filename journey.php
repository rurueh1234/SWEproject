<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Journey Planning - MetroX</title>
    <link rel="stylesheet" href="styles.css" />
    <script defer src="scripts.js"></script>
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
      <h2>Plan Your Metro Journey</h2>
      <form id="journey-form">
        <label for="start">Starting Station:</label>
        <input type="text" id="start" list="stations" required />

        <label for="destination">Destination Station:</label>
        <input type="text" id="destination" list="stations" required />

        <datalist id="stations">
          <option value="Riyadh Season Station"></option>
          <option value="Kingdom Centre Station"></option>
          <option value="Central Station"></option>
        </datalist>

        <button type="submit">Find Route</button>
      </form>
      <div id="journey-result"></div>
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
      document
        .getElementById("journey-form")
        .addEventListener("submit", function (event) {
          event.preventDefault();
          const start = document.getElementById("start").value;
          const destination = document.getElementById("destination").value;
          if (start === destination) {
            document.getElementById("journey-result").innerHTML =
              "<p class='error'>Start and destination cannot be the same.</p>";
          } else {
            document.getElementById("journey-result").innerHTML = `
                    <h3>Best Route</h3>
                    <p>From: ${start}</p>
                    <p>To: ${destination}</p>
                    <p>Estimated Time: 25 mins (Real-time updates coming soon!)</p>
                `;
          }
        });
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
