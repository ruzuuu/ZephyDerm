<?php include "../function.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    
    <style>
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
        .action-buttons a {
            margin-right: 10px;
            color: #222;
            text-decoration: none;
        }

        .action-buttons a:hover {
            color: #777;
        }

        .dataTables_filter input {
            margin-top: 10px;
            margin-right: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include "../sidebar.php"; ?>
            <div class="col main-content custom-navbar bg-light">
                <?php include "../navbar.php"?>
                <div>
                    <a href="add_client_record.php">
                        <button class="create_patients btn btn-purple bg-purple text-white ms-3 mb-3 mt-2 col-xl-2">CREATE CLIENT</button>
                    </a>
                </div>
                <div>
                    <div class="ms-3">
                        <table id="clientTable" class="display nowrap responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Day of Birth</th>
                                    <th>Contact Number</th>
                                    <th>Email Address</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "../../db_connect/config.php";
                                $result = mysqli_query($conn, "SELECT * FROM zp_client_record");

                                while ($row = mysqli_fetch_array($result)) {
                                    ?>

                                    <tr>
                                        <td><?php echo $row['client_firstname']?></td>
                                        <td><?php echo $row['client_lastname']?></td>
                                        <td><?php echo $row['client_birthday']?></td>
                                        <td><?php echo $row['client_number']?></td>
                                        <td><?php echo $row['client_email']?></td>
                                        <td class="action-buttons">
                                            <a href="view.php?id=<?php echo $row['id']?>" class="btn btn-primary text-white"> View Data</a>
                                            <a href="edit_client_record.php?id=<?php echo $row['id']?>" class="btn btn-warning">Edit Data</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#clientTable').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });
        });
    </script>
</body>
</html>
