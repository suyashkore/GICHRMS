// Leave Module JavaScript

function submitLeaveRequest() {
      var leaveType = document.getElementById('leave_type').value;
      if (!leaveType) {
            alert('Please select a leave type.');
            return;
      }

      var formData = new FormData();
      formData.append('leave_type', leaveType);
      formData.append('duration', document.getElementById('leave_duration').value || 'Full Day');
      formData.append('no_of_days', document.getElementById('leave_days').value || '');

      if (['CSL', 'LOP', 'ADL'].includes(leaveType)) {
            var fromDate = document.getElementById('leave_from_date').value;
            var toDate = document.getElementById('leave_to_date').value;
            var reason = document.getElementById('leave_reason').value;
            var attachment = document.getElementById('leave_attachment').files[0];

            if (!fromDate || !toDate || !reason) {
                  alert('Please fill all required fields.');
                  return;
            }

            formData.append('from_date', fromDate);
            formData.append('to_date', toDate);
            formData.append('reason', reason);
            if (attachment) {
                  formData.append('attachment', attachment);
            }
      } else if (leaveType === 'CPL') {
            var workedDate = document.getElementById('comp_off_worked_date').value;
            var compOffDate = document.getElementById('comp_off_date').value;
            var notes = document.getElementById('comp_off_notes').value;

            if (!workedDate || !compOffDate || !notes) {
                  alert('Please fill all required fields.');
                  return;
            }

            formData.append('from_date', workedDate);
            formData.append('to_date', compOffDate);
            formData.append('reason', notes);
      } else if (leaveType === 'MEL') {
            var deliveryDate = document.getElementById('maternity_delivery_date').value;
            var startDate = document.getElementById('maternity_start_date').value;
            var endDate = document.getElementById('maternity_end_date').value;
            var medicalCertificate = document.getElementById('maternity_medical_certificate').files[0];
            var alternateContact = document.getElementById('maternity_alt_contact').value;
            var emergencyContact = document.getElementById('maternity_emergency_contact').value;
            var consignee = document.getElementById('maternity_consignee').value;
            var handoverPlan = document.getElementById('maternity_handover_plan').value;
            var notes = document.getElementById('maternity_notes').value;

            if (!deliveryDate || !startDate || !endDate || !alternateContact || !emergencyContact || !consignee || !handoverPlan || !notes) {
                  alert('Please fill all required fields.');
                  return;
            }

            formData.append('expected_delivery_date', deliveryDate);
            formData.append('from_date', startDate);
            formData.append('to_date', endDate);
            formData.append('alternate_contact_no', alternateContact);
            formData.append('emergency_contact_no', emergencyContact);
            formData.append('out_of_office_consignee', consignee);
            formData.append('work_handover_plan', handoverPlan);
            formData.append('reason', notes);
            if (medicalCertificate) {
                  formData.append('medical_certificate', medicalCertificate);
            }
      } else {
            alert('This leave type is not supported yet.');
            return;
      }

      fetch(base_url + 'admin/hr/submit_leave_request', {
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

function handleLeaveTypeChange() {
      var leaveType = document.getElementById('leave_type').value;
      var baseFields = document.getElementById('leave-base-fields');
      var compOffFields = document.getElementById('leave-comp-off-fields');
      var maternityFields = document.getElementById('leave-maternity-fields');

      baseFields.style.display = 'none';
      compOffFields.style.display = 'none';
      maternityFields.style.display = 'none';

      if (['CSL', 'LOP', 'ADL'].includes(leaveType)) {
            baseFields.style.display = 'block';
            updateLeaveDays();
      } else if (leaveType === 'CPL') {
            compOffFields.style.display = 'block';
            updateCompOffDays();
      } else if (leaveType === 'MEL') {
            maternityFields.style.display = 'block';
            updateMaternityDuration();
      }
}

function setLeaveDuration(button, value) {
      var buttons = document.querySelectorAll('#leaveDurationControl .seg-btn');
      buttons.forEach(function(btn) {
            btn.classList.remove('active');
      });
      button.classList.add('active');
      document.getElementById('leave_duration').value = value;
      updateLeaveDays();
}

function updateLeaveDays() {
      var fromDate = document.getElementById('leave_from_date').value;
      var toDate = document.getElementById('leave_to_date').value;
      var duration = document.getElementById('leave_duration').value;
      var leaveDaysInput = document.getElementById('leave_days');
      var leaveDaysDisplay = document.getElementById('leave_days_display');

      if (!fromDate || !toDate) {
            leaveDaysInput.value = '';
            if (leaveDaysDisplay) leaveDaysDisplay.value = '';
            return;
      }

      var start = new Date(fromDate);
      var end = new Date(toDate);
      if (end < start) {
            leaveDaysInput.value = '';
            if (leaveDaysDisplay) leaveDaysDisplay.value = '';
            return;
      }

      var diffDays = Math.round((end - start) / (1000 * 60 * 60 * 24)) + 1;
      var days = diffDays;
      if (duration === 'First Half' || duration === 'Second Half') {
            if (diffDays === 1) {
                  days = 0.5;
            } else {
                  days = diffDays - 0.5;
            }
      }

      leaveDaysInput.value = days;
      if (leaveDaysDisplay) leaveDaysDisplay.value = days;
}

function updateCompOffDays() {
      var workedDate = document.getElementById('comp_off_worked_date').value;
      var compOffDate = document.getElementById('comp_off_date').value;
      var leaveDaysInput = document.getElementById('leave_days');

      if (!workedDate || !compOffDate) {
            leaveDaysInput.value = '';
            return;
      }

      var start = new Date(workedDate);
      var end = new Date(compOffDate);
      if (end < start) {
            leaveDaysInput.value = '';
            return;
      }

      var diffDays = Math.round((end - start) / (1000 * 60 * 60 * 24)) + 1;
      leaveDaysInput.value = diffDays;
}

function updateMaternityDuration() {
      var startDate = document.getElementById('maternity_start_date').value;
      var endDate = document.getElementById('maternity_end_date').value;
      var totalDaysInput = document.getElementById('maternity_total_days');
      var leaveDaysInput = document.getElementById('leave_days');

      if (!startDate || !endDate) {
            if (totalDaysInput) totalDaysInput.value = '';
            leaveDaysInput.value = '';
            return;
      }

      var start = new Date(startDate);
      var end = new Date(endDate);
      if (end < start) {
            if (totalDaysInput) totalDaysInput.value = '';
            leaveDaysInput.value = '';
            return;
      }

      var diffDays = Math.round((end - start) / (1000 * 60 * 60 * 24)) + 1;
      if (totalDaysInput) totalDaysInput.value = diffDays;
      leaveDaysInput.value = diffDays;
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