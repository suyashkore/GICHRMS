<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <!-- Page Header -->
                <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">Onboarding Details</h4>
                        <p class="page-subtitle">View candidate onboarding information</p>
                    </div>
                    <div>
                        <a href="<?php echo admin_url('hr/onboarding'); ?>" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                        <?php if ($onboarding->status === 'pending'): ?>
                            <a href="<?php echo admin_url('hr/onboarding/edit/' . $onboarding->id); ?>" class="btn btn-primary">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Details -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Candidate Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-muted">Candidate Name</label>
                                        <p><?php echo $onboarding->candidate_name; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted">Department</label>
                                        <p><?php echo $onboarding->department; ?></p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-muted">Proposed CTC</label>
                                        <p><strong>₹<?php echo number_format($onboarding->proposed_ctc, 2); ?></strong></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted">Joining Date</label>
                                        <p><?php echo date('M d, Y', strtotime($onboarding->joining_date)); ?></p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="text-muted">Approval Notes</label>
                                        <p><?php echo nl2br($onboarding->approval_notes); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($onboarding->status === 'approved'): ?>
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5>Approval Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="text-muted">Approved By</label>
                                            <p><?php echo $onboarding->approved_by; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted">Approved Date</label>
                                            <p><?php echo date('M d, Y H:i A', strtotime($onboarding->approved_date)); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($onboarding->status === 'rejected'): ?>
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5>Rejection Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="text-muted">Rejected By</label>
                                            <p><?php echo $onboarding->rejected_by; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted">Rejected Date</label>
                                            <p><?php echo date('M d, Y H:i A', strtotime($onboarding->rejected_date)); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Status</h5>
                            </div>
                            <div class="card-body text-center">
                                <?php
                                $status_class = '';
                                $status_text = '';
                                switch($onboarding->status) {
                                    case 'pending':
                                        $status_class = 'badge-warning';
                                        $status_text = 'Pending';
                                        break;
                                    case 'approved':
                                        $status_class = 'badge-success';
                                        $status_text = 'Approved';
                                        break;
                                    case 'rejected':
                                        $status_class = 'badge-danger';
                                        $status_text = 'Rejected';
                                        break;
                                }
                                ?>
                                <h4><span class="badge <?php echo $status_class; ?>" style="font-size: 18px; padding: 10px 20px;"><?php echo $status_text; ?></span></h4>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Record Information</h5>
                            </div>
                            <div class="card-body">
                                <label class="text-muted">Created Date</label>
                                <p><?php echo date('M d, Y H:i A', strtotime($onboarding->created_date)); ?></p>

                                <?php if (!empty($onboarding->updated_date)): ?>
                                    <label class="text-muted">Last Updated</label>
                                    <p><?php echo date('M d, Y H:i A', strtotime($onboarding->updated_date)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($onboarding->status === 'pending'): ?>
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5>Actions</h5>
                                </div>
                                <div class="card-body">
                                    <a href="<?php echo admin_url('hr/onboarding/approve/' . $onboarding->id); ?>" class="btn btn-success btn-block mb-2" onclick="return confirm('Approve this request?');">
                                        <i class="fa fa-check"></i> Approve
                                    </a>
                                    <a href="<?php echo admin_url('hr/onboarding/reject/' . $onboarding->id); ?>" class="btn btn-danger btn-block" onclick="return confirm('Reject this request?');">
                                        <i class="fa fa-times"></i> Reject
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
