<?php
include "../../db_connect/config.php";
$d = $_GET['d'];
$query = "SELECT appointment_slots.slots, COUNT(book1.time) AS num_bookings
          FROM appointment_slots
          LEFT JOIN book1 ON appointment_slots.slots = book1.time AND book1.date = '$d'
          GROUP BY appointment_slots.slots";
$result = mysqli_query($conn, $query);
$slots = array();
while ($row = mysqli_fetch_array($result)) {
    $slot = $row['slots'];
    $num_bookings = $row['num_bookings'];
    $slots[$slot] = $num_bookings;
}

// Fetch the slots_left value from the slots table
$slots_query = "SELECT `slots_left` FROM `slots` WHERE id = 1";
$slots_result = mysqli_query($conn, $slots_query);
$slots_row = mysqli_fetch_assoc($slots_result);
$slots_left_value = $slots_row['slots_left'];

// Add the slots_left value to the response array
$response = array(
    'slots' => $slots,
    'slots_left' => $slots_left_value
);

// Return the response as a JSON-encoded string
echo json_encode($response);
?>
