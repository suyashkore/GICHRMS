<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <!-- Page Header -->
                <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">Submit Resignation</h4>
                        <p class="page-subtitle">Submit your resignation request</p>
                    </div>
                </div>

                <style>
                    .fg2 {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 14px;
                    }

                    @media (max-width: 580px) {
                        .fg2 {
                            grid-template-columns: 1fr;
                        }
                    }

                    .form-group {
                        display: flex;
                        flex-direction: column;
                        gap: 4px;
                        margin-bottom: 14px;
                    }

                    .form-group label {
                        font-size: 0.8rem;
                        font-weight: 600;
                        color: #374151;
                    }

                    .form-control {
                        border: 1px solid #d1d5db;
                        border-radius: 8px;
                        padding: 10px 12px;
                        font-size: 0.9rem;
                        color: #1f2937;
                        width: 100%;
                        transition: border-color 0.15s, box-shadow 0.15s;
                        background: #ffffff;
                        outline: none;
                        min-height: 46px;
                    }

                    .form-control:focus {
                        border-color: #378ADD;
                        box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
                    }

                    textarea.form-control {
                        min-height: 110px;
                        resize: vertical;
                    }

                    .file-upload-card .file-upload-button {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 52px;
                        width: 100%;
                        padding: 0 16px;
                        border: 1px solid #d1d5db;
                        border-radius: 14px;
                        background: #f8fafc;
                        color: #1f2937;
                        cursor: pointer;
                        position: relative;
                    }

                    .file-upload-card .file-upload-button input[type="file"] {
                        position: absolute;
                        inset: 0;
                        width: 100%;
                        height: 100%;
                        opacity: 0;
                        cursor: pointer;
                    }

                    .rform-actions {
                        display: flex;
                        gap: 10px;
                        justify-content: flex-end;
                        margin-top: 10px;
                        padding-top: 10px;
                        border-top: 1px solid #f3f4f6;
                    }

                    .btn-cancel {
                        padding: 8px 22px;
                        border-radius: 8px;
                        font-size: 0.9rem;
                        background: #ffffff;
                        border: 1px solid #d1d5db;
                        color: #4b5563;
                        cursor: pointer;
                        text-decoration: none;
                    }

                    .btn-cancel:hover {
                        background: #f3f4f6;
                    }

                    .btn-submit {
                        padding: 8px 22px;
                        border-radius: 8px;
                        font-size: 0.9rem;
                        background: #185FA5;
                        border: none;
                        color: #ffffff;
                        font-weight: 600;
                        cursor: pointer;
                    }

                    .btn-submit:hover {
                        background: #0C447C;
                    }
                </style>

                <form method="POST" action="<?php echo admin_url('hr/offboarding/add'); ?>" class="offboarding-form" enctype="multipart/form-data">
                    <div class="fg2">
                        <div class="form-group">
                            <label for="reason">Reason For Separation</label>
                            <select id="reason" name="reason" class="form-control" required>
                                <option value="">Select Reason</option>
                                <option value="Personal Reasons">Personal Reasons</option>
                                <option value="Better Opportunity">Better Opportunity</option>
                                <option value="Health Issues">Health Issues</option>
                                <option value="Career Change">Career Change</option>
                                <option value="Relocation">Relocation</option>
                                <option value="Work Environment">Work Environment</option>
                                <option value="Salary Issues">Salary Issues</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="last_working_date">Desired Last Working Date</label>
                            <input type="date" id="last_working_date" name="last_working_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="fg2">
                        <div class="form-group">
                            <label for="personal_email">Personal Email</label>
                            <input type="email" id="personal_email" name="personal_email" class="form-control" placeholder="name@example.com">
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="current_address">Current Address</label>
                        <input type="text" id="current_address" name="current_address" class="form-control" placeholder="Enter current address">
                    </div>

                    <div class="form-group">
                        <label for="comments">Remarks</label>
                        <textarea id="comments" name="comments" class="form-control" placeholder="Any additional remarks"></textarea>
                    </div>

                    <div class="form-group file-upload-card">
                        <label>Upload Letter</label>
                        <div class="file-upload-button">
                            <span>Choose File</span>
                            <input type="file" name="upload_letter" />
                        </div>
                    </div>

                    <div class="form-group file-upload-card">
                        <label>Upload Address Proof</label>
                        <div class="file-upload-button">
                            <span>Choose File</span>
                            <input type="file" name="upload_address_proof" />
                        </div>
                    </div>

                    <div class="rform-actions">
                        <a href="<?php echo admin_url('hr/offboarding'); ?>" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
