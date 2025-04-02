<?php
// send-alert.php
$connection = mysqli_connect("localhost", "root", "", "metrox");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $station = mysqli_real_escape_string($connection, $_POST["station"]);
    $message = mysqli_real_escape_string($connection, $_POST["message"]);
    $type = mysqli_real_escape_string($connection, $_POST["type"]);

    $query = "INSERT INTO alerts (stationName, message, alertType) VALUES ('$station', '$message', '$type')";
    if (mysqli_query($connection, $query)) {
        echo "Alert sent successfully!";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
