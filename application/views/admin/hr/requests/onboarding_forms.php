<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="form-CTC Approval" class="rform-body" style="display:none;">
      <form method="POST" action="<?php echo admin_url('hr/onboarding/add'); ?>" class="onboarding-form">
            <div class="fg2">
                  <div class="form-group">
                        <label>Candidate Name</label>
                        <input type="text" class="form-control" name="candidate_name" placeholder="Enter candidate name" required />
                  </div>
                  <div class="form-group">
                        <label>Proposed CTC</label>
                        <div class="expense-amount-input">
                              <span class="expense-prefix">₹</span>
                              <input type="number" class="form-control" name="proposed_ctc" placeholder="0.00" min="0" step="0.01" required />
                        </div>
                  </div>
            </div>
            <div class="fg2">
                  <div class="form-group">
                        <label>Joining Date</label>
                        <input type="date" class="form-control" name="joining_date" value="<?php echo date('Y-m-d'); ?>" required />
                  </div>
                  <div class="form-group">
                        <label>Department</label>
                        <select class="form-control" name="department" required>
                              <option value="">Select Department</option>
                              <option value="HR">HR</option>
                              <option value="Sales">Sales</option>
                              <option value="Operations">Operations</option>
                              <option value="Finance">Finance</option>
                              <option value="Development">Development</option>
                              <option value="Marketing">Marketing</option>
                              <option value="Support">Support</option>
                        </select>
                  </div>
            </div>
            <div class="form-group">
                  <label>Approval Notes</label>
                  <textarea class="form-control" name="approval_notes" rows="4" placeholder="Enter any notes for CTC approval"></textarea>
            </div>
            <div class="form-group file-upload-card">
                  <label>Supporting Documents</label>
                  <div class="file-upload-button">
                        <span>Choose File</span>
                        <input type="file" class="form-control" name="supporting_docs" />
                  </div>
            </div>
            <div class="rform-actions">
                  <button type="button" class="btn-cancel" onclick="closeForm()">Cancel</button>
                  <button type="submit" class="btn-submit">Submit Request</button>
            </div>
      </form>
</div>
