<?php
header('Content-Type: application/json');
require_once 'config.php';

$sql = "
SELECT 
    s.name, 
    s.metroStatus, 
    (
        SELECT MIN(a.arrivalTime)
        FROM arrival_times a
        WHERE a.stationID = s.stationID AND a.arrivalTime > CURTIME()
    ) AS nextArrival
FROM station s
";

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
