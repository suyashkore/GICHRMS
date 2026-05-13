<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="form-CTC Approval" class="rform-body" style="display:none;">
      <div class="fg2">
            <div class="form-group">
                  <label>Candidate Name</label>
                  <input type="text" class="form-control" placeholder="Enter candidate name" />
            </div>
            <div class="form-group">
                  <label>Proposed CTC</label>
                  <div class="expense-amount-input">
                        <span class="expense-prefix">₹</span>
                        <input type="number" class="form-control" placeholder="0.00" min="0" step="0.01" />
                  </div>
            </div>
      </div>
      <div class="fg2">
            <div class="form-group">
                  <label>Joining Date</label>
                  <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" />
            </div>
            <div class="form-group">
                  <label>Department</label>
                  <select class="form-control">
                        <option value="">Select Department</option>
                        <option>HR</option>
                        <option>Sales</option>
                        <option>Operations</option>
                        <option>Finance</option>
                        <option>Development</option>
                  </select>
            </div>
      </div>
      <div class="form-group">
            <label>Approval Notes</label>
            <textarea class="form-control" rows="4" placeholder="Enter any notes for CTC approval"></textarea>
      </div>
      <div class="form-group file-upload-card">
            <label>Supporting Documents</label>
            <div class="file-upload-button">
                  <span>Choose File</span>
                  <input type="file" class="form-control" />
            </div>
      </div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="alert('CTC approval request submitted');">Submit Request</button>
      </div>
</div>
