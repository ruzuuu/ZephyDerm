<?php
include "../../db_connect/config.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "SELECT * FROM book1 WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $fullName = $row['firstname'] . ' ' . $row['lastname'];
        $formattedDate = date('M d, Y', strtotime($row['date']));

        $output = '
            <table class="table">
                <tbody>
                    <tr>
                        <th>Full Name</th>
                        <td>' . $fullName . '</td>
                    </tr>
                    <tr>
                        <th>Number</th>
                        <td>' . $row['number'] . '</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>' . $row['email'] . '</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>' . $row['health_concern'] . '</td>
                    </tr>
                    <tr>
                        <th>Services</th>
                        <td>' . $row['services'] . '</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>' . $formattedDate . '</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>' . $row['time'] . '</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>' . $row['appointment_status'] . '</td>
                    </tr>
                </tbody>
            </table>
        ';
    } else {
        $output = "No data found for the provided ID.";
    }

    // Close the database connection
    mysqli_close($conn);

    // Return the generated HTML content
    echo $output;
}
?>
