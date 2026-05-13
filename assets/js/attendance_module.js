// Attendance Module JavaScript

function submitPunchCorrectionRequest() {
      // Get form data
      var date = document.querySelector('#form-Punch Correction input[type="date"]').value;
      var shift = document.querySelector('#form-Punch Correction select').value;
      var punchIn = document.querySelector('#form-Punch Correction input[type="time"]:nth-of-type(1)').value;
      var punchOut = document.querySelector('#form-Punch Correction input[type="time"]:nth-of-type(2)').value;
      var reason = document.querySelector('#form-Punch Correction textarea').value;

      // Validate
      if (!date || !shift || !punchIn || !punchOut || !reason) {
            alert('Please fill all required fields.');
            return;
      }

      // AJAX submit
      var formData = new FormData();
      formData.append('date', date);
      formData.append('shift', shift);
      formData.append('punch_in', punchIn);
      formData.append('punch_out', punchOut);
      formData.append('reason', reason);

      fetch(base_url + 'admin/hr/submit_punch_correction_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Punch Correction request submitted successfully!');
                  closeForm();
            } else {
                  alert('Error: ' + data.message);
            }
      })
      .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the request.');
      });
}

function submitMissingAttendanceRequest() {
      // Get form data
      var date = document.querySelector('#form-Missing Attendance input[type="date"]').value;
      var attendanceStatus = document.querySelector('#form-Missing Attendance select').value;
      var reason = document.querySelector('#form-Missing Attendance textarea').value;
      var proof = document.querySelector('#form-Missing Attendance input[type="file"]').files[0];

      // Validate
      if (!date || !attendanceStatus || !reason) {
            alert('Please fill all required fields.');
            return;
      }

      // AJAX submit
      var formData = new FormData();
      formData.append('date', date);
      formData.append('attendance_status', attendanceStatus);
      formData.append('reason', reason);
      if (proof) formData.append('proof', proof);

      fetch(base_url + 'admin/hr/submit_missing_attendance_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Missing Attendance request submitted successfully!');
                  closeForm();
            } else {
                  alert('Error: ' + data.message);
            }
      })
      .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the request.');
      });
}