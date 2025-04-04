<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$user_email = $_SESSION['user'];
$new_name = $_POST['username'];
$new_email = $_POST['email'];
$new_phone = $_POST['phone'];

// Handle Profile Picture Upload
$profile_pic = null;
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "uploads/";
    $file_name = uniqid() . "_" . basename($_FILES["profile_pic"]["name"]);
    $target_file = $upload_dir . $file_name;
    
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        $profile_pic = $target_file;
    }
}

// Update user information
if ($profile_pic) {
    $stmt = $conn->prepare("UPDATE commuter SET name=?, email=?, phone=?, profile_pic=? WHERE email=?");
    $stmt->bind_param("sssss", $new_name, $new_email, $new_phone, $profile_pic, $user_email);
} else {
    $stmt = $conn->prepare("UPDATE commuter SET name=?, email=?, phone=? WHERE email=?");
    $stmt->bind_param("ssss", $new_name, $new_email, $new_phone, $user_email);
}

if ($stmt->execute()) {
    $_SESSION['user'] = $new_email;
    header("Location: account.php");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
