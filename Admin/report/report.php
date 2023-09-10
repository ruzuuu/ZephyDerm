<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Appointment</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>

</head>

<body>
<?php
include "../function.php";
checklogin();
?>
<?php
    include "../../db_connect/config.php";
    $currentYear = date("Y");

    $queryApproved = "SELECT COUNT(*) as total_approved FROM book1 WHERE appointment_status = 'approved' AND YEAR(created) = $currentYear";
    $resultApproved = mysqli_query($conn, $queryApproved);
    $rowApproved = mysqli_fetch_assoc($resultApproved);
    $totalApproved = $rowApproved['total_approved'];

    $queryPending = "SELECT COUNT(*) as total_pending FROM book1 WHERE appointment_status = 'pending' AND YEAR(created) = $currentYear";
    $resultPending = mysqli_query($conn, $queryPending);
    $rowPending = mysqli_fetch_assoc($resultPending);
    $totalPending = $rowPending['total_pending'];

    $queryReschedule = "SELECT COUNT(*) as total_reschedule FROM book1 WHERE appointment_status = 'rescheduled' AND YEAR(created) = $currentYear";
    $resultReschedule = mysqli_query($conn, $queryReschedule);
    $rowReschedule = mysqli_fetch_assoc($resultReschedule);
    $totalReschedule = $rowReschedule['total_reschedule'];

    $queryMale = "SELECT COUNT(*) as total_male FROM zp_client_record WHERE client_gender = 'Male'";
    $resultMale = mysqli_query($conn, $queryMale);
    $rowMale = mysqli_fetch_assoc($resultMale);
    $totalMale = $rowMale['total_male'];

    $queryFemale = "SELECT COUNT(*) as total_female FROM zp_client_record WHERE client_gender = 'Female'";
    $resultFemale = mysqli_query($conn, $queryFemale);
    $rowFemale = mysqli_fetch_assoc($resultFemale);
    $totalFemale = $rowFemale['total_female'];

    $queryTotalPatients = "SELECT COUNT(*) as total_patient FROM zp_client_record";
    $resultTotalPatients = mysqli_query($conn, $queryTotalPatients);

    if ($resultTotalPatients) {
        $rowTotalPatients = mysqli_fetch_assoc($resultTotalPatients);
        $totalPatient = $rowTotalPatients['total_patient'];
    } else {
        $totalPatient = 'N/A';
    }
    ?>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include "../sidebar.php"; ?>
        <div class="col main-content custom-navbar bg-light">
        <?php include "../navbar.php"?>
            <div class="container">
                <div class="row mt-3">
                    <div class="col-md-4">
                    <select id="filterDropdown" class="form-select">
                        <option value="appointment">Appointment</option>
                        <option value="patient">Patient</option>
                    </select>
                </div>
                <div class="col-md-4">
    <select id="dateFilter" class="form-select">
        <?php
        // Generate options for the select dropdown using a loop
        for ($year = date("Y"); $year >= 2000; $year--) {
            echo '<option value="' . $year . '">' . $year . '</option>';
        }
        ?>
    </select>
</div>

                <div class="col-md-2">
                    <button id="generateReportButton" class="btn btn-primary">Generate Report</button>
                </div>
                </div>
            </div>

            <div class="container">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div id="appointmentContainer">
                            <canvas id="appointmentChart"></canvas>
                        </div>
                        <div id="patientContainer" style="display: none;">
                            <canvas id="patientChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        
<script>
$(document).ready(function() {
    // Create the appointment chart
    var appointmentChartData = {
        labels: ['Approved', 'Pending', 'Rescheduled'],
        datasets: [{
            data: [<?php echo $totalApproved; ?>, <?php echo $totalPending; ?>, <?php echo $totalReschedule; ?>],
            backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
        }],
    };

    var appointmentChart = new Chart(document.getElementById('appointmentChart'), {
        type: 'doughnut',
        data: appointmentChartData,
    });

    // Create the patient chart
    var patientChartData = {
        labels: ['Male Patients', 'Female Patients'],
        datasets: [{
            data: [<?php echo $totalMale; ?>, <?php echo $totalFemale; ?>],
            backgroundColor: ['#28a745', '#dc3545'],
        }]
    };

    var patientChart = new Chart(document.getElementById('patientChart'), {
        type: 'doughnut',
        data: patientChartData,
    });

    // Handle filter dropdown change
    $('#filterDropdown').change(function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'appointment') {
            $('#appointmentContainer').show();
            $('#patientContainer').hide();
        } else if (selectedValue === 'patient') {
            $('#appointmentContainer').hide();
            $('#patientContainer').show();
        }
    });

    // Handle date filter change
    $('#dateFilter').change(function() {
        updateCharts(); // Call a function to update the charts based on the selected date
    });

    function updateCharts() {
        var selectedYear = $('#dateFilter').val();

        $.ajax({
            url: 'filtered.php', // Replace with your PHP script to fetch data
            method: 'POST',
            data: { year: selectedYear },
            dataType: 'json',
            success: function(response) {
                if (response.noData) {
                $('#appointmentContainer').html('<p>No data available for the selected year.</p>');
                return;
            }

                var filteredApproved = response.totalApproved;
                var filteredPending = response.totalPending;
                var filteredReschedule = response.totalReschedule;

                var appointmentChartData = {
                    labels: ['Approved', 'Pending', 'Rescheduled'],
                    datasets: [{
                        data: [filteredApproved, filteredPending, filteredReschedule],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    }],
                };

                appointmentChart.data = appointmentChartData;
                appointmentChart.update();
            },
            error: function(error) {
                console.error(error);
            }
        });
    }

    // Handle generate report button click
    $('#generateReportButton').click(function() {
        var selectedYear = $('#dateFilter').val();
        var workbook = new ExcelJS.Workbook();
        var worksheet = workbook.addWorksheet('Report');

        // Export appointment chart data
        worksheet.getCell('A1').value = 'Appointment Chart Data';
        worksheet.getCell('A2').value = 'Category';
        worksheet.getCell('B2').value = 'Value';
        
        var appointmentChartData = appointmentChart.data.datasets[0].data;
        var appointmentChartLabels = appointmentChart.data.labels;
        
        for (var i = 0; i < appointmentChartLabels.length; i++) {
            worksheet.getCell('A' + (i + 3)).value = appointmentChartLabels[i];
            worksheet.getCell('B' + (i + 3)).value = appointmentChartData[i];
        }

        // Export patient chart data
        worksheet.getCell('A' + (appointmentChartLabels.length + 4)).value = 'Patient Chart Data';
        worksheet.getCell('A' + (appointmentChartLabels.length + 5)).value = 'Category';
        worksheet.getCell('B' + (appointmentChartLabels.length + 5)).value = 'Value';
        
        var patientChartData = patientChart.data.datasets[0].data;
        var patientChartLabels = patientChart.data.labels;
        
        for (var i = 0; i < patientChartLabels.length; i++) {
            worksheet.getCell('A' + (appointmentChartLabels.length + i + 6)).value = patientChartLabels[i];
            worksheet.getCell('B' + (appointmentChartLabels.length + i + 6)).value = patientChartData[i];
        }

        workbook.xlsx.writeBuffer().then(function(buffer) {
            var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'report.xlsx';
            a.click();
            window.URL.revokeObjectURL(url);
        });
    });
});
</script>



</body>
</html>
