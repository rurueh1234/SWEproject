<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Tickets - MetroX</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
      table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
      }
      th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
      }
      .status {
        font-weight: bold;
      }
      .action-btn {
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        color: #fff;
      }
      .edit-btn { background-color: #007bff; }
      .delete-btn { background-color: #dc3545; }
      .alert {
        width: 90%;
        margin: 20px auto;
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        font-weight: bold;
      }
      .success { background-color: #d4edda; color: #155724; }
      .error { background-color: #f8d7da; color: #721c24; }
    </style>
  </head>
  <body>
    <?php
    session_start();
    if (!isset($_SESSION['commuterID'])) {
      echo "<script>window.location.href = 'login.php';</script>";
      exit();
    }

    $alert = "";
    $conn = new mysqli("localhost", "root", "root", "metrox");
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $commuterID = $_SESSION['commuterID'];

    if (isset($_POST['edit_id'])) {
      $edit_id = $_POST['edit_id'];
      $type = $_POST['type'];
      $duration = $_POST['duration'];
      $members = $_POST['members'] ?? null;
      $price = ($type == 'Individual') ? (($duration == 'weekly') ? 5 : 2) : ($members * (($duration == 'weekly') ? 5 : 2));
      $stmt = $conn->prepare("UPDATE ticket SET ticketType=?, validityPeriod=?, NOofMember=?, price=? WHERE ticketID=? AND commuterID=?");
      $stmt->bind_param("ssiiii", $type, $duration, $members, $price, $edit_id, $commuterID);
      if ($stmt->execute()) {
        $alert = "<div class='alert success'>Ticket updated successfully.</div>";
      } else {
        $alert = "<div class='alert error'>Error updating ticket.</div>";
      }
    }

    if (isset($_POST['delete_id'])) {
      $delete_id = $_POST['delete_id'];
      $stmt = $conn->prepare("DELETE FROM ticket WHERE ticketID=? AND commuterID=?");
      $stmt->bind_param("ii", $delete_id, $commuterID);
      if ($stmt->execute()) {
        $alert = "<div class='alert success'>Ticket deleted successfully.</div>";
      } else {
        $alert = "<div class='alert error'>Error deleting ticket.</div>";
      }
    }

    $sql = "SELECT ticketID, ticketType, validityPeriod, NOofMember, price FROM ticket WHERE commuterID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commuterID);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <header>
      <img src="logo.png" alt="MetroX Logo" style="width: 400px; height: 100px; object-fit: contain" />
      <nav>
        <ul>
          <li><a href="homepage.php">Home</a></li>
          <li><a href="viewTickets.php">My tickets</a></li>
          <li><a href="journey.html">Journey Planning</a></li>
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
      <h2 style="text-align:center">Your Purchased Tickets</h2>
      <?php echo $alert; ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Duration</th>
            <th>Family Members</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <form method="POST">
                <td>#<?php echo $row['ticketID']; ?></td>
                <td>
                  <select name="type">
                    <option value="Individual" <?php if ($row['ticketType']=="Individual") echo "selected"; ?>>Individual</option>
                    <option value="Family" <?php if ($row['ticketType']=="Family") echo "selected"; ?>>Family</option>
                  </select>
                </td>
                <td>
                  <select name="duration">
                    <option value="one-time" <?php if ($row['validityPeriod']=="one-time") echo "selected"; ?>>One-Time</option>
                    <option value="daily" <?php if ($row['validityPeriod']=="daily") echo "selected"; ?>>Daily</option>
                    <option value="weekly" <?php if ($row['validityPeriod']=="weekly") echo "selected"; ?>>Weekly</option>
                  </select>
                </td>
                <td>
                  <input type="number" name="members" value="<?php echo $row['NOofMember']; ?>" min="1" <?php if ($row['ticketType'] === 'Individual') echo 'readonly'; ?> />
                </td>
                <td>$<?php echo $row['price']; ?></td>
                <td>
                  <input type="hidden" name="edit_id" value="<?php echo $row['ticketID']; ?>" />
                  <button type="submit" class="action-btn edit-btn">Save</button>
              </form>
              <form method="POST" style="display:inline-block">
                  <input type="hidden" name="delete_id" value="<?php echo $row['ticketID']; ?>" />
                  <button type="submit" class="action-btn delete-btn">Delete</button>
              </form>
                </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
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
          <a href="#" target="_blank">Facebook</a>
          <a href="#" target="_blank">Twitter</a>
          <a href="#" target="_blank">Instagram</a>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 MetroX. All rights reserved.</p>
      </div>
    </footer>

    
  </body>
</html>
