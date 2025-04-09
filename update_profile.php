<?php
session_start();
require 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$commuterID = $_SESSION['user_id']; // Get the commuter ID from the session
$new_name = $_POST['name']; // Updated to match field names in the form
$new_email = $_POST['email'];
$new_phone = $_POST['phone'];

// Handle Profile Picture Upload
$profile_pic = null;
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "uploads/";
    $file_name = uniqid() . "_" . basename($_FILES["profile_pic"]["name"]);
    $target_file = $upload_dir . $file_name;
    
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        $profile_pic = $file_name; // Store only the file name for the database
    } else {
        echo "Error uploading file.";
    }
}

// Update user information
if ($profile_pic) {
    $stmt = $conn->prepare("UPDATE commuter SET name=?, email=?, phone=?, profile_pic=? WHERE commuterID=?");
    $stmt->bind_param("ssssi", $new_name, $new_email, $new_phone, $profile_pic, $commuterID);
} else {
    $stmt = $conn->prepare("UPDATE commuter SET name=?, email=?, phone=? WHERE commuterID=?");
    $stmt->bind_param("sssi", $new_name, $new_email, $new_phone, $commuterID);
}

if ($stmt->execute()) {
    // Update session email if changed
    $_SESSION['email'] = $new_email;
    header("Location: account.php");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
