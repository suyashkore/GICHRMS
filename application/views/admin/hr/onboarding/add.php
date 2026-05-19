<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body" style="position: relative;">

                <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">Employee Onboarding Form</h4>
                        <p class="page-subtitle">Complete each section to submit the employee onboarding request.</p>
                    </div>
                </div>

                <style>
                    /* ── Base Layout ── */
                    .form-section {
                        border: 1px solid #e5e7eb;
                        border-radius: 12px;
                        padding: 24px;
                        margin-bottom: 20px;
                        background: #fafbfc;
                    }
                    .form-section h5 {
                        font-size: 1rem;
                        font-weight: 700;
                        color: #1f2937;
                        margin: 0 0 4px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }
                    .section-desc {
                        font-size: 0.82rem;
                        color: #6b7280;
                        margin: 0 0 18px;
                        padding-bottom: 14px;
                        border-bottom: 2px solid #185FA5;
                    }
                    .step-section { display: none; }
                    .step-section.active { display: block; }

                    /* ── Progress Bar ── */
                    .step-progress {
                        margin-bottom: 24px;
                        display: block;
                    }
                    .step-bar {
                        width: 100%; height: 8px;
                        background: #e5e7eb; border-radius: 999px;
                        overflow: hidden; margin-bottom: 12px;
                    }
                    .step-fill {
                        width: 0%; height: 100%;
                        background: linear-gradient(90deg,#185FA5,#0c447c);
                        transition: width .3s ease;
                    }
                    .step-labels {
                        display: grid;
                        grid-template-columns: repeat(9, 1fr);
                        gap: 6px; font-size: .7rem; text-align: center;
                    }
                    .step-item {
                        color: #6b7280; cursor: pointer;
                        padding: 5px 4px; border-radius: 8px;
                        transition: background .2s, color .2s;
                        line-height: 1.3;
                    }
                    .step-item .step-num {
                        display: block;
                        width: 22px; height: 22px;
                        border-radius: 50%;
                        background: #e5e7eb;
                        color: #6b7280;
                        font-weight: 700;
                        font-size: .7rem;
                        line-height: 22px;
                        margin: 0 auto 4px;
                        transition: background .2s, color .2s;
                    }
                    .step-item.active .step-num,
                    .step-item.completed .step-num {
                        background: #185FA5; color: #fff;
                    }
                    .step-item.active, .step-item.completed {
                        color: #0c447c; font-weight: 700;
                    }
                    .step-item:hover { background: #f3f4f6; }

                    /* ── Grid helpers ── */
                    .fg2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
                    .fg3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 14px; }
                    .fg4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 14px; }
                    @media(max-width:900px){ .fg4,.fg3{grid-template-columns:1fr 1fr;} }
                    @media(max-width:580px){
                        .fg2,.fg3,.fg4{grid-template-columns:1fr;}
                        .step-labels{grid-template-columns:repeat(3,1fr);}
                    }

                    /* ── Form controls ── */
                    .form-group { display: flex; flex-direction: column; gap: 4px; margin-bottom: 14px; }
                    .form-group label { font-size: .8rem; font-weight: 600; color: #374151; }
                    .form-group.required label::after { content:' *'; color:#dc2626; }
                    .form-control {
                        border: 1px solid #d1d5db; border-radius: 8px;
                        padding: 9px 12px; font-size: .85rem; color: #1f2937;
                        width: 100%; background: #fff; outline: none;
                        box-sizing: border-box;
                        transition: border-color .15s, box-shadow .15s;
                        -webkit-appearance: none;
                        appearance: none;
                    }
                    .form-control:focus { border-color:#378ADD; box-shadow:0 0 0 3px rgba(55,138,221,.12); }
                    textarea.form-control { min-height: 88px; resize: vertical; }

                    /* ── SELECT dropdown fix ── */
                    select.form-control {
                        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
                        background-repeat: no-repeat;
                        background-position: right 12px center;
                        padding-right: 36px;
                        cursor: pointer;
                    }
                    select.form-control option { color: #1f2937; background: #fff; padding: 6px; }

                    /* ── Date input fix ── */
                    input[type="text"].form-control[placeholder="DD/MM/YYYY"] { cursor: text; color: #1f2937; }

                    /* ── File input fix ── */
                    input[type="file"].form-control {
                        padding: 6px 12px; cursor: pointer;
                        color: #6b7280; font-size: .82rem;
                    }
                    input[type="file"].form-control::-webkit-file-upload-button {
                        background: #185FA5; color: #fff; border: none;
                        border-radius: 6px; padding: 5px 14px; font-size: .8rem;
                        font-weight: 600; cursor: pointer; margin-right: 10px;
                        font-family: inherit; transition: background .15s;
                    }
                    input[type="file"].form-control::-webkit-file-upload-button:hover { background: #0c447c; }
                    input[type="file"].form-control::file-selector-button {
                        background: #185FA5; color: #fff; border: none;
                        border-radius: 6px; padding: 5px 14px; font-size: .8rem;
                        font-weight: 600; cursor: pointer; margin-right: 10px;
                        font-family: inherit; transition: background .15s;
                    }

                    /* ── Checkbox / Radio ── */
                    .checkbox-group { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 6px; }
                    .checkbox-item { display: flex; align-items: center; gap: 6px; }
                    .checkbox-item input { cursor: pointer; }
                    .checkbox-item label { margin: 0; font-weight: 500; font-size: .83rem; cursor: pointer; }

                    /* ── Section Note ── */
                    .section-note {
                        background: #f0f9ff; border-left: 4px solid #185FA5;
                        padding: 10px 14px; border-radius: 4px;
                        font-size: .82rem; color: #0c5394; margin-bottom: 16px;
                    }

                    /* ── Dynamic row cards ── */
                    .dyn-cards-wrap { display: flex; flex-direction: column; gap: 16px; margin-top: 12px; }
                    .dyn-card {
                        background: #fff; border: 1px solid #e5e7eb;
                        border-radius: 12px; padding: 18px;
                        transition: box-shadow .2s;
                    }
                    .dyn-card:hover { box-shadow: 0 4px 14px rgba(24,95,165,.07); }
                    .dyn-card-header {
                        display: flex; justify-content: space-between;
                        align-items: center; margin-bottom: 16px;
                        padding-bottom: 12px; border-bottom: 1px solid #f0f0f0;
                    }
                    .dyn-card-title {
                        font-size: .9rem; font-weight: 700; color: #1f2937;
                        display: flex; align-items: center; gap: 8px;
                    }
                    .dyn-badge {
                        background: #eaf4ff; color: #185FA5;
                        font-size: .67rem; font-weight: 700;
                        padding: 2px 8px; border-radius: 20px;
                    }
                    .btn-remove-card {
                        border: none; background: #fee2e2; color: #991b1b;
                        width: 32px; height: 32px; border-radius: 8px;
                        cursor: pointer; display: inline-flex;
                        align-items: center; justify-content: center;
                        font-size: .85rem; transition: background .15s; flex-shrink: 0;
                    }
                    .btn-remove-card:hover { background: #fecaca; }

                    /* ── Photo upload ── */
                    .photo-upload-btn {
                        display: flex; align-items: center; justify-content: center;
                        gap: 8px; padding: 12px;
                        border: 2px dashed #d1d5db; border-radius: 8px;
                        background: #f9fafb; color: #6b7280;
                        cursor: pointer; font-size: .82rem;
                        transition: border-color .2s, background .2s;
                        position: relative; min-height: 44px;
                    }
                    .photo-upload-btn:hover { border-color: #185FA5; background: #eaf4ff; color: #185FA5; }
                    .photo-upload-btn input[type="file"] {
                        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
                    }
                    .upload-hint { font-size: .72rem; color: #9ca3af; margin-top: 4px; }

                    /* ── Acceptance card ── */
                    .acceptance-card {
                        background: #f0fdf4; border: 1px solid #bbf7d0;
                        border-radius: 10px; padding: 14px 16px;
                        display: flex; align-items: flex-start; gap: 12px;
                        margin-bottom: 14px;
                    }
                    .acceptance-card input[type="checkbox"] {
                        margin-top: 2px; width: 16px; height: 16px;
                        cursor: pointer; flex-shrink: 0;
                    }
                    .acceptance-card span { font-size: .84rem; color: #166534; font-weight: 500; }

                    /* ── Document Upload Grid ── */
                    .document-upload-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                        gap: 16px; margin-top: 16px;
                    }
                    .document-upload-card {
                        border: 1px solid #d1d5db; border-radius: 10px;
                        padding: 16px; background: #fff; transition: all .2s ease;
                    }
                    .document-upload-card:hover {
                        border-color: #185FA5;
                        box-shadow: 0 2px 8px rgba(24,95,165,.10);
                    }
                    .document-upload-card h6 {
                        font-size: .85rem; font-weight: 700; color: #1f2937;
                        margin: 0 0 12px; display: flex; align-items: center; gap: 7px;
                    }
                    .doc-icon { color: #185FA5; font-size: 1rem; }
                    .document-upload-button {
                        display: flex; align-items: center; justify-content: center;
                        min-height: 44px; padding: 8px 12px;
                        border: 2px dashed #d1d5db; border-radius: 8px;
                        background: #f9fafb; color: #6b7280;
                        cursor: pointer; position: relative;
                        font-size: .8rem; text-align: center; transition: all .2s ease;
                    }
                    .document-upload-button:hover {
                        border-color: #185FA5; background: #eaf4ff; color: #185FA5;
                    }
                    .document-upload-button input[type="file"] {
                        position: absolute; inset: 0; width: 100%; height: 100%;
                        opacity: 0; cursor: pointer;
                    }
                    .file-name-display {
                        font-size: .75rem; color: #185FA5; margin-top: 4px;
                        word-break: break-all; display: none;
                    }

                    /* ── Qualification Table ── */
                    .qualification-table {
                        width: 100%; border-collapse: collapse; margin-top: 8px;
                    }
                    .qualification-table th,
                    .qualification-table td {
                        border: 1px solid #d1d5db; padding: 10px;
                        text-align: left; font-size: .85rem;
                    }
                    .qualification-table th {
                        background: #f3f4f6; font-weight: 600; color: #374151;
                    }
                    .qualification-table input,
                    .qualification-table select {
                        width: 100%; padding: 6px 8px; border: none;
                        background: transparent; font-size: .85rem;
                        outline: none; box-sizing: border-box; font-family: inherit;
                    }
                    .qualification-table select {
                        cursor: pointer;
                    }

                    /* ── Policy cards ── */
                    .policy-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                        gap: 14px; margin-top: 4px;
                    }
                    .policy-card {
                        background: #fff; border: 2px solid #e5e7eb;
                        border-radius: 12px; padding: 18px 16px;
                        cursor: pointer; display: flex; gap: 12px;
                        align-items: flex-start;
                        transition: border-color .2s, box-shadow .2s;
                    }
                    .policy-card:hover { border-color: #185FA5; box-shadow: 0 2px 10px rgba(24,95,165,.10); }
                    .policy-card:has(input:checked) { border-color: #185FA5; background: #f0f7ff; }
                    .policy-card input[type="checkbox"] {
                        margin-top: 3px; width: 16px; height: 16px;
                        flex-shrink: 0; cursor: pointer;
                    }
                    .policy-icon {
                        width: 36px; height: 36px; border-radius: 10px;
                        background: #eaf4ff; color: #185FA5;
                        display: flex; align-items: center; justify-content: center;
                        font-size: .95rem; flex-shrink: 0;
                    }
                    .policy-text strong { display: block; font-size: .84rem; color: #1f2937; margin-bottom: 3px; }
                    .policy-text p { font-size: .75rem; color: #6b7280; margin: 0; line-height: 1.4; }

                    /* ── Signature Section ── */
                    .signature-modal {
                        position: absolute; inset: 0;
                        background: rgba(15,23,42,.60);
                        display: none; align-items: flex-start;
                        justify-content: center; z-index: 9999;
                        padding: 60px 20px 20px; border-radius: 8px;
                    }
                    .signature-modal.open { display: flex; }
                    .signature-modal-content {
                        width: min(100%,680px); background: #fff;
                        border-radius: 16px;
                        box-shadow: 0 18px 60px rgba(15,23,42,.20);
                        overflow: hidden; position: relative;
                    }
                    .signature-modal-header {
                        display: flex; align-items: center; justify-content: space-between;
                        padding: 16px 20px; border-bottom: 1px solid #e5e7eb; background: #f8fafc;
                    }
                    .signature-modal-header h6 { margin: 0; font-size: 1rem; font-weight: 700; color: #111827; }
                    .signature-modal-header button {
                        border: none; background: transparent; font-size: 1.5rem;
                        cursor: pointer; color: #374151; line-height: 1; padding: 0 4px;
                    }
                    .signature-modal-header button:hover { color: #dc2626; }
                    .signature-canvas-wrapper { padding: 16px; background: #fff; }
                    .signature-canvas {
                        width: 100%; height: 220px; border: 1px solid #d1d5db;
                        border-radius: 12px; touch-action: none; cursor: crosshair;
                        display: block; background: #fff;
                    }
                    .signature-hint { text-align: center; font-size: .75rem; color: #9ca3af; margin-top: 8px; }
                    .signature-modal-actions {
                        display: flex; gap: 12px; justify-content: flex-end;
                        padding: 14px 20px 20px; border-top: 1px solid #e5e7eb; background: #f8fafc;
                    }
                    .signature-display {
                        min-height: 60px; border: 2px dashed #d1d5db;
                        border-radius: 10px; background: #fff;
                        display: flex; align-items: center; justify-content: center;
                        color: #6b7280; cursor: pointer; padding: 12px;
                        font-size: .82rem; text-align: center;
                        transition: border-color .2s, background .2s;
                    }
                    .signature-display:hover { border-color: #185FA5; background: #f3f7ff; color: #185FA5; }
                    .signature-display.has-value { border-style: solid; border-color: #185FA5; padding: 6px; }
                    .signature-section {
                        display: grid; grid-template-columns: 1fr 1fr 1fr;
                        gap: 20px; margin-top: 20px;
                    }
                    @media(max-width:580px){ .signature-section{grid-template-columns:1fr;} }
                    .signature-field { display: flex; flex-direction: column; gap: 8px; }
                    .signature-field label { font-weight: 600; font-size: .8rem; color: #374151; }

                    /* ── Buttons ── */
                    .rform-actions {
                        display: flex; gap: 10px; justify-content: flex-end;
                        padding-top: 20px; border-top: 1px solid #e5e7eb; margin-top: 20px;
                    }
                    .btn-cancel {
                        padding: 10px 22px; border-radius: 8px; font-size: .85rem;
                        background: #fff; border: 1px solid #d1d5db; color: #4b5563;
                        cursor: pointer; font-family: inherit; font-weight: 500;
                        display: inline-flex; align-items: center; gap: 6px;
                        transition: background .15s; text-decoration: none;
                    }
                    .btn-cancel:hover { background: #f3f4f6; }
                    .btn-submit {
                        padding: 10px 24px; border-radius: 8px; font-size: .85rem;
                        background: #185FA5; border: none; color: #fff;
                        font-weight: 600; cursor: pointer; font-family: inherit;
                        display: inline-flex; align-items: center; gap: 6px;
                        transition: background .15s;
                    }
                    .btn-submit:hover { background: #0C447C; }
                    .btn-reset {
                        padding: 10px 22px; border-radius: 8px; font-size: .85rem;
                        background: #fff; border: 1px solid #f59e0b; color: #b45309;
                        cursor: pointer; font-family: inherit; font-weight: 500;
                        display: inline-flex; align-items: center; gap: 6px;
                        transition: background .15s;
                    }
                    .btn-reset:hover { background: #fffbeb; }
                    .btn-add {
                        padding: 9px 18px; border-radius: 8px; font-size: .82rem;
                        background: #fff; border: 1.5px dashed #185FA5; color: #185FA5;
                        cursor: pointer; font-family: inherit; font-weight: 600;
                        display: inline-flex; align-items: center; gap: 6px;
                        transition: background .15s; margin-bottom: 4px;
                    }
                    .btn-add:hover { background: #eaf4ff; }
                    .btn-remove-row {
                        padding: 5px 12px; border-radius: 6px; font-size: .78rem;
                        background: #fee2e2; border: none; color: #991b1b;
                        cursor: pointer; font-family: inherit; font-weight: 500;
                        transition: background .15s;
                    }
                    .btn-remove-row:hover { background: #fecaca; }
                </style>

                <form method="POST" action="<?php echo admin_url('hr/onboarding/add'); ?>" enctype="multipart/form-data" id="onboarding-form">

                    <!-- ══ Step Progress ══ -->
                    <div class="step-progress" id="step-progress-wrap">
                        <div class="step-bar"><div class="step-fill" id="step-fill"></div></div>
                        <div class="step-labels">
                            <div class="step-item" data-step="1"><span class="step-num">1</span>Personal</div>
                            <div class="step-item" data-step="2"><span class="step-num">2</span>Identity</div>
                            <div class="step-item" data-step="3"><span class="step-num">3</span>Employment</div>
                            <div class="step-item" data-step="4"><span class="step-num">4</span>Education</div>
                            <div class="step-item" data-step="5"><span class="step-num">5</span>Experience</div>
                            <div class="step-item" data-step="6"><span class="step-num">6</span>Bank</div>
                            <div class="step-item" data-step="7"><span class="step-num">7</span>Emergency</div>
                            <div class="step-item" data-step="8"><span class="step-num">8</span>Documents</div>
                            <div class="step-item" data-step="9"><span class="step-num">9</span>Declaration</div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 1 — Personal Details
                    ══════════════════════════════════ -->
                    <div class="form-section step-section active" data-step="1">
                        <h5><i class="fa fa-user" style="color:#185FA5;"></i> Personal Details</h5>
                        <p class="section-desc">Basic personal and contact information of the employee.</p>
                        <div class="fg2">
                            <div class="form-group required">
                                <label>Full Name (as per Aadhaar/PAN)</label>
                                <input type="text" name="full_name" class="form-control" placeholder="Enter full name" required />
                            </div>
                            <div class="form-group">
                                <label>Father's Name</label>
                                <input type="text" name="father_name" class="form-control" placeholder="Enter father's name" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Mother's Name</label>
                                <input type="text" name="mother_name" class="form-control" placeholder="Enter mother's name" />
                            </div>
                            <div class="form-group required">
                                <label>Date of Birth</label>
                                <input type="text" name="dob" class="form-control" placeholder="DD/MM/YYYY" maxlength="10" autocomplete="off" oninput="autoDateSlash(this)" required />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item"><input type="radio" id="g_male" name="gender" value="Male"><label for="g_male">Male</label></div>
                                    <div class="checkbox-item"><input type="radio" id="g_female" name="gender" value="Female"><label for="g_female">Female</label></div>
                                    <div class="checkbox-item"><input type="radio" id="g_other" name="gender" value="Other"><label for="g_other">Other</label></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Marital Status</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item"><input type="radio" id="ms_single" name="marital_status" value="Single"><label for="ms_single">Single</label></div>
                                    <div class="checkbox-item"><input type="radio" id="ms_married" name="marital_status" value="Married"><label for="ms_married">Married</label></div>
                                    <div class="checkbox-item"><input type="radio" id="ms_divorced" name="marital_status" value="Divorced"><label for="ms_divorced">Divorced</label></div>
                                    <div class="checkbox-item"><input type="radio" id="ms_widowed" name="marital_status" value="Widowed"><label for="ms_widowed">Widowed</label></div>
                                </div>
                            </div>
                        </div>
                        <div class="fg3">
                            <div class="form-group">
                                <label>Blood Group</label>
                                <input type="text" name="blood_group" class="form-control" placeholder="e.g. O+, A-, B+" />
                            </div>
                            <div class="form-group required">
                                <label>Mobile Number</label>
                                <input type="tel" name="mobile_number" class="form-control" placeholder="Enter mobile number" required />
                            </div>
                            <div class="form-group">
                                <label>Personal Email ID</label>
                                <input type="email" name="personal_email" class="form-control" placeholder="Enter personal email" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Nationality</label>
                                <input type="text" name="nationality" class="form-control" placeholder="Enter nationality" />
                            </div>
                            <div class="form-group">
                                <label>Religion</label>
                                <input type="text" name="religion" class="form-control" placeholder="Enter religion (optional)" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Current Address</label>
                                <textarea name="current_address" class="form-control" placeholder="Enter current residential address"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" placeholder="Enter permanent address"></textarea>
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Employee Photo</label>
                                <div class="photo-upload-btn">
                                    <i class="fa fa-camera"></i>
                                    <span id="photo-label-text">Upload Passport Photo</span>
                                    <input type="file" name="employee_photo" accept="image/*" onchange="updatePhotoLabel(this)" />
                                </div>
                                <span class="upload-hint">JPG, PNG — Max 2MB</span>
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 2 — Identity & KYC
                    ══════════════════════════════════ -->
                    <div class="form-section step-section" data-step="2">
                        <h5><i class="fa fa-id-card" style="color:#185FA5;"></i> Identity &amp; KYC Details</h5>
                        <p class="section-desc">Government-issued identity and KYC numbers.</p>
                        <div class="section-note"><i class="fa fa-info-circle"></i> All identity numbers are stored securely and used only for compliance and payroll purposes.</div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Aadhaar Number</label>
                                <input type="text" name="aadhaar_number" class="form-control" placeholder="12-digit Aadhaar number" maxlength="12" />
                            </div>
                            <div class="form-group">
                                <label>PAN Number</label>
                                <input type="text" name="pan_number" class="form-control" placeholder="e.g. ABCDE1234F" maxlength="10" style="text-transform:uppercase;" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Passport Number (if applicable)</label>
                                <input type="text" name="passport_number" class="form-control" placeholder="Enter passport number" />
                            </div>
                            <div class="form-group">
                                <label>Passport Expiry Date</label>
                                <input type="text" name="passport_expiry" class="form-control" placeholder="DD/MM/YYYY" maxlength="10" autocomplete="off" oninput="autoDateSlash(this)" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Driving License Number (if applicable)</label>
                                <input type="text" name="dl_number" class="form-control" placeholder="Enter DL number" />
                            </div>
                            <div class="form-group">
                                <label>Driving License Expiry</label>
                                <input type="text" name="dl_expiry" class="form-control" placeholder="DD/MM/YYYY" maxlength="10" autocomplete="off" oninput="autoDateSlash(this)" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>UAN Number (if available)</label>
                                <input type="text" name="uan_number" class="form-control" placeholder="12-digit UAN" maxlength="12" />
                            </div>
                            <div class="form-group">
                                <label>ESIC Number (if available)</label>
                                <input type="text" name="esic_number" class="form-control" placeholder="Enter ESIC number" />
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 3 — Employment Details
                    ══════════════════════════════════ -->
                    <div class="form-section step-section" data-step="3">
                        <h5><i class="fa fa-briefcase" style="color:#185FA5;"></i> Employment Details</h5>
                        <p class="section-desc">Current employment and role information.</p>
                        <div class="fg2">
                            <div class="form-group required">
                                <label>Employee ID</label>
                                <input type="text" name="employee_id" class="form-control" placeholder="Auto-generated or manual ID" required />
                            </div>
                            <div class="form-group required">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control" placeholder="Enter job designation" required />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group required">
                                <label>Department</label>
                                <select name="department" class="form-control" required>
                                    <option value="">— Select Department —</option>
                                    <option value="HR">HR</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Development">Development</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Support">Support</option>
                                    <option value="Administration">Administration</option>
                                    <option value="Legal">Legal</option>
                                    <option value="Procurement">Procurement</option>
                                </select>
                            </div>
                            <div class="form-group required">
                                <label>Date of Joining</label>
                                <input type="text" name="joining_date" class="form-control" placeholder="DD/MM/YYYY" maxlength="10" autocomplete="off" oninput="autoDateSlash(this)" required />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Reporting Manager</label>
                                <input type="text" name="reporting_manager" class="form-control" placeholder="Enter reporting manager's name" />
                            </div>
                            <div class="form-group">
                                <label>Work Location</label>
                                <input type="text" name="work_location" class="form-control" placeholder="Enter work location / office" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Employment Type</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item"><input type="radio" id="et_perm" name="employment_type" value="Permanent"><label for="et_perm">Permanent</label></div>
                                    <div class="checkbox-item"><input type="radio" id="et_cont" name="employment_type" value="Contract"><label for="et_cont">Contract</label></div>
                                    <div class="checkbox-item"><input type="radio" id="et_prob" name="employment_type" value="Probation"><label for="et_prob">Probation</label></div>
                                    <div class="checkbox-item"><input type="radio" id="et_intern" name="employment_type" value="Internship"><label for="et_intern">Internship</label></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Shift Timing</label>
                                <select name="shift_timing" class="form-control">
                                    <option value="">— Select Shift —</option>
                                    <option value="General">General (9 AM – 6 PM)</option>
                                    <option value="Morning">Morning Shift</option>
                                    <option value="Night">Night Shift</option>
                                    <option value="Rotational">Rotational</option>
                                    <option value="Flexible">Flexible / WFH</option>
                                </select>
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Official Email ID</label>
                                <input type="email" name="official_email" class="form-control" placeholder="e.g. john.doe@company.com" />
                            </div>
                            <div class="form-group">
                                <label>Probation Period (months)</label>
                                <input type="number" name="probation_months" class="form-control" placeholder="e.g. 3" min="0" max="24" />
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 4 — Education
                    ══════════════════════════════════ -->
                    <div class="form-section step-section" data-step="4">
                        <h5><i class="fa fa-graduation-cap" style="color:#185FA5;"></i> Educational Qualification</h5>
                        <p class="section-desc">Academic qualifications and institution details.</p>
                        <button type="button" class="btn-add" id="btn-add-education">
                            <i class="fa fa-plus"></i> Add Qualification
                        </button>
                        <div style="overflow-x:auto;margin-top:8px;">
                            <table class="qualification-table" id="education-table">
                                <thead>
                                    <tr>
                                        <th style="width:16%;">Qualification</th>
                                        <th style="width:18%;">Specialization</th>
                                        <th>Institute / University</th>
                                        <th style="width:10%;">Year</th>
                                        <th style="width:15%;">Percentage / CGPA</th>
                                        <th style="width:8%;text-align:center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="education-tbody">
                                    <tr>
                                        <td><input type="text" name="education[0][qualification]" data-group="education" data-field="qualification" value="10th" /></td>
                                        <td><input type="text" name="education[0][specialization]" data-group="education" data-field="specialization" placeholder="Stream" /></td>
                                        <td><input type="text" name="education[0][institute]" data-group="education" data-field="institute" placeholder="School name" /></td>
                                        <td><input type="number" name="education[0][year]" data-group="education" data-field="year" placeholder="Year" min="1970" max="<?php echo date('Y'); ?>" /></td>
                                        <td><input type="text" name="education[0][percentage]" data-group="education" data-field="percentage" placeholder="e.g. 85%" /></td>
                                        <td style="text-align:center;"><button type="button" class="btn-remove-row remove-edu-row">Remove</button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="education[1][qualification]" data-group="education" data-field="qualification" value="12th" /></td>
                                        <td><input type="text" name="education[1][specialization]" data-group="education" data-field="specialization" placeholder="Stream" /></td>
                                        <td><input type="text" name="education[1][institute]" data-group="education" data-field="institute" placeholder="College name" /></td>
                                        <td><input type="number" name="education[1][year]" data-group="education" data-field="year" placeholder="Year" min="1970" max="<?php echo date('Y'); ?>" /></td>
                                        <td><input type="text" name="education[1][percentage]" data-group="education" data-field="percentage" placeholder="e.g. 78%" /></td>
                                        <td style="text-align:center;"><button type="button" class="btn-remove-row remove-edu-row">Remove</button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="education[2][qualification]" data-group="education" data-field="qualification" value="Graduation" /></td>
                                        <td><input type="text" name="education[2][specialization]" data-group="education" data-field="specialization" placeholder="Specialization" /></td>
                                        <td><input type="text" name="education[2][institute]" data-group="education" data-field="institute" placeholder="University name" /></td>
                                        <td><input type="number" name="education[2][year]" data-group="education" data-field="year" placeholder="Year" min="1970" max="<?php echo date('Y'); ?>" /></td>
                                        <td><input type="text" name="education[2][percentage]" data-group="education" data-field="percentage" placeholder="e.g. 7.5 CGPA" /></td>
                                        <td style="text-align:center;"><button type="button" class="btn-remove-row remove-edu-row">Remove</button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="education[3][qualification]" data-group="education" data-field="qualification" value="Post Graduation" /></td>
                                        <td><input type="text" name="education[3][specialization]" data-group="education" data-field="specialization" placeholder="Specialization" /></td>
                                        <td><input type="text" name="education[3][institute]" data-group="education" data-field="institute" placeholder="University name" /></td>
                                        <td><input type="number" name="education[3][year]" data-group="education" data-field="year" placeholder="Year" min="1970" max="<?php echo date('Y'); ?>" /></td>
                                        <td><input type="text" name="education[3][percentage]" data-group="education" data-field="percentage" placeholder="e.g. 8.0 CGPA" /></td>
                                        <td style="text-align:center;"><button type="button" class="btn-remove-row remove-edu-row">Remove</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 5 — Previous Employment
                    ══════════════════════════════════ -->
                    <div class="form-section step-section" data-step="5">
                        <h5><i class="fa fa-history" style="color:#185FA5;"></i> Previous Employment Details</h5>
                        <p class="section-desc">Work history and past employment records.</p>
                        <button type="button" class="btn-add" id="btn-add-employment">
                            <i class="fa fa-plus"></i> Add Employment
                        </button>
                        <div style="overflow-x:auto;margin-top:8px;">
                            <table class="qualification-table" id="employment-table">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Designation</th>
                                        <th style="width:12%;">Start Date</th>
                                        <th style="width:12%;">End Date</th>
                                        <th style="width:12%;">CTC (p.a.)</th>
                                        <th style="width:14%;">Notice Period</th>
                                        <th style="width:8%;text-align:center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="employment-tbody">
                                    <tr>
                                        <td><input type="text" name="employment[0][company]" data-group="employment" data-field="company" placeholder="Company name" /></td>
                                        <td><input type="text" name="employment[0][designation]" data-group="employment" data-field="designation" placeholder="Designation" /></td>
                                        <td><input type="text" name="employment[0][start_date]" data-group="employment" data-field="start_date" placeholder="DD/MM/YYYY" maxlength="10" oninput="autoDateSlash(this)" /></td>
                                        <td><input type="text" name="employment[0][end_date]" data-group="employment" data-field="end_date" placeholder="DD/MM/YYYY" maxlength="10" oninput="autoDateSlash(this)" /></td>
                                        <td><input type="number" name="employment[0][ctc]" data-group="employment" data-field="ctc" placeholder="0.00" step="0.01" min="0" /></td>
                                        <td><input type="text" name="employment[0][notice]" data-group="employment" data-field="notice" placeholder="e.g. 30 days" /></td>
                                        <td style="text-align:center;"><button type="button" class="btn-remove-row remove-emp-row">Remove</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="fg2" style="margin-top:14px;">
                            <div class="form-group">
                                <label>Total Work Experience (years)</label>
                                <input type="number" name="total_experience" class="form-control" placeholder="e.g. 3.5" step="0.5" min="0" />
                            </div>
                            <div class="form-group">
                                <label>Notice Period (current/last job)</label>
                                <input type="text" name="current_notice_period" class="form-control" placeholder="e.g. 30 days, 2 months" />
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 6 — Bank Details
                    ══════════════════════════════════ -->
                    <div class="form-section step-section" data-step="6">
                        <h5><i class="fa fa-university" style="color:#185FA5;"></i> Bank Details</h5>
                        <p class="section-desc">Salary account information for payroll processing.</p>
                        <div class="section-note"><i class="fa fa-info-circle"></i> Bank details are used solely for salary credit and statutory payments.</div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name" />
                            </div>
                            <div class="form-group">
                                <label>Account Holder Name</label>
                                <input type="text" name="account_holder" class="form-control" placeholder="As per passbook" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Account Number</label>
                                <input type="text" name="account_number" class="form-control" placeholder="Enter account number" />
                            </div>
                            <div class="form-group">
                                <label>Confirm Account Number</label>
                                <input type="text" name="account_number_confirm" class="form-control" placeholder="Re-enter account number" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control" placeholder="Enter IFSC code" style="text-transform:uppercase;" />
                            </div>
                            <div class="form-group">
                                <label>Branch Name</label>
                                <input type="text" name="branch_name" class="form-control" placeholder="Enter branch name" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>Account Type</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item"><input type="radio" id="acc_savings" name="account_type" value="Savings"><label for="acc_savings">Savings</label></div>
                                    <div class="checkbox-item"><input type="radio" id="acc_current" name="account_type" value="Current"><label for="acc_current">Current</label></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 7 — Emergency Contact & Statutory
                    ══════════════════════════════════ -->
                    <div class="form-section step-section" data-step="7">
                        <h5><i class="fa fa-phone" style="color:#185FA5;"></i> Emergency Contact &amp; Statutory Info</h5>
                        <p class="section-desc">Emergency contact details and PF/ESIC statutory information.</p>

                        <h6 style="font-size:.88rem;font-weight:700;color:#1f2937;margin:0 0 12px;padding-bottom:8px;border-bottom:1px solid #e5e7eb;">
                            <i class="fa fa-exclamation-circle" style="color:#185FA5;"></i> Emergency Contact
                        </h6>
                        <div class="fg3">
                            <div class="form-group">
                                <label>Contact Person Name</label>
                                <input type="text" name="emergency_name" class="form-control" placeholder="Enter full name" />
                            </div>
                            <div class="form-group">
                                <label>Relationship</label>
                                <input type="text" name="emergency_relation" class="form-control" placeholder="e.g. Father, Spouse" />
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="tel" name="emergency_mobile" class="form-control" placeholder="Enter contact number" />
                            </div>
                        </div>

                        <h6 style="font-size:.88rem;font-weight:700;color:#1f2937;margin:16px 0 12px;padding-bottom:8px;border-bottom:1px solid #e5e7eb;">
                            <i class="fa fa-file-text-o" style="color:#185FA5;"></i> Statutory Information
                        </h6>
                        <div class="fg2">
                            <div class="form-group">
                                <label>PF Applicable</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item"><input type="radio" id="pf_yes" name="pf_applicable" value="Yes"><label for="pf_yes">Yes</label></div>
                                    <div class="checkbox-item"><input type="radio" id="pf_no" name="pf_applicable" value="No"><label for="pf_no">No</label></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>PF UAN Number</label>
                                <input type="text" name="pf_uan" class="form-control" placeholder="12-digit UAN number" maxlength="12" />
                            </div>
                        </div>
                        <div class="fg2">
                            <div class="form-group">
                                <label>ESIC Applicable</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item"><input type="radio" id="esic_yes" name="esic_applicable" value="Yes"><label for="esic_yes">Yes</label></div>
                                    <div class="checkbox-item"><input type="radio" id="esic_no" name="esic_applicable" value="No"><label for="esic_no">No</label></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>ESIC Number (if already exists)</label>
                                <input type="text" name="esic_number_stat" class="form-control" placeholder="Enter ESIC number" />
                            </div>
                        </div>
                        <div class="fg3">
                            <div class="form-group">
                                <label>Nominee Name</label>
                                <input type="text" name="nominee_name" class="form-control" placeholder="Enter nominee name" />
                            </div>
                            <div class="form-group">
                                <label>Relationship with Nominee</label>
                                <input type="text" name="nominee_relation" class="form-control" placeholder="e.g. Spouse, Child" />
                            </div>
                            <div class="form-group">
                                <label>Nominee Date of Birth</label>
                                <input type="text" name="nominee_dob" class="form-control" placeholder="DD/MM/YYYY" maxlength="10" autocomplete="off" oninput="autoDateSlash(this)" />
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 8 — Documents
                    ══════════════════════════════════ -->
                    <div class="form-section step-section" data-step="8">
                        <h5><i class="fa fa-folder-open" style="color:#185FA5;"></i> Documents Submitted</h5>
                        <p class="section-desc">Upload all required documents for verification and records.</p>
                        <div class="section-note">
                            <i class="fa fa-info-circle"></i> Upload each document in PDF or Image format (JPG, PNG). Max 5MB per file.
                        </div>
                        <div class="document-upload-grid">

                            <div class="document-upload-card">
                                <h6><i class="fa fa-file-text-o doc-icon"></i> Resume / CV</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_resume" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_resume')" />
                                </div>
                                <div class="file-name-display" id="lbl_resume"></div>
                                <div class="upload-hint">PDF, DOC, DOCX, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-camera doc-icon"></i> Passport Photographs</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_photo" accept=".jpg,.jpeg,.png" onchange="showFileName(this,'lbl_photo')" />
                                </div>
                                <div class="file-name-display" id="lbl_photo"></div>
                                <div class="upload-hint">JPG, PNG only</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-id-card doc-icon"></i> Aadhaar Card</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_aadhaar" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_aadhaar')" />
                                </div>
                                <div class="file-name-display" id="lbl_aadhaar"></div>
                                <div class="upload-hint">PDF, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-credit-card doc-icon"></i> PAN Card</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_pan" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_pan')" />
                                </div>
                                <div class="file-name-display" id="lbl_pan"></div>
                                <div class="upload-hint">PDF, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-university doc-icon"></i> Bank Passbook / Cheque</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_bank" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_bank')" />
                                </div>
                                <div class="file-name-display" id="lbl_bank"></div>
                                <div class="upload-hint">PDF, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-graduation-cap doc-icon"></i> Educational Certificates</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_education" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_edu')" />
                                </div>
                                <div class="file-name-display" id="lbl_edu"></div>
                                <div class="upload-hint">PDF, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-briefcase doc-icon"></i> Experience Letters</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_experience" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_exp')" />
                                </div>
                                <div class="file-name-display" id="lbl_exp"></div>
                                <div class="upload-hint">PDF, DOC, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-money doc-icon"></i> Salary Slips (Last 3 Months)</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_salary" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_salary')" />
                                </div>
                                <div class="file-name-display" id="lbl_salary"></div>
                                <div class="upload-hint">PDF, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-file-text doc-icon"></i> Relieving Letter</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_relieving" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_relieve')" />
                                </div>
                                <div class="file-name-display" id="lbl_relieve"></div>
                                <div class="upload-hint">PDF, DOC, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-plane doc-icon"></i> Passport (if applicable)</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_passport" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_passport')" />
                                </div>
                                <div class="file-name-display" id="lbl_passport"></div>
                                <div class="upload-hint">PDF, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-car doc-icon"></i> Driving License (if applicable)</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_dl" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_dl')" />
                                </div>
                                <div class="file-name-display" id="lbl_dl"></div>
                                <div class="upload-hint">PDF, JPG, PNG</div>
                            </div>

                            <div class="document-upload-card">
                                <h6><i class="fa fa-shield doc-icon"></i> BGV / Police Clearance</h6>
                                <div class="document-upload-button">
                                    <span><i class="fa fa-upload"></i> &nbsp;Choose File</span>
                                    <input type="file" name="doc_bgv" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this,'lbl_bgv')" />
                                </div>
                                <div class="file-name-display" id="lbl_bgv"></div>
                                <div class="upload-hint">PDF, JPG, PNG</div>
                            </div>

                        </div>
                    </div>

                    <!-- ══════════════════════════════════
                         STEP 9 — Declaration & Compliance
                    ══════════════════════════════════ -->
                    <div class="form-section step-section" data-step="9">
                        <h5><i class="fa fa-shield" style="color:#185FA5;"></i> Declaration &amp; Compliance</h5>
                        <p class="section-desc">Read and acknowledge the declaration and company policies before submitting.</p>
                        <div class="section-note">
                            <i class="fa fa-info-circle"></i>
                            Please read and acknowledge each policy. All checkboxes must be confirmed before submitting.
                        </div>
                        <div class="policy-grid">
                            <label class="policy-card">
                                <input type="checkbox" name="policy_declaration" value="1" />
                                <div class="policy-icon"><i class="fa fa-file-text-o"></i></div>
                                <div class="policy-text">
                                    <strong>Declaration of Truth</strong>
                                    <p>I declare that all information provided is true and correct to the best of my knowledge.</p>
                                </div>
                            </label>
                            <label class="policy-card">
                                <input type="checkbox" name="policy_nda" value="1" />
                                <div class="policy-icon" style="background:#fee2e2;color:#dc2626;"><i class="fa fa-lock"></i></div>
                                <div class="policy-text">
                                    <strong>NDA / Confidentiality</strong>
                                    <p>I agree to maintain confidentiality and not disclose company information to third parties.</p>
                                </div>
                            </label>
                            <label class="policy-card">
                                <input type="checkbox" name="policy_code_conduct" value="1" />
                                <div class="policy-icon" style="background:#dcfce7;color:#16a34a;"><i class="fa fa-gavel"></i></div>
                                <div class="policy-text">
                                    <strong>Code of Conduct</strong>
                                    <p>I agree to abide by the company's code of conduct, ethics, and workplace policies.</p>
                                </div>
                            </label>
                            <label class="policy-card">
                                <input type="checkbox" name="policy_data_privacy" value="1" />
                                <div class="policy-icon" style="background:#ede9fe;color:#7c3aed;"><i class="fa fa-database"></i></div>
                                <div class="policy-text">
                                    <strong>Data Privacy Policy</strong>
                                    <p>I consent to the company processing my personal data as required for employment purposes.</p>
                                </div>
                            </label>
                            <label class="policy-card">
                                <input type="checkbox" name="policy_it" value="1" />
                                <div class="policy-icon" style="background:#fef9c3;color:#d97706;"><i class="fa fa-desktop"></i></div>
                                <div class="policy-text">
                                    <strong>IT &amp; Security Policy</strong>
                                    <p>I agree to follow company IT policies including password guidelines and secure device usage.</p>
                                </div>
                            </label>
                        </div>

                        <!-- Signature Section -->
                        <div style="margin-top:24px;">
                            <h6 style="font-size:.88rem;font-weight:700;color:#1f2937;margin:0 0 16px;padding-bottom:8px;border-bottom:1px solid #e5e7eb;">
                                <i class="fa fa-pencil" style="color:#185FA5;"></i> Digital Signature
                            </h6>
                        </div>
                        <div class="signature-section">
                            <div class="signature-field">
                                <label>Employee Signature</label>
                                <input type="hidden" name="employee_signature" id="employee_signature" />
                                <div class="signature-display" id="signature-display">
                                    <i class="fa fa-pencil-square-o"></i>&nbsp; Click here to draw signature
                                </div>
                            </div>
                            <div class="signature-field">
                                <label>Declaration Date</label>
                                <input type="text" name="declaration_date" id="declaration_date"
                                    class="form-control" placeholder="DD/MM/YYYY" maxlength="10"
                                    autocomplete="off" oninput="autoDateSlash(this)" />
                            </div>
                            <div class="signature-field">
                                <label>Place</label>
                                <input type="text" name="declaration_place" class="form-control" placeholder="City / Location" />
                            </div>
                        </div>
                    </div>

                    <!-- ══ Signature Modal ══ -->
                    <div class="signature-modal" id="signature-modal">
                        <div class="signature-modal-content">
                            <div class="signature-modal-header">
                                <h6><i class="fa fa-pencil"></i> Draw Your Signature</h6>
                                <button type="button" id="signature-close" title="Close">&times;</button>
                            </div>
                            <div class="signature-canvas-wrapper">
                                <canvas id="signature-canvas" class="signature-canvas"></canvas>
                                <p class="signature-hint">Draw your signature using mouse or touch</p>
                            </div>
                            <div class="signature-modal-actions">
                                <button type="button" class="btn-cancel" id="signature-clear"><i class="fa fa-trash-o"></i> Clear</button>
                                <button type="button" class="btn-submit" id="signature-save"><i class="fa fa-check"></i> Save Signature</button>
                            </div>
                        </div>
                    </div>

                    <!-- ══ Navigation ══ -->
                    <div class="rform-actions">
                        <button type="button" class="btn-cancel" id="step-prev" style="display:none;">
                            <i class="fa fa-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn-submit" id="step-next" style="display:none;">
                            Next <i class="fa fa-arrow-right"></i>
                        </button>
                        <button type="submit" class="btn-submit" id="form-submit" style="display:none;">
                            <i class="fa fa-check"></i> Submit Onboarding Form
                        </button>
                        <button type="button" class="btn-reset" id="step-reset">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                    </div>

                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ═══════════════════════════════════
       STEP NAVIGATION
       Step 1 = Home (no progress bar)
       Steps 2-10 = main flow
    ═══════════════════════════════════ */
    var steps        = Array.from(document.querySelectorAll('.step-section'));
    var progressWrap = document.getElementById('step-progress-wrap');
    var progressFill = document.getElementById('step-fill');
    var stepItems    = Array.from(document.querySelectorAll('.step-item[data-step]'));
    var prevBtn      = document.getElementById('step-prev');
    var nextBtn      = document.getElementById('step-next');
    var submitBtn    = document.getElementById('form-submit');
    var totalSteps   = steps.length;   // 9
    var currentStep  = 1;

    function updateStepDisplay() {
        steps.forEach(function (s) {
            s.classList.toggle('active', Number(s.dataset.step) === currentStep);
        });

        var pct = totalSteps > 1 ? ((currentStep - 1) / (totalSteps - 1)) * 100 : 0;
        progressFill.style.width = Math.min(pct, 100) + '%';

        stepItems.forEach(function (item) {
            var n = Number(item.dataset.step);
            item.classList.toggle('active',    n === currentStep);
            item.classList.toggle('completed', n < currentStep);
        });

        prevBtn.style.display   = currentStep === 1          ? 'none' : 'inline-flex';
        nextBtn.style.display   = currentStep === totalSteps ? 'none' : 'inline-flex';
        submitBtn.style.display = currentStep === totalSteps ? 'inline-flex' : 'none';
    }

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        currentStep = step;
        updateStepDisplay();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    prevBtn.addEventListener('click', function () { goToStep(currentStep - 1); });
    nextBtn.addEventListener('click', function () { goToStep(currentStep + 1); });

    stepItems.forEach(function (item) {
        item.addEventListener('click', function () { goToStep(Number(item.dataset.step)); });
    });

    /* Reset current section */
    document.getElementById('step-reset').addEventListener('click', function () {
        var sec = document.querySelector('.step-section[data-step="' + currentStep + '"]');
        if (!sec) return;
        sec.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (el.type === 'checkbox' || el.type === 'radio') { el.checked = false; }
            else if (el.type === 'number') { el.value = el.min || '0'; }
            else { el.value = ''; }
        });
        sec.querySelectorAll('.file-name-display').forEach(function (el) {
            el.textContent = ''; el.style.display = 'none';
        });
        if (currentStep === 9) {
            var sv = document.getElementById('employee_signature');
            var sd = document.getElementById('signature-display');
            if (sv) sv.value = '';
            if (sd) {
                sd.classList.remove('has-value');
                sd.innerHTML = '<i class="fa fa-pencil-square-o"></i>&nbsp; Click here to draw signature';
            }
        }
    });

    /* ═══════════════════════════════════
       DATE — DD/MM/YYYY auto-slash
    ═══════════════════════════════════ */
    function getTodayDDMMYYYY() {
        var d  = new Date();
        var dd = String(d.getDate()).padStart(2, '0');
        var mm = String(d.getMonth() + 1).padStart(2, '0');
        return dd + '/' + mm + '/' + d.getFullYear();
    }

    window.autoDateSlash = function (input) {
        var v = input.value.replace(/\D/g, '').substring(0, 8);
        var out = '';
        if (v.length > 4)      out = v.substring(0,2) + '/' + v.substring(2,4) + '/' + v.substring(4);
        else if (v.length > 2) out = v.substring(0,2) + '/' + v.substring(2);
        else                   out = v;
        input.value = out;
    };

    /* pre-fill declaration date with today */
    var declDate = document.getElementById('declaration_date');
    if (declDate && !declDate.value) declDate.value = getTodayDDMMYYYY();

    /* ═══════════════════════════════════
       PHOTO LABEL
    ═══════════════════════════════════ */
    window.updatePhotoLabel = function (input) {
        var lbl = document.getElementById('photo-label-text');
        if (!lbl) return;
        lbl.textContent = (input.files && input.files.length > 0)
            ? input.files.length + ' file(s) selected'
            : 'Upload Passport Photo';
    };

    /* ═══════════════════════════════════
       FILE NAME DISPLAY
    ═══════════════════════════════════ */
    window.showFileName = function (input, labelId) {
        var lbl = document.getElementById(labelId);
        if (!lbl) return;
        if (input.files && input.files.length > 0) {
            lbl.textContent = '✔ ' + input.files[0].name;
            lbl.style.display = 'block';
        } else {
            lbl.textContent = '';
            lbl.style.display = 'none';
        }
    };

    /* ═══════════════════════════════════
       DYNAMIC ROWS — EDUCATION
    ═══════════════════════════════════ */
    function refreshEduIndexes() {
        document.querySelectorAll('#education-tbody tr').forEach(function (row, i) {
            row.querySelectorAll('input[data-group="education"]').forEach(function (inp) {
                inp.name = 'education[' + i + '][' + inp.dataset.field + ']';
            });
        });
    }

    document.getElementById('btn-add-education').addEventListener('click', function () {
        var i  = document.querySelectorAll('#education-tbody tr').length;
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td><input type="text" name="education['+i+'][qualification]" data-group="education" data-field="qualification" placeholder="Qualification" /></td>' +
            '<td><input type="text" name="education['+i+'][specialization]" data-group="education" data-field="specialization" placeholder="Specialization" /></td>' +
            '<td><input type="text" name="education['+i+'][institute]" data-group="education" data-field="institute" placeholder="University / Institute" /></td>' +
            '<td><input type="number" name="education['+i+'][year]" data-group="education" data-field="year" placeholder="Year" min="1970" max="<?php echo date('Y'); ?>" /></td>' +
            '<td><input type="text" name="education['+i+'][percentage]" data-group="education" data-field="percentage" placeholder="e.g. 8.0" /></td>' +
            '<td style="text-align:center;"><button type="button" class="btn-remove-row remove-edu-row">Remove</button></td>';
        document.getElementById('education-tbody').appendChild(tr);
    });

    /* ═══════════════════════════════════
       DYNAMIC ROWS — EMPLOYMENT
    ═══════════════════════════════════ */
    function refreshEmpIndexes() {
        document.querySelectorAll('#employment-tbody tr').forEach(function (row, i) {
            row.querySelectorAll('input[data-group="employment"]').forEach(function (inp) {
                inp.name = 'employment[' + i + '][' + inp.dataset.field + ']';
            });
        });
    }

    document.getElementById('btn-add-employment').addEventListener('click', function () {
        var i  = document.querySelectorAll('#employment-tbody tr').length;
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td><input type="text" name="employment['+i+'][company]" data-group="employment" data-field="company" placeholder="Company name" /></td>' +
            '<td><input type="text" name="employment['+i+'][designation]" data-group="employment" data-field="designation" placeholder="Designation" /></td>' +
            '<td><input type="text" name="employment['+i+'][start_date]" data-group="employment" data-field="start_date" placeholder="DD/MM/YYYY" maxlength="10" oninput="autoDateSlash(this)" /></td>' +
            '<td><input type="text" name="employment['+i+'][end_date]" data-group="employment" data-field="end_date" placeholder="DD/MM/YYYY" maxlength="10" oninput="autoDateSlash(this)" /></td>' +
            '<td><input type="number" name="employment['+i+'][ctc]" data-group="employment" data-field="ctc" placeholder="0.00" step="0.01" min="0" /></td>' +
            '<td><input type="text" name="employment['+i+'][notice]" data-group="employment" data-field="notice" placeholder="e.g. 30 days" /></td>' +
            '<td style="text-align:center;"><button type="button" class="btn-remove-row remove-emp-row">Remove</button></td>';
        document.getElementById('employment-tbody').appendChild(tr);
    });

    /* Delegation for remove buttons */
    document.body.addEventListener('click', function (e) {
        if (e.target.matches('.remove-edu-row')) {
            var row = e.target.closest('tr');
            if (row) { row.parentNode.removeChild(row); refreshEduIndexes(); }
        }
        if (e.target.matches('.remove-emp-row')) {
            var row = e.target.closest('tr');
            if (row) { row.parentNode.removeChild(row); refreshEmpIndexes(); }
        }
    });

    /* ═══════════════════════════════════
       SIGNATURE PAD
    ═══════════════════════════════════ */
    var sigInput   = document.getElementById('employee_signature');
    var sigDisplay = document.getElementById('signature-display');
    var sigModal   = document.getElementById('signature-modal');
    var sigCanvas  = document.getElementById('signature-canvas');
    var sigClose   = document.getElementById('signature-close');
    var sigClear   = document.getElementById('signature-clear');
    var sigSave    = document.getElementById('signature-save');
    var ctx        = sigCanvas.getContext('2d');
    var drawing    = false;
    var lx = 0, ly = 0;

    function setupCanvas() {
        var ratio = window.devicePixelRatio || 1;
        var rect  = sigCanvas.getBoundingClientRect();
        sigCanvas.width  = rect.width  * ratio;
        sigCanvas.height = rect.height * ratio;
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.scale(ratio, ratio);
        ctx.lineJoin = 'round'; ctx.lineCap = 'round';
        ctx.lineWidth = 2.5; ctx.strokeStyle = '#111827';
    }

    function openModal() {
        sigModal.classList.add('open');
        setTimeout(function () {
            setupCanvas();
            if (sigInput.value) {
                var img = new Image();
                img.onload = function () {
                    var r = window.devicePixelRatio || 1;
                    ctx.drawImage(img, 0, 0, sigCanvas.width / r, sigCanvas.height / r);
                };
                img.src = sigInput.value;
            }
        }, 50);
    }

    function closeModal() { sigModal.classList.remove('open'); }

    function getXY(e) {
        var rect = sigCanvas.getBoundingClientRect();
        var src  = e.touches ? e.touches[0] : e;
        return { x: src.clientX - rect.left, y: src.clientY - rect.top };
    }

    sigCanvas.addEventListener('mousedown',  function(e){ drawing=true; var p=getXY(e); lx=p.x; ly=p.y; });
    sigCanvas.addEventListener('mousemove',  function(e){ if(!drawing) return; var p=getXY(e); ctx.beginPath(); ctx.moveTo(lx,ly); ctx.lineTo(p.x,p.y); ctx.stroke(); lx=p.x; ly=p.y; });
    sigCanvas.addEventListener('mouseup',    function(){ drawing=false; });
    sigCanvas.addEventListener('mouseleave', function(){ drawing=false; });
    sigCanvas.addEventListener('touchstart', function(e){ e.preventDefault(); drawing=true; var p=getXY(e); lx=p.x; ly=p.y; }, {passive:false});
    sigCanvas.addEventListener('touchmove',  function(e){ e.preventDefault(); if(!drawing) return; var p=getXY(e); ctx.beginPath(); ctx.moveTo(lx,ly); ctx.lineTo(p.x,p.y); ctx.stroke(); lx=p.x; ly=p.y; }, {passive:false});
    sigCanvas.addEventListener('touchend',   function(){ drawing=false; });

    sigDisplay.addEventListener('click', openModal);
    sigClose.addEventListener('click',   closeModal);
    sigModal.addEventListener('click',   function(e){ if(e.target===sigModal) closeModal(); });
    sigClear.addEventListener('click',   function(){ ctx.clearRect(0, 0, sigCanvas.width, sigCanvas.height); });
    sigSave.addEventListener('click',    function () {
        var blank = document.createElement('canvas');
        blank.width  = sigCanvas.width;
        blank.height = sigCanvas.height;
        if (sigCanvas.toDataURL() === blank.toDataURL()) {
            alert('Please draw your signature before saving.');
            return;
        }
        var dataURL = sigCanvas.toDataURL('image/png');
        sigInput.value = dataURL;
        sigDisplay.classList.add('has-value');
        sigDisplay.innerHTML = '<img src="' + dataURL + '" style="max-height:48px;max-width:100%;" alt="Signature" />';
        closeModal();
    });

    /* Init */
    updateStepDisplay();
});
</script>

<?php init_tail(); ?>