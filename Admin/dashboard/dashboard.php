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
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
        
    </head>
    <style>
      
        .dropdown-menu {
        margin-left: -2rem;
    }
    @media (max-width: 768px) {
        .dropdown-menu {
            margin-left: -4rem;
        }
    }
    .fade-in {
            animation: fadeIn 1s ease-in-out;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
    <body>   
    <div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include "../sidebar.php"; ?>
        <div class="col main-content custom-navbar bg-light">
        <?php include "../navbar.php"?>
        <div class="container">
            <div class="row ">
                <div class="d-flex justify-content-center align-items-center">
                    <!-- Purple Box -->
                    <div class="bg-purple col-xl-8 p-3 rounded mb-3 shadow mx-2">
                        <!-- Container for Text and Picture  -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="text-white">Hello Arola</h2>
                                <span class="text-white">Today you have <?php ?> appointments for this day!</span>
                            </div>
                            <div class="col-auto">
                                <img src="../image/dashboard.png" alt="" height="160px" class="w-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="container-fluid">
              <div class="row justify-content-center">
                <div class="col-md-5 mt-2 fade-in">
                    <div class="shadow bg-body rounded pt-3 pb-3 d-flex justify-content-center align-items-center flex-shrink-0 h-100">
                        <div>
                          <canvas id="appointmentChart" width="200" height="200"></canvas>
                            <div>
                            <div class="mx-2">
                                    <label>Total Appointments</label>
                                    <label><?php echo $totalApproved + $totalPending + $totalReschedule; ?></label>
                                </div>
                            </div>
                        </div>
                      </div>
                  </div>
                  <!-- Total Patients Chart -->
                  <div class="col-md-5 mt-2 fade-in">
                        <div class="shadow bg-body rounded pt-3 pb-3 d-flex justify-content-center align-items-center flex-shrink-0 h-100">
                            <div>
                                <canvas id="patientChart" width="200" height="200"></canvas>
                                <div>
                                  <div class="mx-2">
                                        <label>Total Patients</label>
                                        <label><?php echo $totalPatient; ?></label>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        
    
                        <div class="text-center shadow mt-4 mb-5 bg-white p-4 rounded mx-4 fade-in" id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
                        
                
            </ul>
        </div>
    </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
  function renderEventDetails(event, element) {
    var time = event.time ? '<br>' + event.time : '';
    element.find('.fc-title').append(time);
    element.addClass('rendered');

    var details = '<div class="event-details">' +
      '<strong>Name:</strong> ' + event.name + '<br>' +
      '<strong>Number:</strong> ' + event.number + '<br>' +
      '<strong>Email:</strong> ' + event.email + '<br>' +
      '<strong>Health Concern:</strong> ' + event.healthConcern + '<br>' +
      '<strong>Services:</strong> ' + event.services + '<br>' +
      '<strong>Date:</strong> ' + event.date + '<br>' +
      '<strong>Time:</strong> ' + event.time +
      '</div>';

    tippy(element[0], {
      placement: 'bottom-start',
      interactive: true,
      content: details,
      allowHTML: true,
      animation: 'scale',
      appendTo: document.body,
    });

    element.css({
      'background-color': event.color,
      'border-color': event.color
    });
  }

  $(document).ready(function() {
    $('#calendar').fullCalendar({
      events: './get_booking.php',
      eventRender: function(event, element) {
        var duplicate = checkForDuplicateEvent(event);

        if (!element.hasClass('rendered') && !duplicate) {
          renderEventDetails(event, element);
        }
      },
      viewRender: function(view, element) {
        $('.event-details').remove(); // Remove existing tooltips
      },
      eventAfterAllRender: function(view) {
        $('.fc-event').each(function() {
          var event = $(this);
          var tooltip = event.data('tippy');
          if (tooltip) {
            tooltip.enable(); // Enable tooltips for rendered events
          }
        });
      }
    });
  });

  // Function to check for duplicate events
  function checkForDuplicateEvent(event) {
    var existingEvents = $('#calendar').fullCalendar('clientEvents');

    for (var i = 0; i < existingEvents.length; i++) {
      var existingEvent = existingEvents[i];

      if (existingEvent.id !== event.id && existingEvent.title === event.title && existingEvent.start.isSame(event.start)) {
        return true;
      }
    }

    return false;
  }
    </script>
<script>
    $(document).ready(function() {
        // Data for the pie charts
        var appointmentChartData = {
            labels: ['Approved', 'Pending', 'Rescheduled'],
            datasets: [{
                data: [<?php echo $totalApproved; ?>, <?php echo $totalPending; ?>, <?php echo $totalReschedule; ?>],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
            }],
        };

        var patientChartData = {
            labels: ['Male Patients', 'Female Patients'],
            datasets: [{
                data: [<?php echo $totalMale; ?>, <?php echo $totalFemale; ?>],
                backgroundColor: ['#28a745', '#dc3545'],
            }]
        };

        // Create pie charts
        var appointmentChart = new Chart(document.getElementById('appointmentChart'), {
            type: 'doughnut',
            data: appointmentChartData,
            options: {
                responsive: true,
            },
        });

        var patientChart = new Chart(document.getElementById('patientChart'), {
            type: 'doughnut',
            data: patientChartData,
            
        });
    });
</script>
    </body>
</html>