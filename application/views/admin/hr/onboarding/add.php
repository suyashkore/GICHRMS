<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <!-- Page Header -->
                <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">Add Onboarding</h4>
                        <p class="page-subtitle">Create a new onboarding request</p>
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
                        padding: 8px 11px;
                        font-size: 0.85rem;
                        color: #1f2937;
                        width: 100%;
                        transition: border-color 0.15s, box-shadow 0.15s;
                        background: #ffffff;
                        outline: none;
                    }

                    .form-control:focus {
                        border-color: #378ADD;
                        box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
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

                    .expense-amount-input {
                        display: flex;
                        align-items: center;
                        width: 100%;
                        min-height: 48px;
                        background: #f8fafc;
                        border: 1px solid #d1d5db;
                        border-radius: 14px;
                        padding: 0 12px;
                        gap: 10px;
                    }

                    .expense-prefix {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 44px;
                        height: 44px;
                        border-radius: 12px;
                        background: #eaf4ff;
                        color: #185FA5;
                        font-weight: 700;
                        font-size: 1rem;
                        flex-shrink: 0;
                    }

                    .expense-amount-input .form-control {
                        border: none;
                        padding: 0;
                        margin: 0;
                        background: transparent;
                        box-shadow: none;
                        min-width: 0;
                        width: 100%;
                        height: 44px;
                        font-size: 0.95rem;
                    }

                    .form-group select.form-control,
                    .form-group input.form-control,
                    .form-group input[type="date"],
                    .form-group input[type="time"] {
                        min-height: 44px;
                        padding: 10px 12px;
                    }

                    textarea.form-control {
                        resize: vertical;
                        min-height: 78px;
                        font-family: inherit;
                    }

                    .rform-actions {
                        display: flex;
                        gap: 10px;
                        justify-content: flex-end;
                        padding-top: 14px;
                        border-top: 1px solid #f3f4f6;
                        margin-top: 6px;
                    }

                    .btn-cancel {
                        padding: 8px 20px;
                        border-radius: 8px;
                        font-size: 0.85rem;
                        background: #ffffff;
                        border: 1px solid #d1d5db;
                        color: #4b5563;
                        cursor: pointer;
                        transition: background 0.15s, border-color 0.15s;
                        font-family: inherit;
                        text-decoration: none;
                    }

                    .btn-cancel:hover {
                        background: #f3f4f6;
                        border-color: #b0bec5;
                    }

                    .btn-submit {
                        padding: 8px 22px;
                        border-radius: 8px;
                        font-size: 0.85rem;
                        background: #185FA5;
                        border: none;
                        color: #ffffff;
                        font-weight: 600;
                        cursor: pointer;
                        transition: background 0.15s;
                        font-family: inherit;
                    }

                    .btn-submit:hover {
                        background: #0C447C;
                    }
                </style>

                <form method="POST" action="<?php echo admin_url('hr/onboarding/add'); ?>" class="onboarding-form" enctype="multipart/form-data">
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
                            <input type="file" class="form-control" name="supporting_documents" />
                        </div>
                    </div>
                    <div class="rform-actions">
                        <a href="<?php echo admin_url('hr/onboarding'); ?>" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
