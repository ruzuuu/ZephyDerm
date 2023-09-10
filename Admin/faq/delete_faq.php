<?php
if (isset($_GET['id'])) {
    include "../../db_connect/config.php";
    $id = $_GET['id'];

    $sql = "DELETE FROM faq WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        echo "FAQ deleted successfully.";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
