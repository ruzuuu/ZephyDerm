<?php
include "../../db_connect/config.php";

if (isset($_POST['year'])) {
    $selectedYear = $_POST['year'];

    // Modify your SQL queries to fetch data based on the selected year
    $queryApproved = "SELECT COUNT(*) as total_approved FROM book1 WHERE appointment_status = 'approved' AND YEAR(created) = $selectedYear";
    $queryPending = "SELECT COUNT(*) as total_pending FROM book1 WHERE appointment_status = 'pending' AND YEAR(created) = $selectedYear";
    $queryReschedule = "SELECT COUNT(*) as total_reschedule FROM book1 WHERE appointment_status = 'rescheduled' AND YEAR(created) = $selectedYear";

    $resultApproved = mysqli_query($conn, $queryApproved);
    $resultPending = mysqli_query($conn, $queryPending);
    $resultReschedule = mysqli_query($conn, $queryReschedule);

    $rowApproved = mysqli_fetch_assoc($resultApproved);
    $rowPending = mysqli_fetch_assoc($resultPending);
    $rowReschedule = mysqli_fetch_assoc($resultReschedule);

    $filteredData = array(
        'totalApproved' => $rowApproved['total_approved'],
        'totalPending' => $rowPending['total_pending'],
        'totalReschedule' => $rowReschedule['total_reschedule']
    );
    

    echo json_encode($filteredData);
} else {
    echo json_encode(array()); // Empty response if year is not provided
}
?>
