// Leave Module JavaScript

function submitLeaveRequest() {
      // Get form data
      var leaveType = document.querySelector('#form-Leave select').value;
      var noOfDays = document.querySelector('#form-Leave input[type="number"]').value;
      var fromDate = document.querySelector('#form-Leave input[type="date"]:nth-of-type(1)').value;
      var toDate = document.querySelector('#form-Leave input[type="date"]:nth-of-type(2)').value;
      var reason = document.querySelector('#form-Leave textarea').value;
      var attachment = document.querySelector('#form-Leave input[type="file"]').files[0];

      // Validate
      if (!leaveType || !noOfDays || !fromDate || !toDate || !reason) {
            alert('Please fill all required fields.');
            return;
      }

      // AJAX submit
      var formData = new FormData();
      formData.append('leave_type', leaveType);
      formData.append('no_of_days', noOfDays);
      formData.append('from_date', fromDate);
      formData.append('to_date', toDate);
      formData.append('reason', reason);
      if (attachment) formData.append('attachment', attachment);

      fetch(base_url + 'admin/hr/submit_leave_request
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Leave request submitted successfully!');
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

function submitRegularisationRequest() {
      // Similar to above, implement for regularisation
      var date = document.querySelector('#form-Regularisation input[type="date"]').value;
      var shift = document.querySelector('#form-Regularisation select').value;
      var punchIn = document.querySelector('#form-Regularisation input[type="time"]:nth-of-type(1)').value;
      var punchOut = document.querySelector('#form-Regularisation input[type="time"]:nth-of-type(2)').value;
      var reason = document.querySelector('#form-Regularisation textarea').value;

      if (!date || !shift || !punchIn || !punchOut || !reason) {
            alert('Please fill all required fields.');
            return;
      }

      var formData = new FormData();
      formData.append('date', date);
      formData.append('shift', shift);
      formData.append('punch_in', punchIn);
      formData.append('punch_out', punchOut);
      formData.append('reason', reason);

      fetch(base_url + 'admin/hr/submit_regularisation_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Regularisation request submitted successfully!');
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

function submitWorkFromHomeRequest() {
      // Implement for Work From Home
      var fromDate = document.querySelector('#form-Work From Home input[type="date"]:nth-of-type(1)').value;
      var toDate = document.querySelector('#form-Work From Home input[type="date"]:nth-of-type(2)').value;
      var workDescription = document.querySelector('#form-Work From Home textarea').value;

      if (!fromDate || !toDate || !workDescription) {
            alert('Please fill all required fields.');
            return;
      }

      var formData = new FormData();
      formData.append('from_date', fromDate);
      formData.append('to_date', toDate);
      formData.append('work_description', workDescription);

      fetch(base_url + 'admin/hr/submit_work_from_home_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Work From Home request submitted successfully!');
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

function submitOnDutyRequest() {
      // Implement for On Duty
      var fromDate = document.querySelector('#form-On Duty input[type="date"]:nth-of-type(1)').value;
      var toDate = document.querySelector('#form-On Duty input[type="date"]:nth-of-type(2)').value;
      var purpose = document.querySelector('#form-On Duty textarea').value;
      var location = document.querySelector('#form-On Duty input[type="text"]').value;

      if (!fromDate || !toDate || !purpose || !location) {
            alert('Please fill all required fields.');
            return;
      }

      var formData = new FormData();
      formData.append('from_date', fromDate);
      formData.append('to_date', toDate);
      formData.append('purpose', purpose);
      formData.append('location', location);

      fetch(base_url + 'admin/hr/submit_on_duty_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('On Duty request submitted successfully!');
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

function submitCompOffRequest() {
      // Implement for Comp Off
      var workedDate = document.querySelector('#form-Comp Off input[type="date"]:nth-of-type(1)').value;
      var compOffDate = document.querySelector('#form-Comp Off input[type="date"]:nth-of-type(2)').value;
      var reason = document.querySelector('#form-Comp Off textarea').value;

      if (!workedDate || !compOffDate || !reason) {
            alert('Please fill all required fields.');
            return;
      }

      var formData = new FormData();
      formData.append('worked_date', workedDate);
      formData.append('comp_off_date', compOffDate);
      formData.append('reason', reason);

      fetch(base_url + 'admin/hr/submit_comp_off_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Comp Off request submitted successfully!');
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

function submitResignationRequest() {
      // Implement for Resignation
      var noticePeriod = document.querySelector('#form-Resignation select').value;
      var lastWorkingDate = document.querySelector('#form-Resignation input[type="date"]').value;
      var reason = document.querySelector('#form-Resignation textarea').value;

      if (!noticePeriod || !lastWorkingDate || !reason) {
            alert('Please fill all required fields.');
            return;
      }

      var formData = new FormData();
      formData.append('notice_period', noticePeriod);
      formData.append('last_working_date', lastWorkingDate);
      formData.append('reason', reason);

      fetch(base_url + 'admin/hr/submit_resignation_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Resignation request submitted successfully!');
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

function submitExpenseRequest() {
      // Implement for Expense
      var expenseType = document.querySelector('#form-Expense select').value;
      var amount = document.querySelector('#form-Expense input[type="number"]').value;
      var date = document.querySelector('#form-Expense input[type="date"]').value;
      var description = document.querySelector('#form-Expense textarea').value;
      var bill = document.querySelector('#form-Expense input[type="file"]').files[0];

      if (!expenseType || !amount || !date || !description) {
            alert('Please fill all required fields.');
            return;
      }

      var formData = new FormData();
      formData.append('expense_type', expenseType);
      formData.append('amount', amount);
      formData.append('date', date);
      formData.append('description', description);
      if (bill) formData.append('bill', bill);

      fetch(base_url + 'admin/hr/submit_expense_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Expense request submitted successfully!');
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

function submitRestrictedHolidayRequest() {
      // Implement for Restricted Holiday
      var holiday = document.querySelector('#form-Restricted Holiday select').value;
      var fromDate = document.querySelector('#form-Restricted Holiday input[type="date"]:nth-of-type(1)').value;
      var toDate = document.querySelector('#form-Restricted Holiday input[type="date"]:nth-of-type(2)').value;
      var reason = document.querySelector('#form-Restricted Holiday textarea').value;

      if (!holiday || !fromDate || !toDate || !reason) {
            alert('Please fill all required fields.');
            return;
      }

      var formData = new FormData();
      formData.append('holiday', holiday);
      formData.append('from_date', fromDate);
      formData.append('to_date', toDate);
      formData.append('reason', reason);

      fetch(base_url + 'admin/hr/submit_restricted_holiday_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Restricted Holiday request submitted successfully!');
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

function updateShortLeaveSummary() {
      var fromInput = document.getElementById('shortLeaveFrom');
      var toInput = document.getElementById('shortLeaveTo');
      var summary = document.getElementById('shortLeaveSummary');
      if (!fromInput || !toInput || !summary) return;

      var from = fromInput.value;
      var to = toInput.value;
      if (!from || !to) {
            summary.textContent = 'Select from and to time.';
            return;
      }

      var fromParts = from.split(':').map(Number);
      var toParts = to.split(':').map(Number);
      var fromMinutes = fromParts[0] * 60 + fromParts[1];
      var toMinutes = toParts[0] * 60 + toParts[1];
      var diff = toMinutes - fromMinutes;
      if (diff <= 0) {
            summary.textContent = 'End time must be after start time.';
            return;
      }

      summary.textContent = 'Duration: ' + diff + ' minutes';
}

function submitShortLeaveRequest() {
      var date = document.querySelector('#form-Short Leave input[type="date"]').value;
      var fromTime = document.getElementById('shortLeaveFrom').value;
      var toTime = document.getElementById('shortLeaveTo').value;
      var reason = document.querySelector('#form-Short Leave textarea').value;

      if (!date || !fromTime || !toTime || !reason) {
            alert('Please fill all required fields.');
            return;
      }

      var fromParts = fromTime.split(':').map(Number);
      var toParts = toTime.split(':').map(Number);
      var fromMinutes = fromParts[0] * 60 + fromParts[1];
      var toMinutes = toParts[0] * 60 + toParts[1];
      var diff = toMinutes - fromMinutes;

      if (diff <= 0) {
            alert('End time must be after start time.');
            return;
      }

      if (diff > 30) {
            alert('Short leave cannot exceed 30 minutes.');
            return;
      }

      var formData = new FormData();
      formData.append('date', date);
      formData.append('from_time', fromTime);
      formData.append('to_time', toTime);
      formData.append('duration_minutes', diff);
      formData.append('reason', reason);

      fetch(base_url + 'admin/hr/submit_short_leave_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  alert('Short Leave request submitted successfully!');
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