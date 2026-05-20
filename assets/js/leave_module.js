// Leave Module JavaScript

function showToast(message, type = 'info') {
      const toast = document.createElement('div');
      toast.className = `toast-notification toast-${type}`;
      toast.textContent = message;
      toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#3b82f6'};
            color: white;
            padding: 14px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            max-width: 350px;
            font-size: 14px;
            animation: slideIn 0.3s ease-out;
      `;
      document.body.appendChild(toast);
      setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
      }, 3000);
}

// Add CSS animations if not already present
if (!document.getElementById('toast-styles')) {
      const style = document.createElement('style');
      style.id = 'toast-styles';
      style.textContent = `
            @keyframes slideIn {
                  from { transform: translateX(400px); opacity: 0; }
                  to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                  from { transform: translateX(0); opacity: 1; }
                  to { transform: translateX(400px); opacity: 0; }
            }
      `;
      document.head.appendChild(style);
}

function submitLeaveRequest() {
      var leaveTypeId = document.getElementById('leave_type').value;
      var leaveTypeOption = document.getElementById('leave_type').selectedOptions[0];
      var leaveTypeCode = leaveTypeOption ? leaveTypeOption.dataset.code : '';
      if (!leaveTypeId) {
            showToast('Please select a leave type.', 'error');
            return;
      }

      function mapDurationToApiType(duration) {
            if (duration === 'First Half') return 'first_half';
            if (duration === 'Second Half') return 'second_half';
            return 'full_day';
      }

      if (['CSL', 'LOP', 'ADL', 'CPL', 'MEL'].includes(leaveTypeCode)) {
            var fromDate = document.getElementById('leave_from_date').value;
            var toDate = document.getElementById('leave_to_date').value;
            var reason = document.getElementById('leave_reason').value;
            var notifyTeam = document.getElementById('notify_team').checked;

            if (['CSL', 'LOP', 'ADL'].includes(leaveTypeCode)) {
                  if (!fromDate || !toDate || !reason) {
                        showToast('Please fill all required fields.', 'error');
                        return;
                  }
            }

            if (!ciStaffEmail) {
                  showToast('Unable to determine current user email for API submission.', 'error');
                  return;
            }

            var formData = new FormData();
            formData.append('leave_type_id', leaveTypeId);
            formData.append('staff_email', ciStaffEmail);
            formData.append('type', mapDurationToApiType(document.getElementById('leave_duration').value || 'Full Day'));
            formData.append('notify_team', notifyTeam ? '1' : '0');

            if (leaveTypeCode === 'CSL' || leaveTypeCode === 'LOP' || leaveTypeCode === 'ADL') {
                  formData.append('from_date', fromDate);
                  formData.append('to_date', toDate);
                  formData.append('reason', reason);
            }

            if (leaveTypeCode === 'CPL') {
                  var workedDate = document.getElementById('comp_off_worked_date').value;
                  var compOffDate = document.getElementById('comp_off_date').value;
                  var notes = document.getElementById('comp_off_notes').value;

                  if (!workedDate || !compOffDate || !notes) {
                        showToast('Please fill all required fields.', 'error');
                        return;
                  }

                  // Use comp-off date for the actual leave day; worked date is metadata.
                  formData.append('from_date', compOffDate);
                  formData.append('to_date', compOffDate);
                  formData.append('reason', notes);
                  formData.append('comp_off_notes', notes);
                  formData.append('worked_date', workedDate);
            }

            if (leaveTypeCode === 'MEL') {
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
                        showToast('Please fill all required fields.', 'error');
                        return;
                  }

                  formData.append('from_date', startDate);
                  formData.append('to_date', endDate);
                  formData.append('expected_delivery_date', deliveryDate);
                  formData.append('alternate_contact_no', alternateContact);
                  formData.append('emergency_contact_no', emergencyContact);
                  formData.append('out_of_office_consignee', consignee);
                  formData.append('work_handover_plan', handoverPlan);
                  formData.append('reason', notes);
                  formData.append('maternity_notes', notes);
                  if (medicalCertificate) {
                        formData.append('medical_certificate', medicalCertificate);
                  }
            }

            var generalAttachment = document.getElementById('leave_attachment').files[0];
            if (generalAttachment) {
                  formData.append('attachment', generalAttachment);
            }

            fetch(laravelWebBase + 'leave/apply-ci-web', {
                  method: 'POST',
                  headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                  },
                  body: formData
            })
            .then(response => response.json())
            .then(data => {
                  if (data.success || data.status) {
                        showToast('Leave request submitted successfully!', 'success');
                        closeForm();
                  } else {
                        showToast('Error: ' + (data.message || 'Unable to submit request'), 'error');
                  }
            })
            .catch(error => {
                  console.error('Error:', error);
                  showToast('An error occurred while submitting the request.', 'error');
            });
            return;
      }

      var formData = new FormData();
      formData.append('leave_type', leaveTypeCode || leaveTypeId);
      formData.append('duration', document.getElementById('leave_duration').value || 'Full Day');
      formData.append('no_of_days', document.getElementById('leave_days').value || '');

      if (leaveTypeCode === 'CPL') {
            var workedDate = document.getElementById('comp_off_worked_date').value;
            var compOffDate = document.getElementById('comp_off_date').value;
            var notes = document.getElementById('comp_off_notes').value;

            if (!workedDate || !compOffDate || !notes) {
                        showToast('Please fill all required fields.', 'error');
            }

            formData.append('from_date', workedDate);
            formData.append('to_date', compOffDate);
            formData.append('reason', notes);
      } else if (leaveTypeCode === 'MEL') {
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
                        showToast('Please fill all required fields.', 'error');
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
            showToast('This leave type is not supported yet.', 'error');
            return;
      }

      fetch(base_url + 'admin/hr/submit_leave_request', {
            method: 'POST',
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.success) {
                  showToast('Leave request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + data.message, 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
      });
}

function handleLeaveTypeChange() {
      var leaveTypeOption = document.getElementById('leave_type').selectedOptions[0];
      var leaveTypeCode = leaveTypeOption ? leaveTypeOption.dataset.code : '';
      var baseFields = document.getElementById('leave-base-fields');
      var compOffFields = document.getElementById('leave-comp-off-fields');
      var maternityFields = document.getElementById('leave-maternity-fields');

      baseFields.style.display = 'none';
      compOffFields.style.display = 'none';
      maternityFields.style.display = 'none';

      if (['CSL', 'LOP', 'ADL'].includes(leaveTypeCode)) {
            baseFields.style.display = 'block';
            updateLeaveDays();
      } else if (leaveTypeCode === 'CPL') {
            compOffFields.style.display = 'block';
            updateCompOffDays();
      } else if (leaveTypeCode === 'MEL') {
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
      var dateInput = document.getElementById('reg_attendance_date');
      var typeSelect = document.getElementById('reg_regularization_type');
      var inTimeInput = document.getElementById('reg_requested_in_time');
      var outTimeInput = document.getElementById('reg_requested_out_time');
      var reasonInput = document.getElementById('reg_reason');
      var attachmentInput = document.getElementById('reg_attachment');

      // Validate inputs exist
      if (!dateInput || !typeSelect || !reasonInput) {
            showToast('Form elements not found. Please reload the page.', 'error');
            return;
      }

      var date = dateInput.value;
      var type = typeSelect.value;
      var inTime = inTimeInput ? inTimeInput.value : null;
      var outTime = outTimeInput ? outTimeInput.value : null;
      var reason = reasonInput.value;

      if (!date || !type || !reason) {
            showToast('Please fill all required fields.', 'error');
            return;
      }

      if (!ciStaffEmail) {
            showToast('Unable to determine current user email.', 'error');
            return;
      }

      var formData = new FormData();
      formData.append('staff_email', ciStaffEmail);
      formData.append('attendance_date', date);
      formData.append('regularization_type', type);
      if (inTime) formData.append('requested_in_time', inTime + ':00');
      if (outTime) formData.append('requested_out_time', outTime + ':00');
      formData.append('reason', reason);
      if (attachmentInput && attachmentInput.files.length > 0) {
            formData.append('attachment', attachmentInput.files[0]);
      }

      fetch(laravelWebBase + 'regularization/apply-ci-web', {
            method: 'POST',
            headers: {
                  'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
      })
      .then(response => response.json())
      .then(data => {
            if (data.status || data.success) {
                  showToast('Regularisation request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + (data.message || 'Unable to submit request'), 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
      });
}

function submitWorkFromHomeRequest() {
      // Implement for Work From Home
      var fromDate = document.querySelector('#form-Work From Home input[type="date"]:nth-of-type(1)').value;
      var toDate = document.querySelector('#form-Work From Home input[type="date"]:nth-of-type(2)').value;
      var workDescription = document.querySelector('#form-Work From Home textarea').value;

      if (!fromDate || !toDate || !workDescription) {
            showToast('Please fill all required fields.', 'error');
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
                  showToast('Work From Home request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + data.message, 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
      });
}

function submitOnDutyRequest() {
      // Implement for On Duty
      var fromDate = document.querySelector('#form-On Duty input[type="date"]:nth-of-type(1)').value;
      var toDate = document.querySelector('#form-On Duty input[type="date"]:nth-of-type(2)').value;
      var purpose = document.querySelector('#form-On Duty textarea').value;
      var location = document.querySelector('#form-On Duty input[type="text"]').value;

      if (!fromDate || !toDate || !purpose || !location) {
            showToast('Please fill all required fields.', 'error');
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
                  showToast('On Duty request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + data.message, 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
      });
}

function submitCompOffRequest() {
      // Implement for Comp Off
      var workedDate = document.querySelector('#form-Comp Off input[type="date"]:nth-of-type(1)').value;
      var compOffDate = document.querySelector('#form-Comp Off input[type="date"]:nth-of-type(2)').value;
      var reason = document.querySelector('#form-Comp Off textarea').value;

      if (!workedDate || !compOffDate || !reason) {
            showToast('Please fill all required fields.', 'error');
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
                  showToast('Comp Off request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + data.message, 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
      });
}

function submitResignationRequest() {
      // Implement for Resignation
      var noticePeriod = document.querySelector('#form-Resignation select').value;
      var lastWorkingDate = document.querySelector('#form-Resignation input[type="date"]').value;
      var reason = document.querySelector('#form-Resignation textarea').value;

      if (!noticePeriod || !lastWorkingDate || !reason) {
            showToast('Please fill all required fields.', 'error');
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
                  showToast('Resignation request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + data.message, 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
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
            showToast('Please fill all required fields.', 'error');
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
                  showToast('Expense request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + data.message, 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
      });
}

function submitRestrictedHolidayRequest() {
      // Implement for Restricted Holiday
      var holiday = document.querySelector('#form-Restricted Holiday select').value;
      var fromDate = document.querySelector('#form-Restricted Holiday input[type="date"]:nth-of-type(1)').value;
      var toDate = document.querySelector('#form-Restricted Holiday input[type="date"]:nth-of-type(2)').value;
      var reason = document.querySelector('#form-Restricted Holiday textarea').value;

      if (!holiday || !fromDate || !toDate || !reason) {
            showToast('Please fill all required fields.', 'error');
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
                  showToast('Restricted Holiday request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + data.message, 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
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
            showToast('End time must be after start time.', 'error');
            return;
      }

      if (diff > 30) {
            showToast('Short leave cannot exceed 30 minutes.', 'error');
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
                  showToast('Short Leave request submitted successfully!', 'success');
                  closeForm();
            } else {
                  showToast('Error: ' + data.message, 'error');
            }
      })
      .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while submitting the request.', 'error');
      });
}