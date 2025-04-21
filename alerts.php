<?php
session_start();
require 'config.php'; // Assuming this contains the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Fetch alerts from the database
$query = "SELECT stationName, message, alertType FROM alerts ORDER BY createdAt DESC LIMIT 10"; // Fetch the latest 10 alerts
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the alerts as an array
$alerts = [];
while ($row = $result->fetch_assoc()) {
    $alerts[] = $row;
}
?>

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
        background-color: #f2f2f2;
        border-radius: 4px;
      }

      .alert-type.Delay {
        background-color: #ffcc00;
        color: #fff;
      }

      .alert-type.Closure {
        background-color: #d9534f;
        color: #fff;
      }

      .alert-type.Smooth {
        background-color: #5bc0de;
        color: #fff;
      }
    </style>
</head>
<body>
    <header>
        <img src="logo.png" alt="MetroX Logo" style="width: 400px; height: 100px; object-fit: contain" />
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
            <li><a href="#" id="logout-link">Logout</a></li>
          </ul>
        </nav>
    </header>

    <main>
        <h2>Live Metro Alerts</h2>
        <p>Stay informed about station closures and schedule changes.</p>

        <!-- Table to display alerts -->
        <table class="alert-table">
            <thead>
                <tr>
                    <th>Station</th>
                    <th>Alert Type</th>
                    <th>Alert Message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alerts as $alert): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($alert['stationName']); ?></td>
                        <td class="alert-type <?php echo htmlspecialchars($alert['alertType']); ?>">
                            <?php echo htmlspecialchars($alert['alertType']); ?>
                        </td>
                        <td class="msg"><?php echo htmlspecialchars($alert['message']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h3>About MetroX</h3>
                <p>MetroX provides real-time metro updates, journey planning, and ticket management for a seamless travel experience.</p>
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
        // Logout functionality
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
