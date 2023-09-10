    <?php
    include "../function.php";
    checklogin();
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        include "../../db_connect/config.php";
        $sql = "SELECT * FROM zp_client_record WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $fname = $row['client_firstname'];
            $lname = $row['client_lastname'];
            $dob = $row['client_birthday'];
            $gender = $row['client_gender'];
            $contact = $row['client_number'];
            $email = $row['client_email'];
            $econtact = $row['client_emergency_person'];
            $relation = $row['client_relation'];
            $econtactno = $row['client_emergency_contact_number'];
        } else {
            echo "Record not found";
            exit;
        }

        // Retrieve additional info if available
        $info_sql = "SELECT diagnosis FROM zp_derma_record WHERE patient_id=?";
        $info_stmt = mysqli_prepare($conn, $info_sql);
        mysqli_stmt_bind_param($info_stmt, "i", $id);
        mysqli_stmt_execute($info_stmt);
        $info_result = mysqli_stmt_get_result($info_stmt);
        if (mysqli_num_rows($info_result) > 0) {
            $info_row = mysqli_fetch_assoc($info_result);
            $diagnosis = $info_row['diagnosis'];
        }

        mysqli_stmt_close($stmt);
        mysqli_stmt_close($info_stmt);
        mysqli_close($conn);
    }

    if (isset($_POST['add_diagnosis'])) {
        include "../../db_connect/config.php"; // Include your database configuration

        $id = $_POST['id'];
        $diagnosis = $_POST['diagnosis'];
        $history = $_POST['history'];
        $date_diagnosis = $_POST['date_diagnosis'];
        $management = $_POST['management'];

        // Insert or update diagnosis information in zp_derma_record table
        $info_sql = "INSERT INTO zp_derma_record (patient_id, date_diagnosis, history, management, diagnosis) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE diagnosis=?";
        $info_stmt = mysqli_prepare($conn, $info_sql);
        mysqli_stmt_bind_param($info_stmt, "isssss", $id, $date_diagnosis, $history, $management, $diagnosis, $diagnosis);

        if ($info_stmt->execute()) {
            echo "<script>
            window.addEventListener('DOMContentLoaded', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data Updated successfully.'
                }).then(function() {
                    window.location.href = 'edit_client_record.php?id=" . $id . "';
                });
            });
        </script>";
    } else {
    echo "<script>
            window.addEventListener('DOMContentLoaded', (event) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to add data.'
                });
            });
        </script>";
    }

        // Close the prepared statement and database connection
        mysqli_stmt_close($info_stmt);
        mysqli_close($conn);
    }
    if (isset($_POST['add_appointment'])) {
        include "../../db_connect/config.php"; // Include your database configuration

        $id = $_POST['id'];
        $date = $_POST['date_appointment'];

        // Insert or update diagnosis information in zp_derma_record table
        $info_sql = "INSERT INTO zp_derma_appointment (patient_id, date_appointment) VALUES (?, ?) ON DUPLICATE KEY UPDATE date_appointment=?";
        $info_stmt = mysqli_prepare($conn, $info_sql);
        mysqli_stmt_bind_param($info_stmt, "iss", $id, $date, $date);
        if ($info_stmt->execute()) {
            echo "<script>
            window.addEventListener('DOMContentLoaded', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data Updated successfully.'
                }).then(function() {
                    window.location.href = 'edit_client_record.php?id=" . $id . "';
                });
            });
        </script>";
    } else {
    echo "<script>
            window.addEventListener('DOMContentLoaded', (event) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to add data.'
                });
            });
        </script>";
    }

        // Close the prepared statement and database connection
        mysqli_stmt_close($info_stmt);
        mysqli_close($conn);
    }

    if (isset($_POST['update_client'])) {
        include "../../db_connect/config.php"; // Include your database configuration

        $id = $_POST['id'];
        $fname = $_POST['client_firstname'];
        $lname = $_POST['client_lastname'];
        $dob = $_POST['client_birthday'];
        $gender = $_POST['client_gender'];
        $contact = $_POST['client_number'];
        $email = $_POST['client_email'];
        $econtact = $_POST['client_emergency_person'];
        $relation = $_POST['client_relation'];
        $econtactno = $_POST['client_emergency_contact_number'];

        // Update patient record in zp_client_record table
        $sql_update_client = "UPDATE zp_client_record SET client_firstname=?, client_lastname=?, client_birthday=?, client_gender=?, client_number=?, client_email=?, client_emergency_person=?, client_relation=?, client_emergency_contact_number=? WHERE id=?";
        $stmt_update_client = mysqli_prepare($conn, $sql_update_client);
        mysqli_stmt_bind_param($stmt_update_client, "sssssssssi", $fname, $lname, $dob, $gender, $contact, $email, $econtact, $relation, $econtactno, $id);

        if ($stmt_update_client->execute()) {
            echo "<script>
            window.addEventListener('DOMContentLoaded', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data Updated successfully.'
                }).then(function() {
                    window.location.href = 'client_record.php';
                });
            });
        </script>";
    } else {
    echo "<script>
            window.addEventListener('DOMContentLoaded', (event) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to add data.'
                });
            });
        </script>";
    }
// Fetch appointment dates from the database
$appointments = array();
$info_sql = "SELECT date_appointment FROM zp_derma_appointment WHERE patient_id=?";
$info_stmt = mysqli_prepare($conn, $info_sql);
mysqli_stmt_bind_param($info_stmt, "i", $id);
mysqli_stmt_execute($info_stmt);
$info_result = mysqli_stmt_get_result($info_stmt);

while ($info_row = mysqli_fetch_assoc($info_result)) {
    // Convert and format the date to ISO 8601 format (YYYY-MM-DD)
    $appointmentDate = date('Y-m-d', strtotime($info_row['date_appointment']));
    $appointments[] = array('title' => 'Appointment', 'start' => $appointmentDate);
}
        // Close the prepared statement and database connection
        mysqli_stmt_close($stmt_update_client);
        mysqli_close($conn);
    }
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <link rel="stylesheet" href="../css/style.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
        <style>
        </style>
    </head>
    <body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include "../sidebar.php"; ?>
            <div class="col main-content custom-navbar bg-light">
                <?php include "../navbar.php";?>
                <div class="ms-3">
                    <div class="m-2 bg-white text-dark p-4 rounded-4 border border-4 shadow-sm">
                        <h2 style="color:6537AE;">Client Record (Edit)</h2>
                        <form method="post">
                            <div class="row mb-3">
                                <input class="form-label" type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="col-md-6">
                                    <label class="mb-2">First Name:</label>
                                    <input class="form-control" type="text" name="client_firstname" value="<?php echo $fname; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-2">Last Name:</label>
                                    <input class="form-control" type="text" name="client_lastname" value="<?php echo $lname; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="mb-3">Gender:</label>
                                    <select class="form-control" name="client_gender" required>
                                        <option value="Male" <?php echo ($gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-3">Date of Birth:</label>
                                    <input class="form-control" type="date" name="client_birthday" value="<?php echo $dob; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="mb-2 mt-4">EMERGENCY PERSON:</label>
                                <hr>
                                <div class="col-md-6">
                                    <label class="mb-3">Contact Number:</label>
                                    <input class="form-control" type="text" name="client_number" value="<?php echo $contact; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-3">Email:</label>
                                    <input class="form-control" type="email" name="client_email" value="<?php echo $email; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="mb-3">Contact Person:</label>
                                    <input class="form-control" type="text" name="client_emergency_person" value="<?php echo $econtact; ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="mb-3">Relation:</label>
                                    <input class="form-control" type="text" name="client_relation" value="<?php echo $relation; ?>" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="mb-3">Contact Person Number:</label>
                                    <input class="form-control" type="text" name="client_emergency_contact_number" value="<?php echo $econtactno; ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input class="btn btn-purple bg-purple text-white" type="submit" name="update_diagnosis" value="Update">
                                <a class="btn btn-warning" href="client_record.php">Cancel</a>
                            </div>
                        </form>
                        <form method="post">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">

                                <div class="mb-3">
                                    <label class="mb-3">Date of Diagnosis:</label>
                                    <input class="form-control" name="date_diagnosis" type="date"></input>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-3">History of the Patient:</label>
                                    <textarea class="form-control" name="history" id="summernote" rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-3">Diagnosis of the Patient:</label>
                                    <textarea class="form-control" name="diagnosis" id="summernote" rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="mb-3">Management</label>
                                    <textarea class="form-control" name="management" id="summernote" rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <input class="btn btn-purple bg-purple text-white" type="submit" name="add_diagnosis" value="Add Diagnosis">
                                </div>
                            </form>
                            <form method="post">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">

                                <div class="mb-3">
                                    <label class="mb-3">Date of appointment:</label>
                                    <input class="form-control" name="date_appointment" type="date"></input>
                                </div>
                                <div class="mb-3">
                                    <input class="btn btn-purple bg-purple text-white" type="submit" name="add_appointment" value="Add Appointment">
                                </div>
                            </form>
                        <div>
                            <div class="bg-white text-dark p-4 rounded-4 border border-4 shadow-sm mb-3">
                                <h2 style="color: 6537AE;">Diagnosis</h2>
                                <?php
                                if (isset($_GET['id'])) {
                                    include "../../db_connect/config.php";
                                    $id = $_GET['id'];
                                    $info_sql = "SELECT * FROM zp_derma_record WHERE patient_id=?";
                                    $info_stmt = mysqli_prepare($conn, $info_sql);
                                    mysqli_stmt_bind_param($info_stmt, "i", $id);
                                    mysqli_stmt_execute($info_stmt);
                                    $info_result = mysqli_stmt_get_result($info_stmt);

                                    if (mysqli_num_rows($info_result) > 0) {
                                        echo '<table class="table table-bordered table-striped">';
                                        echo '  <thead>
                                                    <tr>
                                                        <th style="width:20%">Date:</th>
                                                        <th style="width:20%">History:</th>
                                                        <th>Diagnosis:</th>
                                                        <th>Management:</th>
                                                    </tr>
                                                </thead>';
                                        echo '<tbody>';
                                        while ($info_row = mysqli_fetch_assoc($info_result)) {
                                            $date_diagnosis = $info_row['date_diagnosis'];
                                            $history = $info_row['history'];
                                            $diagnosis = $info_row['diagnosis'];
                                            $management = $info_row['management'];
                                            echo '
                                            <tr>
                                                <td>' . date("F jS Y ", strtotime(strval($date_diagnosis))) . '</td>
                                                <td>' . $history . '</td>
                                                <td>' . $diagnosis . '</td>
                                                <td>' . $management . '</td>
                                            </tr>';
                                        }
                                        echo '</tbody></table>';
                                    } else {
                                        echo '<p>No diagnosis information available for this patient.</p>';
                                    }

                                    mysqli_stmt_close($info_stmt);
                                    mysqli_close($conn);
                                }
                                ?>
                            </div>
                        </div>
                        <div>
                                <div id="calendar"></div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
                    // Initialize Summernote on the textarea
                    $(document).ready(function() {
                    $('#summernote').summernote({
                        height: 200 // You can adjust the height as needed
                    });
                    $('#calendar').fullCalendar({
                        editable:true,
                        header:{
                        left:'prev, next today',
                        center:'title',
                        right:'month, agendaWeek, agendaDay'
                        },
                    })
                });
        </script>
    </body>
    </html>
