<?php 
include "../function.php";
checklogin();
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
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    </head>
    
    <body>
    <?php
if (isset($_POST['submit'])) {
    require_once "../../db_connect/config.php";

    // Fetch data from the form
    $fname = $_POST['client_firstname'];
    $lname = $_POST['client_lastname'];
    $birthday = $_POST['client_birthday'];
    $contact = $_POST['client_number'];
    $gender = $_POST['client_gender'];
    $email = $_POST['client_email'];
    $econtact = $_POST['client_emergency_person'];
    $relation = $_POST['client_relation'];
    $econtactno = $_POST['client_emergency_contact_number'];

    // Check if the data already exists
    $checkSql = "SELECT COUNT(*) FROM zp_client_record WHERE client_firstname = ? AND client_lastname = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    if ($checkStmt) {
        mysqli_stmt_bind_param($checkStmt, "ss", $fname, $lname);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_bind_result($checkStmt, $count);
        mysqli_stmt_fetch($checkStmt);
        mysqli_stmt_close($checkStmt);

        if ($count > 0) {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Data already exists.'
                });
            </script>";
        } else {
            // Data doesn't exist, proceed with insertion
            $insertSql = "INSERT INTO zp_client_record (client_firstname, client_lastname, client_birthday, client_number, client_gender, client_email, client_emergency_person, client_relation, client_emergency_contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertSql);
            if ($insertStmt) {
                mysqli_stmt_bind_param($insertStmt, "sssssssss", $fname, $lname, $birthday, $contact, $gender, $email, $econtact, $relation, $econtactno);
                if (mysqli_stmt_execute($insertStmt)) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Data added successfully.'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                window.location.href = 'client_record.php';
                            }
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to add data.'
                        });
                    </script>";
                }

                mysqli_stmt_close($insertStmt);
            } else {
                echo "Error in preparing the insertion statement: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error in preparing the check statement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include "../sidebar.php"; ?>
            <div class="col main-content custom-navbar bg-light">
                <?php include "../navbar.php"?>
                <div class="ms-3">
            </div>
                    <div class="m-2 bg-white text-dark p-4 rounded-4 border border-4 shadow-sm">
                            <h2 style="color:6537AE;">Client Record (Edit)</h2>
                                <form method="post">
                                    <div class="row">
                                        <div class="mb-3">
                                            <input class="form-label" type="hidden" name="id">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="mb-2">First Name:</label>
                                            <input class="form-control" type="text" name="client_firstname" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="mb-2">Last Name:</label>
                                            <input class="form-control" type="text" name="client_lastname" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="mb-3">Sex:</label>
                                            <select class="form-control" name="client_gender" required>
                                                <option selected="true" disabled></option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="mb-3">Date of Birth:</label>
                                            <input class="form-control" type="date" name="client_birthday" id="d" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="mb-2 mt-4">EMERGENCY PERSON:</label>
                                        <hr>
                                        <div class="mb-3 col-md-6">
                                            <label class="mb-3">Contact Number:</label>
                                            <input class="form-control" type="text" name="client_number" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="mb-3">Email:</label>
                                            <input class="form-control" type="email" name="client_email" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label class="mb-3">Contact Person:</label>
                                            <input class="form-control" type="text" name="client_emergency_person" required>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="mb-3">Relation:</label>
                                            <input class="form-control" type="text" name="client_relation" required>
                                        </div>
                                        <div class="mb-3 col-md-5">
                                            <label class="mb-3">Contact Person Number:</label>
                                            <input class="form-control" type="text" name="client_emergency_contact_number" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <input class="btn btn-purple bg-purple text-white" type="submit" name="submit" value="Update">
                                        <a class="btn btn-warning" href="client_record.php">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        configuration = {
            allowInput: true,
          dateFormat: "Y-m-d",
          maxDate: "today"

        }
        flatpickr("#d", configuration);
      </script>
    </body>
</html>