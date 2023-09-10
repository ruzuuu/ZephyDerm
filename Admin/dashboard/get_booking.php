<?php
include "../../db_connect/config.php";

$query = "SELECT firstname, lastname, number, email, health_concern, services, date, time, appointment_status FROM book1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query Error: ' . mysqli_error($conn));
}

$events = array();
while ($row = mysqli_fetch_assoc($result)) {
    $color = ($row['appointment_status'] === 'Approved') ? '#228B22' : '#21A5B7';

    $event = array(
        'title' => $row['firstname'] . ' ' . $row['lastname'],
        'start' => $row['date'],
        'time' => $row['time'],
        'name' => $row['firstname'] . ' ' . $row['lastname'],
        'number' => $row['number'],
        'email' => $row['email'],
        'healthConcern' => $row['health_concern'],
        'services' => $row['services'],
        'date' => date('M d, Y', strtotime($row['date'])),
        'color' => $color
    );
    $events[] = $event;
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($events);
?>
