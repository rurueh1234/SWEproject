<?php
session_start();
require 'config.php';

// Start message as an empty string
$message = "";
$redirect = false; // Flag to trigger redirect

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and trim inputs
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm_password"]);  // Added for password confirmation

    // Validate input fields
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } elseif (preg_match('/^[0-9]/', $name)) {
        $message = "Name cannot start with a number. Please use a valid name format.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $message = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $message = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $message = "Password must contain at least one number.";
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $message = "Password must contain at least one special character.";
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
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO commuter (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                $message = "Registration successful! Redirecting to login page...";
                $redirect = true;  // Set flag to trigger redirect
            } else {
                $message = "Something went wrong. Please try again.";
            }
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
    <title>Register - MetroX</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        <?php if ($redirect): ?>
            // JavaScript to redirect to login page after 3 seconds
            setTimeout(function(){
                window.location.href = "login.php"; // Redirect after 3 seconds
            }, 3000); // 3000 milliseconds = 3 seconds
        <?php endif; ?>
    </script>
</head>
<body>
    <header>
        <h1>Register for MetroX</h1>
        <img src="logo.png" alt="MetroX Logo" style="width: 400px; height: 100px; object-fit: contain">
    </header>
    <main>
        <h2>Create an Account</h2>
        
        <?php if ($message != ""): ?>
            <div class="<?php echo (strpos($message, 'success') !== false) ? 'success' : 'error'; ?>"
                 style="<?php echo (strpos($message, 'success') !== false) ? 'color: green; background-color: #d4edda; border: 1px solid #c3e6cb;' : 'color: red; background-color: #f8d7da; border: 1px solid #f5c6cb;'; ?> 
                 font-size: 14px; padding: 10px; border-radius: 5px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form id="register-form" method="POST" action="register.php">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label> <!-- Added confirm password -->
            <input type="password" id="confirm_password" name="confirm_password" required>

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
