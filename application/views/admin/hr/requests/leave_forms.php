<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Leave Forms -->
<div id="form-Leave" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group">
                  <label>Leave Type</label>
                  <select class="form-control" name="leave_type" id="leave_type" onchange="handleLeaveTypeChange()">
                        <option value="">Select</option>
                        <?php if (!empty($leave_types) && is_array($leave_types)) : ?>
                              <?php foreach ($leave_types as $type) : ?>
                                    <option value="<?php echo html_escape($type['id']); ?>" data-code="<?php echo html_escape($type['code']); ?>"><?php echo html_escape($type['name']); ?></option>
                              <?php endforeach; ?>
                        <?php endif; ?>
                  </select>
            </div>
      </div>

      <div id="leave-base-fields" style="display:none;">
            <div class="fg2">
                  <div class="form-group"><label>From Date</label><input type="date" id="leave_from_date" name="from_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateLeaveDays()" /></div>
                  <div class="form-group"><label>To Date</label><input type="date" id="leave_to_date" name="to_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateLeaveDays()" /></div>
            </div>
            <div class="fg2">
                  <div class="form-group"><label>No. of Days</label><input type="text" id="leave_days_display" class="form-control" readonly placeholder="Auto-calculated" /></div>
                  <div class="form-group">
                        <label>Duration</label>
                        <div class="segmented-control" id="leaveDurationControl">
                              <button type="button" class="seg-btn active" onclick="setLeaveDuration(this,'Full Day')">Full Day</button>
                              <button type="button" class="seg-btn" onclick="setLeaveDuration(this,'First Half')">First Half</button>
                              <button type="button" class="seg-btn" onclick="setLeaveDuration(this,'Second Half')">Second Half</button>
                        </div>
                        <input type="hidden" id="leave_duration" name="duration" value="Full Day" />
                  </div>
            </div>
            <div class="form-group"><label>Reason</label><textarea class="form-control" id="leave_reason" name="reason" rows="3" placeholder="Enter reason for leave"></textarea></div>
            <div class="form-group form-check">
                  <input type="checkbox" class="form-check-input" id="notify_team" />
                  <label class="form-check-label" for="notify_team">Notify Team</label>
            </div>
            <div class="form-group"><label>Attachment <small>(optional)</small></label><input type="file" id="leave_attachment" class="form-control" /></div>
      </div>

      <div id="leave-comp-off-fields" style="display:none;">
            <div class="fg2">
                  <div class="form-group"><label>Worked Date</label><input type="date" id="comp_off_worked_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateCompOffDays()" /></div>
                  <div class="form-group"><label>Comp Off Date</label><input type="date" id="comp_off_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateCompOffDays()" /></div>
            </div>
            <div class="form-group"><label>Additional Notes</label><textarea class="form-control" id="comp_off_notes" rows="3" placeholder="Enter additional notes"></textarea></div>
      </div>

      <div id="leave-maternity-fields" style="display:none;">
            <div class="fg2">
                  <div class="form-group"><label>Expected Delivery Date</label><input type="date" id="maternity_delivery_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
                  <div class="form-group"><label>Start Date</label><input type="date" id="maternity_start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateMaternityDuration()" /></div>
            </div>
            <div class="fg2">
                  <div class="form-group"><label>End Date</label><input type="date" id="maternity_end_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" onchange="updateMaternityDuration()" /></div>
                  <div class="form-group"><label>Total Leave Duration</label><input type="text" id="maternity_total_days" class="form-control" readonly placeholder="Auto-calculated" /></div>
            </div>
            <div class="form-group"><label>Upload Medical Certificate</label><input type="file" id="maternity_medical_certificate" class="form-control" /></div>
            <div class="form-group"><label>Alternate Contact No</label><input type="tel" id="maternity_alt_contact" class="form-control" placeholder="Alternate contact number" /></div>
            <div class="form-group"><label>Emergency Contact No</label><input type="tel" id="maternity_emergency_contact" class="form-control" placeholder="Emergency contact number" /></div>
            <div class="form-group"><label>Out of Office Consignee</label><input type="text" id="maternity_consignee" class="form-control" placeholder="Out of office consignee" /></div>
            <div class="form-group"><label>Work Handover Plan</label><textarea class="form-control" id="maternity_handover_plan" rows="3" placeholder="Describe the handover plan"></textarea></div>
            <div class="form-group"><label>Additional Notes</label><textarea class="form-control" id="maternity_notes" rows="3" placeholder="Add any additional notes"></textarea></div>
            <div class="form-group"><small><strong>Note:</strong> Ensure all documents are uploaded as per company policy. This request will be sent to HR for approval.</small></div>
      </div>

      <input type="hidden" id="leave_days" name="no_of_days" value="" />
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitLeaveRequest()">Submit Request</button>
      </div>
</div>

<!-- Regularisation -->
<div id="form-Regularisation" class="rform-body" style="display:none;">
      <div class="form-group"><label>Regularisation Date</label><input id="reg_attendance_date" type="date" class="form-control form-control-card" value="<?php echo date('Y-m-d'); ?>" /></div>
      <div class="form-group"><label>Missing Punch Type</label>
            <select id="reg_regularization_type" class="form-control form-control-card">
                  <option value="">Select Type</option>
                  <option value="biometric_issue">Biometric Not Working</option>
                  <option value="forgot_punch">Forgot to Punch</option>
                  <option value="app_issue">App Not Working</option>
                  <option value="official_work">On Official Work</option>
                  <option value="network_issue">Network Issue</option>
                  <option value="weekoff_holiday">Worked on Holiday/Weekoff</option>
                  <option value="other">Other</option>
            </select>
      </div>
      <div class="fg2">
            <div class="form-group"><label>From Time</label><input id="reg_requested_in_time" type="time" class="form-control form-control-card" /></div>
            <div class="form-group"><label>To Time</label><input id="reg_requested_out_time" type="time" class="form-control form-control-card" /></div>
      </div>
      <div class="form-group"><label>Reason</label><textarea id="reg_reason" class="form-control form-control-card" rows="3" placeholder="Reason for regularisation"></textarea></div>
      <div class="form-group file-upload-card">
            <label>Supporting Document</label>
            <div class="file-upload-button">
                  <span>+ Add File</span>
                  <input id="reg_attachment" type="file" class="form-control" />
            </div>
      </div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitRegularisationRequest()">Submit Request</button>
      </div>
</div>

<!-- Work From Home -->
<div id="form-Work From Home" class="rform-body" style="display:none;">
      <div class="form-group"><label>From Date</label><input type="date" class="form-control form-control-card" value="<?php echo date('Y-m-d'); ?>" /></div>
      <div class="form-group"><label>To Date</label><input type="date" class="form-control form-control-card" value="<?php echo date('Y-m-d'); ?>" /></div>
      <div class="form-group"><label>Duration</label>
            <div class="segmented-control" data-target="wfhDuration">
                  <button type="button" class="seg-btn active" onclick="setWorkFromHomeDuration(this,'Full Day')">Full Day</button>
                  <button type="button" class="seg-btn" onclick="setWorkFromHomeDuration(this,'First Half')">First Half</button>
                  <button type="button" class="seg-btn" onclick="setWorkFromHomeDuration(this,'Second Half')">Second Half</button>
            </div>
            <input type="hidden" id="wfhDuration" value="Full Day" />
      </div>
      <div class="form-group"><label>Work Location</label>
            <select class="form-control form-control-card">
                  <option>Home Office</option>
                  <option>Client Site</option>
                  <option>Co-working Space</option>
                  <option>Other</option>
            </select>
      </div>
      <div class="form-group"><label>Reason</label><textarea class="form-control form-control-card" rows="4" placeholder="Describe the work you will do from home"></textarea></div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitWorkFromHomeRequest()">Submit Request</button>
      </div>
</div>

<!-- On Duty -->
<div id="form-On Duty" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group"><label>From Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
            <div class="form-group"><label>To Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
      </div>
      <div class="form-group"><label>Purpose</label><textarea class="form-control" rows="3" placeholder="Purpose of on duty"></textarea></div>
      <div class="form-group"><label>Location</label><input type="text" class="form-control" placeholder="e.g. Client Site, Head Office" /></div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitOnDutyRequest()">Submit Request</button>
      </div>
</div>

<!-- Comp Off -->
<div id="form-Comp Off" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group"><label>Worked Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
            <div class="form-group"><label>Comp Off Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
      </div>
      <div class="form-group"><label>Reason</label><textarea class="form-control" rows="3" placeholder="Why are you applying for comp off?"></textarea></div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitCompOffRequest()">Submit Request</button>
      </div>
</div>

<!-- Resignation -->
<div id="form-Resignation" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group"><label>Reason For Separation</label>
                  <select class="form-control form-control-card">
                        <option value="">Select Reason</option>
                        <option>Personal Reasons</option>
                        <option>Health Reasons</option>
                        <option>Higher Studies</option>
                        <option>Better Opportunity</option>
                        <option>Relocation</option>
                        <option>Other</option>
                  </select>
            </div>
            <div class="form-group"><label>Desired Last Working Date</label><input type="date" class="form-control form-control-card" value="<?php echo date('Y-m-d'); ?>" /></div>
      </div>
      <div class="fg2">
            <div class="form-group"><label>Personal Email</label><input type="email" class="form-control form-control-card" placeholder="name@example.com" /></div>
            <div class="form-group"><label>Phone Number</label><input type="tel" class="form-control form-control-card" placeholder="Enter phone number" /></div>
      </div>
      <div class="form-group"><label>Current Address</label><input type="text" class="form-control form-control-card" placeholder="Enter current address" /></div>
      <div class="form-group"><label>Remarks</label><textarea class="form-control form-control-card" rows="4" placeholder="Any additional remarks"></textarea></div>
      <div class="form-group file-upload-card"><label>Upload Letter</label>
            <div class="file-upload-button">
                  <span>Choose File</span>
                  <input type="file" class="form-control" />
            </div>
      </div>
      <div class="form-group file-upload-card"><label>Upload Address Proof</label>
            <div class="file-upload-button">
                  <span>Choose File</span>
                  <input type="file" class="form-control" />
            </div>
      </div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitResignationRequest()">Submit Request</button>
      </div>
</div>

<!-- Expense -->
<div id="form-Expense" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group"><label>Expense Type</label><select class="form-control"><option>Travel</option><option>Food</option><option>Accommodation</option><option>Other</option></select></div>
            <div class="form-group"><label>Amount (&#8377;)</label>
                  <div class="expense-amount-input">
                        <span class="expense-prefix">&#8377;</span>
                        <input type="number" class="form-control" placeholder="0.00" />
                  </div>
            </div>
      </div>
      <div class="form-group"><label>Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
      <div class="form-group"><label>Description</label><textarea class="form-control" rows="3" placeholder="Describe the expense"></textarea></div>
      <div class="form-group"><label>Bill / Receipt <small>(optional)</small></label><input type="file" class="form-control" /></div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitExpenseRequest()">Submit Request</button>
      </div>
</div>

<!-- Restricted Holiday -->
<div id="form-Restricted Holiday" class="rform-body" style="display:none;">
      <div class="form-group"><label>Select Holiday</label><select class="form-control"><option>Holiday 1</option><option>Holiday 2</option></select></div>
      <div class="fg2">
            <div class="form-group"><label>From Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
            <div class="form-group"><label>To Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
      </div>
      <div class="form-group"><label>Reason</label><textarea class="form-control" rows="3" placeholder="Reason for restricted holiday"></textarea></div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitRestrictedHolidayRequest()">Submit Request</button>
      </div>
</div>

<!-- Short Leave -->
<div id="form-Short Leave" class="rform-body" style="display:none;">
      <div class="form-group"><label>Date</label><input type="date" class="form-control form-control-card" value="<?php echo date('Y-m-d'); ?>" /></div>
      <div class="shortleave-duration-panel">
            <div class="shortleave-time-block">
                  <label>From</label>
                  <input type="time" class="form-control form-control-card shortleave-time" id="shortLeaveFrom" value="09:00" onchange="updateShortLeaveSummary()" />
            </div>
            <div class="shortleave-time-separator">→</div>
            <div class="shortleave-time-block">
                  <label>To</label>
                  <input type="time" class="form-control form-control-card shortleave-time" id="shortLeaveTo" value="09:30" onchange="updateShortLeaveSummary()" />
            </div>
      </div>
      <div class="shortleave-summary" id="shortLeaveSummary">Duration: 30 minutes</div>
      <div class="shortleave-note">Maximum short leave duration is 30 minutes.</div>
      <div class="form-group"><label>Reason</label><textarea class="form-control form-control-card" rows="3" placeholder="Reason for short leave"></textarea></div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitShortLeaveRequest()">Submit Request</button>
      </div>
</div>

