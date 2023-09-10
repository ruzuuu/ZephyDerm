$(document).ready(function() {
    $('#patientTable').DataTable({
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        }
    });
});

function updateStatus(id, status) {
if (status === 'Approved') {
// Show confirmation dialog before approving
Swal.fire({
  title: 'Confirmation',
  text: 'Are you sure you want to approve this appointment?',
  icon: 'warning',
  showCancelButton: true,
  cancelButtonText: 'No',
  confirmButtonText: 'Yes',
}).then((result) => {
  if (result.isConfirmed) {
    // User confirmed, update the status
    performStatusUpdate(id, status);
  }
});
} else if (status === 'Rescheduled') {
// Display the reschedule modal
showRescheduleModal(id);
} else if (status === 'Cancelled') {
// Display the reschedule modal
showCancelledModal(id);
} 
else {
// Update status directly for other cases
performStatusUpdate(id, status);
}
}

function performStatusUpdate(id, status) {
$.ajax({
url: 'update_status.php',
type: 'POST',
data: {
  id: id,
  status: status
},
success: function(response) {
  var statusCell = $('#status_' + id);
  statusCell.text(response);
  statusCell.removeClass().addClass('status-' + status.toLowerCase().replace(' ', '-'));
  var toastrMessage = 'Appointment status is now ' + status + '!';
  toastr.success(toastrMessage, '', {
    progressBar: true,
    timeOut: 3000,
    positionClass: 'toast-top-right'
  });
}
});
}
function showRescheduleModal(id) {
$.ajax({
    url: 'get_reschedule.php',
    type: 'POST',
    data: { id: id, secret_key: 'helloimjaycearon' },
    success: function (response) {
        $('#rescheduleModal .modal-body').html(response);
        $('#rescheduleModal').modal('show');
    }
});
}
function showData(id) {
    $.ajax({
        url: 'get_appointment.php',
        type: 'POST',
        data: {id: id, secret_key: 'helloimjaycearon' },
        success: function(response) {
            $('#dataModal .modal-body').html(response);
            $('#dataModal').modal('show');
        }
    });
}
function showCancelledModal(id) {
$.ajax({
    url: 'get_cancelled.php',
    type: 'POST',
    data: { id: id, secret_key: 'helloimjaycearon' },
    success: function (response) {
        $('#cancelledModal .modal-body').html(response);
        $('#cancelledModal').modal('show');
    }
});
}