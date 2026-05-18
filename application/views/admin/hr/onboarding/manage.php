<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <!-- Page Header -->
                <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">HR / Onboarding</h4>
                        <p class="page-subtitle">Manage candidate onboarding and CTC approvals</p>
                    </div>
                    <div>
                        <a href="<?php echo admin_url('hr/onboarding/add'); ?>" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add Onboarding
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Pending</h5>
                                <h2 class="text-warning"><?php echo count(array_filter($onboarding_records, function($r) { return $r->status === 'pending'; })); ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Approved</h5>
                                <h2 class="text-success"><?php echo count(array_filter($onboarding_records, function($r) { return $r->status === 'approved'; })); ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Rejected</h5>
                                <h2 class="text-danger"><?php echo count(array_filter($onboarding_records, function($r) { return $r->status === 'rejected'; })); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onboarding Records Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Candidate Name</th>
                                <th>Department</th>
                                <th>CTC</th>
                                <th>Joining Date</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($onboarding_records) > 0): ?>
                                <?php foreach ($onboarding_records as $record): ?>
                                    <tr>
                                        <td><?php echo $record->id; ?></td>
                                        <td><?php echo $record->candidate_name; ?></td>
                                        <td><?php echo $record->department; ?></td>
                                        <td>₹<?php echo number_format($record->proposed_ctc, 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($record->joining_date)); ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            $status_text = '';
                                            switch($record->status) {
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
                                            <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($record->created_date)); ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('hr/onboarding/view/' . $record->id); ?>" class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="<?php echo admin_url('hr/onboarding/edit/' . $record->id); ?>" class="btn btn-sm btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <?php if ($record->status === 'pending'): ?>
                                                <a href="<?php echo admin_url('hr/onboarding/approve/' . $record->id); ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this request?');">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                                <a href="<?php echo admin_url('hr/onboarding/reject/' . $record->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this request?');">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php echo admin_url('hr/onboarding/delete/' . $record->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No onboarding records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
