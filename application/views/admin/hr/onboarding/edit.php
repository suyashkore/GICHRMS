<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <!-- Page Header -->
                <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">Edit Onboarding</h4>
                        <p class="page-subtitle">Update onboarding request details</p>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="<?php echo admin_url('hr/onboarding/edit/' . $onboarding->id); ?>" class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="candidate_name">Candidate Name <span class="text-danger">*</span></label>
                                <input type="text" id="candidate_name" name="candidate_name" class="form-control" placeholder="Enter candidate name" value="<?php echo $onboarding->candidate_name; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department">Department <span class="text-danger">*</span></label>
                                <select id="department" name="department" class="form-control" required>
                                    <option value="">Select Department</option>
                                    <option value="HR" <?php echo ($onboarding->department === 'HR' ? 'selected' : ''); ?>>HR</option>
                                    <option value="Sales" <?php echo ($onboarding->department === 'Sales' ? 'selected' : ''); ?>>Sales</option>
                                    <option value="Operations" <?php echo ($onboarding->department === 'Operations' ? 'selected' : ''); ?>>Operations</option>
                                    <option value="Finance" <?php echo ($onboarding->department === 'Finance' ? 'selected' : ''); ?>>Finance</option>
                                    <option value="Development" <?php echo ($onboarding->department === 'Development' ? 'selected' : ''); ?>>Development</option>
                                    <option value="Marketing" <?php echo ($onboarding->department === 'Marketing' ? 'selected' : ''); ?>>Marketing</option>
                                    <option value="Support" <?php echo ($onboarding->department === 'Support' ? 'selected' : ''); ?>>Support</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="proposed_ctc">Proposed CTC (₹) <span class="text-danger">*</span></label>
                                <input type="number" id="proposed_ctc" name="proposed_ctc" class="form-control" placeholder="0.00" min="0" step="0.01" value="<?php echo $onboarding->proposed_ctc; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="joining_date">Joining Date <span class="text-danger">*</span></label>
                                <input type="date" id="joining_date" name="joining_date" class="form-control" value="<?php echo date('Y-m-d', strtotime($onboarding->joining_date)); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="approval_notes">Approval Notes</label>
                        <textarea id="approval_notes" name="approval_notes" class="form-control" rows="4" placeholder="Enter any notes or comments"><?php echo $onboarding->approval_notes; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Current Status: <strong><?php echo ucfirst($onboarding->status); ?></strong></label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update Onboarding Request</button>
                        <a href="<?php echo admin_url('hr/onboarding'); ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
