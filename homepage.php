<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
session_start();

/*if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: login.php");
    exit();
}*/

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'MetroX');
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
                <li><a href="homepage.php">Home</a></li>
                <li><a href="viewTickets.html">My tickets</a></li>
                <li><a href="journey.html">Journey Planning</a></li>
                <li><a href="metroStatus.html">Status</a></li>
                <li><a href="searchStation.html">Stations</a></li>
                <li><a href="alerts.html">Alerts</a></li>
                <li><a href="buyTicket.html">Buy tickets</a></li>
                <li><a href="account.html">My account</a></li>
                <li><a href="?logout=true" id="logout-link">Logout</a></li>
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
        try {
            $result = $conn->query("SELECT message, createdAt FROM alerts ORDER BY createdAt DESC LIMIT 3");

            if ($result && $result->num_rows > 0) {
                echo "<section>";
                echo "<h3>Recent Alerts</h3>";
                echo "<ul>";
                while ($alert = $result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($alert['message']) . " (Posted: " . $alert['createdAt'] . ")</li>";
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
                    localStorage.clear();
                });
            }
        });
    </script>
</body>
</html>