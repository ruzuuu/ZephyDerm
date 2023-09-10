<?php
// Get the appointment ID and new status from the AJAX request
$id = $_POST['id'];
$status = $_POST['status'];

// Update the status in the database
include "../db_connect/config.php";
$sql = "UPDATE book1 SET appointment_status = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $status, $id);

if (mysqli_stmt_execute($stmt)) {
    // Return the updated status as the response
    echo $status;
} else {
    // Return an error message if the update fails
    echo "Error updating status.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>