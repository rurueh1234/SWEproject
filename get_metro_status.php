
<?php
header('Content-Type: application/json');
require_once 'config.php';

$sql = "SELECT name, metroStatus FROM station";
$result = $conn->query($sql);

$stations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $stations[] = $row;
    }
}

echo json_encode($stations);
$conn->close();
?>
