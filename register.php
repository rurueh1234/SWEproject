<?php
session_start();
require 'config.php'; 

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM commuter WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email is already registered.";
        } else {
            // Hash the password and insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->close(); // Close previous statement

            $stmt = $conn->prepare("INSERT INTO commuter (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Set session variables
                $_SESSION['username'] = $name; // Store username in session
                header("Location: login.php?success=1");
                exit();
            } else {
                $message = "Registration failed. Please try again.";
            }
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MetroX</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Register for MetroX</h1>
        <img src="logo.png" alt="MetroX Logo" style="width: 400px; height: 100px; object-fit: contain">
    </header>
    <main>
        <h2>Create an Account</h2>
        <?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>

        <form id="register-form" method="POST" action="register.php">
            <label for="name">Username</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
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
