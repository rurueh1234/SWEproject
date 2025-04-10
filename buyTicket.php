<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "root", "metrox");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $commuterID = $_SESSION['commuterID'];
    $ticketType = $_POST['ticketType'];
    $validityPeriod = $_POST['validityPeriod'];
    $NOofMember = isset($_POST['NOofMember']) ? intval($_POST['NOofMember']) : null;

    if ($ticketType === 'Individual') {
        $price = ($validityPeriod === 'weekly') ? 5 : 2;
        $NOofMember = null;
    } else {
        $basePrice = ($validityPeriod === 'weekly') ? 5 : 2;
        $NOofMember = max(2, $NOofMember);
        $price = $basePrice * $NOofMember;
    }

    $stmt = $conn->prepare("INSERT INTO ticket (commuterID, ticketType, validityPeriod, NOofMember, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $commuterID, $ticketType, $validityPeriod, $NOofMember, $price);

    if ($stmt->execute()) {
        echo "<script>alert('Ticket purchased successfully!');</script>";
    } else {
        echo "<script>alert('Error purchasing ticket.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Buy Ticket - MetroX</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
      form label,
      form select,
      form input {
        display: block;
        width: 100%;
        margin: 10px 0;
      }
      form button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
      }
      form button:hover {
        background-color: #0056b3;
      }
      #familyDiv {
        display: none;
      }
      #ticketPrice {
        font-weight: bold;
        font-size: 18px;
        color: #333;
        margin: 10px 0;
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
      <h2 style="text-align:center">Purchase MetroX Ticket</h2>
      <form method="POST">
        <label>Ticket Type:</label>
        <select name="ticketType" id="ticketType" onchange="toggleFamily()">
          <option value="Individual">Individual</option>
          <option value="Family">Family</option>
        </select>

        <div id="familyDiv">
          <label>Number of Family Members:</label>
          <input type="number" name="NOofMember" min="2" max="10" value="2" />
        </div>

        <label>Ticket Duration:</label>
        <select name="validityPeriod" id="validityPeriod">
          <option value="one-time">One-Time</option>
          <option value="daily">Daily</option>
          <option value="weekly">Weekly</option>
        </select>

        <p><strong>Price:</strong> <span id="ticketPrice">$2</span></p>

        <button type="submit">Buy Ticket</button>
      </form>
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
      function toggleFamily() {
        const type = document.getElementById("ticketType").value;
        const familyDiv = document.getElementById("familyDiv");
        familyDiv.style.display = (type === "Family") ? "block" : "none";
        updatePrice();
      }

      function updatePrice() {
        const type = document.getElementById("ticketType").value;
        const duration = document.getElementById("validityPeriod").value;
        const membersInput = document.querySelector("[name='NOofMember']");
        const members = parseInt(membersInput?.value || 2);
        let price = 2;

        if (type === "Individual") {
          price = (duration === "weekly") ? 5 : 2;
        } else {
          const base = (duration === "weekly") ? 5 : 2;
          price = base * Math.max(2, members);
        }

        document.getElementById("ticketPrice").innerText = `$${price}`;
      }

      document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("ticketType").addEventListener("change", updatePrice);
        document.getElementById("validityPeriod").addEventListener("change", updatePrice);
        document.querySelector("[name='NOofMember']").addEventListener("input", updatePrice);
        updatePrice(); // initial call
      });
    </script>
  </body>
</html>
