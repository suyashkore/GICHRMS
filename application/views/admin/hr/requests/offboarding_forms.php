<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="form-Resignation" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group">
                  <label>Employee Name</label>
                  <input type="text" class="form-control" placeholder="Enter employee name" />
            </div>
            <div class="form-group">
                  <label>Last Working Day</label>
                  <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" />
            </div>
      </div>
      <div class="form-group">
            <label>Reason for Resignation</label>
            <select class="form-control">
                  <option value="">Select Reason</option>
                  <option>Personal Reasons</option>
                  <option>Career Growth</option>
                  <option>Health Reasons</option>
                  <option>Relocation</option>
                  <option>Other</option>
            </select>
      </div>
      <div class="form-group">
            <label>Notice Period</label>
            <input type="text" class="form-control" placeholder="e.g. 30 days" />
      </div>
      <div class="form-group">
            <label>Remarks</label>
            <textarea class="form-control" rows="4" placeholder="Add any additional remarks"></textarea>
      </div>
      <div class="form-group file-upload-card">
            <label>Upload Resignation Letter</label>
            <div class="file-upload-button">
                  <span>Choose File</span>
                  <input type="file" class="form-control" />
            </div>
      </div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="alert('Resignation request submitted');">Submit Request</button>
      </div>
</div>
