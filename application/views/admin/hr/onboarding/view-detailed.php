<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <!-- Page Header -->
                <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">Employee Onboarding Details</h4>
                        <p class="page-subtitle">Complete onboarding information for <?php echo $onboarding->full_name ?? $onboarding->candidate_name; ?></p>
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

                <style>
                    .detail-section {
                        border: 1px solid #e5e7eb;
                        border-radius: 12px;
                        padding: 20px;
                        margin-bottom: 20px;
                        background: #fafbfc;
                    }

                    .detail-section h5 {
                        font-size: 1rem;
                        font-weight: 700;
                        color: #1f2937;
                        margin-bottom: 16px;
                        padding-bottom: 12px;
                        border-bottom: 2px solid #185FA5;
                    }

                    .detail-row {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 20px;
                        margin-bottom: 16px;
                    }

                    .detail-row.full {
                        grid-template-columns: 1fr;
                    }

                    .detail-field {
                        display: flex;
                        flex-direction: column;
                        gap: 6px;
                    }

                    .detail-field label {
                        font-size: 0.8rem;
                        font-weight: 600;
                        color: #6b7280;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }

                    .detail-field p {
                        font-size: 0.95rem;
                        color: #1f2937;
                        margin: 0;
                        word-break: break-word;
                    }

                    .status-badge {
                        display: inline-block;
                        padding: 6px 12px;
                        border-radius: 6px;
                        font-size: 0.8rem;
                        font-weight: 600;
                        text-transform: uppercase;
                    }

                    .status-pending {
                        background: #fef3c7;
                        color: #92400e;
                    }

                    .status-approved {
                        background: #d1fae5;
                        color: #065f46;
                    }

                    .status-rejected {
                        background: #fee2e2;
                        color: #991b1b;
                    }

                    .detail-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 12px;
                    }

                    .detail-table th,
                    .detail-table td {
                        border: 1px solid #d1d5db;
                        padding: 12px;
                        text-align: left;
                        font-size: 0.85rem;
                    }

                    .detail-table th {
                        background: #f3f4f6;
                        font-weight: 600;
                        color: #374151;
                    }

                    @media (max-width: 768px) {
                        .detail-row {
                            grid-template-columns: 1fr;
                        }
                    }
                </style>

                <!-- Status Card -->
                <div class="detail-section">
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Status</label>
                            <p><span class="status-badge status-<?php echo strtolower($onboarding->status); ?>"><?php echo ucfirst($onboarding->status); ?></span></p>
                        </div>
                        <div class="detail-field">
                            <label>Submitted Date</label>
                            <p><?php echo date('M d, Y H:i A', strtotime($onboarding->created_date)); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section 1: Personal Details -->
                <div class="detail-section">
                    <h5>1. Personal Details</h5>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Full Name</label>
                            <p><?php echo $onboarding->full_name ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Parent's Name</label>
                            <p><?php echo $onboarding->parent_name ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Date of Birth</label>
                            <p><?php echo !empty($onboarding->dob) ? date('M d, Y', strtotime($onboarding->dob)) : 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Gender</label>
                            <p><?php echo $onboarding->gender ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Marital Status</label>
                            <p><?php echo $onboarding->marital_status ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Blood Group</label>
                            <p><?php echo $onboarding->blood_group ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Nationality</label>
                            <p><?php echo $onboarding->nationality ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Mobile Number</label>
                            <p><?php echo $onboarding->mobile_number ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Personal Email</label>
                            <p><?php echo $onboarding->personal_email ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row full">
                        <div class="detail-field">
                            <label>Current Address</label>
                            <p><?php echo nl2br($onboarding->current_address ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <div class="detail-row full">
                        <div class="detail-field">
                            <label>Permanent Address</label>
                            <p><?php echo nl2br($onboarding->permanent_address ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Identity & KYC Details -->
                <div class="detail-section">
                    <h5>2. Identity & KYC Details</h5>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Aadhaar Number</label>
                            <p><?php echo $onboarding->aadhaar_number ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>PAN Number</label>
                            <p><?php echo $onboarding->pan_number ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Passport Number</label>
                            <p><?php echo $onboarding->passport_number ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Driving License Number</label>
                            <p><?php echo $onboarding->dl_number ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>UAN Number</label>
                            <p><?php echo $onboarding->uan_number ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>ESIC Number</label>
                            <p><?php echo $onboarding->esic_number ?? 'N/A'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Employment Details -->
                <div class="detail-section">
                    <h5>3. Employment Details</h5>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Employee ID</label>
                            <p><?php echo $onboarding->employee_id ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Designation</label>
                            <p><?php echo $onboarding->designation ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Department</label>
                            <p><?php echo $onboarding->department ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Date of Joining</label>
                            <p><?php echo !empty($onboarding->joining_date) ? date('M d, Y', strtotime($onboarding->joining_date)) : 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Reporting Manager</label>
                            <p><?php echo $onboarding->reporting_manager ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Employment Type</label>
                            <p><?php echo $onboarding->employment_type ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Work Location</label>
                            <p><?php echo $onboarding->work_location ?? 'N/A'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Educational Qualification -->
                <div class="detail-section">
                    <h5>4. Educational Qualification</h5>
                    <?php
                        $education_details = [];
                        if (!empty($onboarding->education_details)) {
                            $education_details = json_decode($onboarding->education_details, true);
                        }
                    ?>
                    <?php if (!empty($education_details) && is_array($education_details)): ?>
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th>Qualification</th>
                                    <th>Specialization</th>
                                    <th>Institute/University</th>
                                    <th>Year</th>
                                    <th>Percentage/CGPA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($education_details as $edu): ?>
                                    <tr>
                                        <td><?php echo !empty($edu['qualification']) ? $edu['qualification'] : 'N/A'; ?></td>
                                        <td><?php echo !empty($edu['specialization']) ? $edu['specialization'] : 'N/A'; ?></td>
                                        <td><?php echo !empty($edu['institute']) ? $edu['institute'] : 'N/A'; ?></td>
                                        <td><?php echo !empty($edu['year']) ? $edu['year'] : 'N/A'; ?></td>
                                        <td><?php echo !empty($edu['percentage']) ? $edu['percentage'] : 'N/A'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th>Qualification</th>
                                    <th>Institute/University</th>
                                    <th>Year</th>
                                    <th>Percentage/CGPA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>10th</td>
                                    <td><?php echo $onboarding->edu_10th_institute ?? 'N/A'; ?></td>
                                    <td><?php echo $onboarding->edu_10th_year ?? 'N/A'; ?></td>
                                    <td><?php echo $onboarding->edu_10th_percentage ?? 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td>12th</td>
                                    <td><?php echo $onboarding->edu_12th_institute ?? 'N/A'; ?></td>
                                    <td><?php echo $onboarding->edu_12th_year ?? 'N/A'; ?></td>
                                    <td><?php echo $onboarding->edu_12th_percentage ?? 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td>Graduation</td>
                                    <td><?php echo $onboarding->edu_graduation_institute ?? 'N/A'; ?></td>
                                    <td><?php echo $onboarding->edu_graduation_year ?? 'N/A'; ?></td>
                                    <td><?php echo $onboarding->edu_graduation_percentage ?? 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td>Post Graduation</td>
                                    <td><?php echo $onboarding->edu_postgraduation_institute ?? 'N/A'; ?></td>
                                    <td><?php echo $onboarding->edu_postgraduation_year ?? 'N/A'; ?></td>
                                    <td><?php echo $onboarding->edu_postgraduation_percentage ?? 'N/A'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <!-- Section 5: Previous Employment Details -->
                <div class="detail-section">
                    <h5>5. Previous Employment Details</h5>
                    <?php
                        $employment_history = [];
                        if (!empty($onboarding->employment_history)) {
                            $employment_history = json_decode($onboarding->employment_history, true);
                        }
                    ?>
                    <?php if (!empty($employment_history) && is_array($employment_history)): ?>
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Designation</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>CTC</th>
                                    <th>Notice Period</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employment_history as $employment): ?>
                                    <tr>
                                        <td><?php echo !empty($employment['company']) ? $employment['company'] : 'N/A'; ?></td>
                                        <td><?php echo !empty($employment['designation']) ? $employment['designation'] : 'N/A'; ?></td>
                                        <td><?php echo !empty($employment['start_date']) ? date('M d, Y', strtotime($employment['start_date'])) : 'N/A'; ?></td>
                                        <td><?php echo !empty($employment['end_date']) ? date('M d, Y', strtotime($employment['end_date'])) : 'N/A'; ?></td>
                                        <td><?php echo !empty($employment['ctc']) ? '₹' . number_format($employment['ctc'], 2) : 'N/A'; ?></td>
                                        <td><?php echo !empty($employment['notice_period']) ? $employment['notice_period'] : 'N/A'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Total Experience</label>
                            <p><?php echo !empty($onboarding->total_experience) ? $onboarding->total_experience . ' years' : 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Last Company</label>
                            <p><?php echo $onboarding->last_company ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Last Designation</label>
                            <p><?php echo $onboarding->last_designation ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Last Drawn CTC</label>
                            <p><?php echo !empty($onboarding->last_ctc) ? '₹' . number_format($onboarding->last_ctc, 2) : 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Notice Period</label>
                            <p><?php echo $onboarding->notice_period ?? 'N/A'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Bank Details -->
                <div class="detail-section">
                    <h5>6. Bank Details (Salary Account)</h5>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Bank Name</label>
                            <p><?php echo $onboarding->bank_name ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Account Holder Name</label>
                            <p><?php echo $onboarding->account_holder ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Account Number</label>
                            <p><?php echo $onboarding->account_number ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>IFSC Code</label>
                            <p><?php echo $onboarding->ifsc_code ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Branch Name</label>
                            <p><?php echo $onboarding->branch_name ?? 'N/A'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section 7: Emergency Contact Details -->
                <div class="detail-section">
                    <h5>7. Family / Emergency Contact Details</h5>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Emergency Contact Person</label>
                            <p><?php echo $onboarding->emergency_contact_name ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Relationship</label>
                            <p><?php echo $onboarding->emergency_relationship ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Mobile Number</label>
                            <p><?php echo $onboarding->emergency_mobile ?? 'N/A'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section 8: Statutory Information -->
                <div class="detail-section">
                    <h5>8. Statutory Information</h5>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>PF Applicable</label>
                            <p><?php echo $onboarding->pf_applicable ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>PF UAN Number</label>
                            <p><?php echo $onboarding->pf_uan ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>ESIC Applicable</label>
                            <p><?php echo $onboarding->esic_applicable ?? 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Nominee Name</label>
                            <p><?php echo $onboarding->nominee_name ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Relationship with Nominee</label>
                            <p><?php echo $onboarding->nominee_relationship ?? 'N/A'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Section 9: Documents Submitted -->
                <?php if (!empty($onboarding->documents)): ?>
                    <div class="detail-section">
                        <h5>9. Documents Submitted</h5>
                        <?php 
                            $documents = json_decode($onboarding->documents, true);
                            if (!empty($documents)) {
                                echo '<style>
                                    .document-list {
                                        display: grid;
                                        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                                        gap: 12px;
                                    }
                                    .document-item {
                                        border: 1px solid #d1d5db;
                                        border-radius: 8px;
                                        padding: 12px;
                                        background: #f9fafb;
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: space-between;
                                        gap: 8px;
                                    }
                                    .document-item h6 {
                                        font-size: 0.8rem;
                                        font-weight: 600;
                                        color: #1f2937;
                                        margin: 0;
                                    }
                                    .document-item a {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 6px;
                                        padding: 6px 10px;
                                        background: #185FA5;
                                        color: #ffffff;
                                        text-decoration: none;
                                        border-radius: 6px;
                                        font-size: 0.75rem;
                                        font-weight: 600;
                                        text-align: center;
                                        transition: background 0.2s;
                                    }
                                    .document-item a:hover {
                                        background: #0c447c;
                                    }
                                </style>';
                                echo '<div class="document-list">';
                                
                                $doc_labels = [
                                    'doc_resume' => '📄 Resume/CV',
                                    'doc_passport_photo' => '📸 Passport Photo',
                                    'doc_aadhaar' => '🎫 Aadhaar',
                                    'doc_pan' => '🎫 PAN Card',
                                    'doc_bank' => '🏦 Bank Details',
                                    'doc_education' => '🎓 Education',
                                    'doc_experience' => '💼 Experience',
                                    'doc_salary' => '💰 Salary Slips',
                                    'doc_relieving' => '📋 Relieving Letter',
                                    'doc_passport' => '✈️ Passport',
                                    'doc_driving_license' => '🚗 Driving License'
                                ];
                                
                                foreach ($documents as $doc_key => $file_path) {
                                    $label = $doc_labels[$doc_key] ?? ucfirst($doc_key);
                                    $file_url = base_url($file_path);
                                    $file_name = basename($file_path);
                                    
                                    echo '<div class="document-item">';
                                    echo '<h6>' . $label . '</h6>';
                                    echo '<a href="' . $file_url . '" target="_blank" download>⬇️ Download</a>';
                                    echo '</div>';
                                }
                                
                                echo '</div>';
                            } else {
                                echo '<p>No documents uploaded</p>';
                            }
                        ?>
                    </div>
                <?php else: ?>
                    <div class="detail-section">
                        <h5>9. Documents Submitted</h5>
                        <p>No documents uploaded</p>
                    </div>
                <?php endif; ?>

                <!-- Section 10: Declaration -->
                <div class="detail-section">
                    <h5>10. Declaration Information</h5>
                    <div class="detail-row">
                        <div class="detail-field">
                            <label>Declaration Date</label>
                            <p><?php echo !empty($onboarding->declaration_date) ? date('M d, Y', strtotime($onboarding->declaration_date)) : 'N/A'; ?></p>
                        </div>
                        <div class="detail-field">
                            <label>Place</label>
                            <p><?php echo $onboarding->declaration_place ?? 'N/A'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Approval Status -->
                <?php if ($onboarding->status === 'approved'): ?>
                    <div class="detail-section">
                        <h5>Approval Information</h5>
                        <div class="detail-row">
                            <div class="detail-field">
                                <label>Approved By</label>
                                <p><?php echo $onboarding->approved_by ?? 'N/A'; ?></p>
                            </div>
                            <div class="detail-field">
                                <label>Approved Date</label>
                                <p><?php echo !empty($onboarding->approved_date) ? date('M d, Y H:i A', strtotime($onboarding->approved_date)) : 'N/A'; ?></p>
                            </div>
                        </div>
                    </div>
                <?php elseif ($onboarding->status === 'rejected'): ?>
                    <div class="detail-section">
                        <h5>Rejection Information</h5>
                        <div class="detail-row">
                            <div class="detail-field">
                                <label>Rejected By</label>
                                <p><?php echo $onboarding->rejected_by ?? 'N/A'; ?></p>
                            </div>
                            <div class="detail-field">
                                <label>Rejected Date</label>
                                <p><?php echo !empty($onboarding->rejected_date) ? date('M d, Y H:i A', strtotime($onboarding->rejected_date)) : 'N/A'; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
