<?php
session_start();
require 'db_connect.php'; // Database connection file

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, phone, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $phone, $profile_pic);
$stmt->fetch();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['edit-username'];
    $new_email = $_POST['edit-email'];
    $new_phone = $_POST['edit-phone'];

    // Handle profile picture upload
    if (!empty($_FILES['edit-pic']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["edit-pic"]["name"]);
        move_uploaded_file($_FILES["edit-pic"]["tmp_name"], $target_file);
        $profile_pic = $target_file;
    }

    // Update user information in the database
    $update_query = "UPDATE users SET username = ?, email = ?, phone = ?, profile_pic = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $new_username, $new_email, $new_phone, $profile_pic, $user_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: account.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating profile!";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - MetroX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="logo.png" alt="MetroX Logo" style="width: 400px; height: 100px;">
        <nav>
            <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="viewTickets.php">My Tickets</a></li>
                <li><a href="journey.php">Journey Planning</a></li>
                <li><a href="metroStatus.php">Status</a></li>
                <li><a href="searchStation.php">Stations</a></li>
                <li><a href="alerts.php">Alerts</a></li>
                <li><a href="buyTicket.php">Buy Tickets</a></li>
                <li><a href="account.php">My Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <img src="<?php echo htmlspecialchars($profile_pic ?: 'profile.png'); ?>" alt="Profile Picture">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone); ?></p>
            <button id="edit-button">Edit</button>
        </div>

        <div id="edit-form" style="display: none;">
            <h2>Edit Profile</h2>
            <form action="account.php" method="POST" enctype="multipart/form-data">
                <label for="edit-username">Username:</label>
                <input type="text" name="edit-username" value="<?php echo htmlspecialchars($username); ?>" required>

                <label for="edit-email">Email:</label>
                <input type="email" name="edit-email" value="<?php echo htmlspecialchars($email); ?>" required>

                <label for="edit-phone">Phone Number:</label>
                <input type="text" name="edit-phone" value="<?php echo htmlspecialchars($phone); ?>" required>

                <label for="edit-pic">Profile Picture:</label>
                <input type="file" name="edit-pic" accept="image/*">

                <button type="submit">Save Changes</button>
                <button type="button" id="cancel-edit">Cancel</button>
            </form>
        </div>
    </main>

    <script>
        document.getElementById("edit-button").addEventListener("click", function () {
            document.getElementById("edit-form").style.display = "block";
        });

        document.getElementById("cancel-edit").addEventListener("click", function () {
            document.getElementById("edit-form").style.display = "none";
        });
    </script>

    <footer>
        <p>&copy; 2025 MetroX. All rights reserved.</p>
    </footer>
</body>
</html>
