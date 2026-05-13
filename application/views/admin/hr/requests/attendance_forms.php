<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Punch Correction -->
<div id="form-Punch Correction" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group"><label>Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
            <div class="form-group"><label>Shift</label><select class="form-control"><option>Morning</option><option>Evening</option><option>Night</option></select></div>
      </div>
      <div class="fg2">
            <div class="form-group"><label>Punch In</label><input type="time" class="form-control" /></div>
            <div class="form-group"><label>Punch Out</label><input type="time" class="form-control" /></div>
      </div>
      <div class="form-group"><label>Reason</label><textarea class="form-control" rows="3" placeholder="Reason for correction"></textarea></div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitPunchCorrectionRequest()">Submit Request</button>
      </div>
</div>

<!-- Missing Attendance -->
<div id="form-Missing Attendance" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group"><label>Date</label><input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></div>
            <div class="form-group"><label>Attendance Status</label><select class="form-control"><option>Present</option><option>Half Day</option></select></div>
      </div>
      <div class="form-group"><label>Reason</label><textarea class="form-control" rows="3" placeholder="Explain missing attendance"></textarea></div>
      <div class="form-group"><label>Proof <small>(optional)</small></label><input type="file" class="form-control" /></div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="submitMissingAttendanceRequest()">Submit Request</button>
      </div>
</div>

<script src="<?php echo base_url('assets/js/attendance_module.js'); ?>"></script>