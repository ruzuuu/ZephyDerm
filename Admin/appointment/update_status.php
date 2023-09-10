<?php
include "../../db_connect/config.php";

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $query = "UPDATE book1 SET appointment_status = '$status' WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo $status;
    } else {
        echo 'Error updating appointment status: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request';
}
?>
