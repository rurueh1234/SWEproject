<?php
session_start();
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and trim inputs
    $email = trim($_POST["email"]); // Now we use email for login
    $password = trim($_POST["password"]);

    // Validate input fields
    if (empty($email) || empty($password)) {
        $message = "All fields are required.";
    } else {
        // Check the database for the email
        $stmt = $conn->prepare("SELECT commuterID, name, email, password FROM commuter WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                // Set session variables
                $_SESSION["user_id"] = $user["commuterID"]; // Use commuterID
                $_SESSION["username"] = $user["name"];
                $_SESSION["email"] = $user["email"];

                // Redirect to account page
                header("Location: account.php");
                exit;
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "Email not found.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MetroX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Login to MetroX</h1>
        <img src="logo.png" alt="MetroX Logo" style="width: 400px; height: 100px; object-fit: contain">
    </header>
    <main>
        <h2>Login</h2>
        
        <?php if ($message != ""): ?>
            <div class="error" style="color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; font-size: 14px; padding: 10px; border-radius: 5px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form id="login-form" method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
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
</body>
</html>
