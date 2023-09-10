<?php
include "../function.php";
checklogin();
?>
<?php
if (isset($_POST['submit'])) {
    include "../../db_connect/config.php";
    $question =$_POST['question'];
    $answer = $_POST['answer'];
    $sql = "INSERT INTO faq (question, answer) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ss", $question, $answer);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: faq.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Dashboard</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>
    
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include "../sidebar.php"; ?>
        <div class="col main-content custom-navbar bg-light">
            <?php include "../navbar.php"?>
            <button type="button" class="btn btn-primary" onclick="addFaqModal()">Insert</button>
            <table id="faqTable" class="faqTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                include "../../db_connect/config.php";
                $result = mysqli_query($conn, "SELECT * FROM faq");
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <td><?php echo $row['question'] ?></td>
                        <td><?php echo strlen($row['answer']) > 50 ? substr($row['answer'], 0, 50) . '...' : $row['answer']; ?></td>
                        <td class="action-buttons">
                            <button onclick="showRescheduleModal('<?php echo $row['id']; ?>')" class="btn btn-purple bg-purple text-white">Edit</button>
                            <a href="#" onclick="deleteFAQ(<?php echo $row['id']; ?>)" class="link-dark">
                                <span class="las la-trash" style="font-size: 20px; color:#222; margin-left:40px;"></span>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleModalLabel">Edit FAQ Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Reschedule form will be dynamically inserted here -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rescheduleModalLabel">Edit FAQ Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Reschedule form will be dynamically inserted here -->
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- Include Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Summernote on the textarea
        $('.summernote').summernote();

        $('#faqTable').DataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            }
        });
    });

    function showRescheduleModal(id) {
        $.ajax({
            url: 'edit_faq.php',
            type: 'GET',
            data: { id: id },
            success: function (response) {
                $('#rescheduleModal .modal-body').html(response);
                $('#rescheduleModal').modal('show');
            }
        });
    }
    function addFaqModal(id) {
        $.ajax({
            url: 'add_faq.php',
            type: 'POST',
            data: { id: id },
            success: function (response) {
                $('#addFaqModal .modal-body').html(response);
                $('#addFaqModal').modal('show');
            }
        });
    }

    $('#rescheduleModal').on('hidden.bs.modal', function (e) {
        $('.summernote').summernote('destroy');
    });
    function deleteFAQ(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete_faq.php',
                    type: 'GET',
                    data: { id: id },
                    success: function (response) {
                        // Handle the response or refresh the page if needed
                        // For example, you can redirect to faq.php after successful deletion:
                        window.location.href = 'faq.php';
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                        // Show an error alert using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Error deleting FAQ: ' + xhr.responseText,
                        });
                    }
                });
            }
        });

        // Prevent the default behavior of the link
        return false;
    }
</script>

</script>

</body>
</html>
