<?php
// Include the database configuration
require_once 'config.php';

// Check if the user is logged in (replicating the JavaScript check in PHP for better security)
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SITE_NAME; ?> - Home</title>
    <link rel="stylesheet" href="styles.css" />
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
                <li><a href="index.php">Home</a></li>
                <li><a href="viewTickets.html">My tickets</a></li>
                <li><a href="journey.html">Journey Planning</a></li>
                <li><a href="metroStatus.html">Status</a></li>
                <li><a href="searchStation.html">Stations</a></li>
                <li><a href="alerts.html">Alerts</a></li>
                <li><a href="buyTicket.html">Buy tickets</a></li>
                <li><a href="account.html">My account</a></li>
                <li><a href="#" id="logout-link">Logout</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>MetroX - The Future of Smart Metro</h2>
        <p>
            Get real-time metro updates, plan your journey, and manage your tickets
            with ease.
        </p>
        <a href="metroStatus.html" class="btn">Check Metro Status</a>
        <a href="buyTicket.html" class="btn">Buy Tickets</a>

        <?php
        // Fetch and display recent alerts from the database
        try {
            $stmt = $pdo->query("SELECT message, timestamp FROM Alert ORDER BY timestamp DESC LIMIT 3");
            $alerts = $stmt->fetchAll();
            if ($alerts) {
                echo "<section>";
                echo "<h3>Recent Alerts</h3>";
                echo "<ul>";
                foreach ($alerts as $alert) {
                    echo "<li>" . htmlspecialchars($alert['message']) . " (Posted: " . $alert['timestamp'] . ")</li>";
                }
                echo "</ul>";
                echo "</section>";
            } else {
                echo "<section><p>No recent alerts.</p></section>";
            }
        } catch (PDOException $e) {
            echo "<section><p>Error fetching alerts: " . $e->getMessage() . "</p></section>";
        }
        ?>
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
            <p>© 2025 <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </footer>

    <script>
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