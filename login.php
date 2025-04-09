<?php
session_start();
require 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$commuterID = $_SESSION['user_id']; // Get the commuter ID from the session
$new_name = $_POST['name'];
$new_email = $_POST['email'];
$new_phone = $_POST['phone'];

// Handle Profile Picture Upload
$profile_pic = null;

// Check if a new profile picture is being uploaded
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "uploads/";
    $file_name = uniqid() . "_" . basename($_FILES["profile_pic"]["name"]);
    $target_file = $upload_dir . $file_name;

    // Move uploaded file to the target directory
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        $profile_pic = $file_name; // Store the new file name for the database
    } else {
        echo "Error uploading file.";
        exit(); // Exit if there is an error
    }
}

// Update user information
if ($profile_pic) {
    // If a new profile picture is uploaded
    $stmt = $conn->prepare("UPDATE commuter SET name=?, email=?, phone=?, profile_pic=? WHERE commuterID=?");
    $stmt->bind_param("ssssi", $new_name, $new_email, $new_phone, $profile_pic, $commuterID);
} else {
    // If no new profile picture is uploaded, keep the existing one
    $stmt = $conn->prepare("UPDATE commuter SET name=?, email=?, phone=? WHERE commuterID=?");
    $stmt->bind_param("sssi", $new_name, $new_email, $new_phone, $commuterID);
}

if ($stmt->execute()) {
    // Update session email if changed
    $_SESSION['email'] = $new_email;
    header("Location: account.php");
    exit();
} else {
    // Error updating the profile
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
