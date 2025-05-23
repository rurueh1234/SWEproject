<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) { // Check for user_id in the session
    header("Location: login.php");
    exit();
}

$commuterID = $_SESSION['user_id']; // Retrieve commuterID from session

// Fetch user details
$stmt = $conn->prepare("SELECT name, email, phone, profile_pic FROM commuter WHERE commuterID = ?");
$stmt->bind_param("i", $commuterID);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $profile_pic);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - MetroX</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        nav {
            display: block;
        }
        .profile-container, #edit-form {
            background: #f4f4f4;
            padding: 20px;
            width: 300px;
            margin: 20px auto;
            border-radius: 5px;
        }
        img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        #edit-form {
            display: none;
        }
    </style>
</head>
<body>
<header>
    <img src="logo.png" alt="MetroX Logo" style="width: 400px; height: 100px; object-fit: contain">
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
<div id="profile" class="profile-container">
    <img id="profile-pic" src="<?php echo (!empty($profile_pic) && file_exists('uploads/' . $profile_pic)) ? 'uploads/' . htmlspecialchars($profile_pic) : 'profile.png'; ?>" alt="Profile Picture">
    <h2>Welcome <span><?php echo htmlspecialchars($name); ?></span></h2>
    <p><strong>Email:</strong> <span><?php echo htmlspecialchars($email); ?></span></p>
    <p><strong>Phone Number:</strong> <span><?php echo htmlspecialchars($phone ?: 'Not provided'); ?></span></p>
    <button id="edit-button">Edit</button>
</div>
    <div id="edit-form">
        <h2>Edit Profile</h2>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <label for="edit-name">Userame:</label>
            <input type="text" name="name" id="edit-name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="edit-email">Email:</label>
            <input type="email" name="email" id="edit-email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="edit-phone">Phone Number:</label>
            <input type="text" name="phone" id="edit-phone" value="<?php echo htmlspecialchars($phone ?: ''); ?>">

            <label for="edit-pic">Profile Picture:</label>
            <input type="file" name="profile_pic" id="edit-pic" accept="image/*">

            <button type="submit">Save Changes</button>
            <button type="button" id="cancel-edit">Cancel</button>
        </form>
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
    document.getElementById("edit-button").addEventListener("click", function () {
        document.getElementById("profile").style.display = "none";
        document.getElementById("edit-form").style.display = "block";
    });

    document.getElementById("cancel-edit").addEventListener("click", function () {
        document.getElementById("edit-form").style.display = "none";
        document.getElementById("profile").style.display = "block";
    });
</script>

</body>
</html>
