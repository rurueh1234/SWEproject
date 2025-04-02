<?php
// alerts.php
$connection = mysqli_connect("localhost", "root", "", "metrox");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT stationName, message, alertType FROM alerts ORDER BY createdAt DESC";
$result = mysqli_query($connection, $query);

$alerts = array();
while ($row = mysqli_fetch_assoc($result)) {
    $alerts[] = $row;
}

header("Content-Type: application/json");
echo json_encode($alerts);
?>
