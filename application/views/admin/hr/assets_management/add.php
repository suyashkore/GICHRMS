<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body" style="position: relative;">

                <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">Asset Management Form</h4>
                        <p class="page-subtitle">Complete each section to submit the employee asset request.</p>
                    </div>
                </div>

                <style>
                    /* ── General form section container with border and rounded corners ── */
                    .form-section {
                        border: 1px solid #e5e7eb;
                        border-radius: 12px;
                        padding: 24px;
                        margin-bottom: 20px;
                        background: #fafbfc;
                    }

                    /* ── Section heading style with bold font ── */
                    .form-section h5 {
                        font-size: 1rem;
                        font-weight: 700;
                        color: #1f2937;
                        margin: 0 0 4px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }

                    /* ── Small muted description below section title with blue bottom border ── */
                    .section-desc {
                        font-size: 0.82rem;
                        color: #6b7280;
                        margin: 0 0 18px;
                        padding-bottom: 14px;
                        border-bottom: 2px solid #185FA5;
                    }

                    /* ── Hide all step sections by default, only active is shown ── */
                    .step-section { display: none; }
                    .step-section.active { display: block; }

                    /* ── Step progress bar wrapper, hidden on step 1 (home) ── */
                    .step-progress { margin-bottom: 24px; display: none; }
                    .step-progress.visible { display: block; }

                    /* ── Progress bar track (gray background) ── */
                    .step-bar {
                        width: 100%; height: 8px;
                        background: #e5e7eb; border-radius: 999px;
                        overflow: hidden; margin-bottom: 12px;
                    }

                    /* ── Blue fill that animates width as user advances steps ── */
                    .step-fill {
                        width: 0%; height: 100%;
                        background: linear-gradient(90deg,#185FA5,#0c447c);
                        transition: width .3s ease;
                    }

                    /* ── Grid of step label items below the progress bar ── */
                    .step-labels {
                        display: grid;
                        grid-template-columns: repeat(5,1fr);
                        gap: 6px; font-size: .7rem; text-align: center;
                    }

                    /* ── Individual step label with number bubble and text ── */
                    .step-item {
                        color: #6b7280; cursor: pointer;
                        padding: 5px 4px; border-radius: 8px;
                        transition: background .2s,color .2s;
                        line-height: 1.3;
                    }

                    /* ── Circular number badge inside each step label ── */
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

                    /* ── Active and completed steps get blue number badge ── */
                    .step-item.active .step-num,
                    .step-item.completed .step-num { background: #185FA5; color: #fff; }
                    .step-item.active, .step-item.completed { color: #0c447c; font-weight: 700; }
                    .step-item:hover { background: #f3f4f6; }

                    /* ── Two-column responsive grid layout ── */
                    .fg2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }

                    /* ── Three-column responsive grid layout ── */
                    .fg3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 14px; }

                    /* ── Four-column responsive grid layout ── */
                    .fg4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 14px; }

                    /* ── Collapse 4 and 3 column grids on medium screens ── */
                    @media(max-width:900px){ .fg4,.fg3{grid-template-columns:1fr 1fr;} }

                    /* ── Single column for all grids and step labels on small screens ── */
                    @media(max-width:580px){
                        .fg2,.fg3,.fg4{grid-template-columns:1fr;}
                        .step-labels{grid-template-columns:repeat(3,1fr);}
                    }

                    /* ── Vertical form group wrapper with label and input stacked ── */
                    .form-group { display: flex; flex-direction: column; gap: 4px; margin-bottom: 14px; }
                    .form-group label { font-size: .8rem; font-weight: 600; color: #374151; }

                    /* ── Append red asterisk to required field labels ── */
                    .form-group.required label::after { content:' *'; color:#dc2626; }

                    /* ── Shared input, select, and textarea base styles ── */
                    .form-control {
                        border: 1px solid #d1d5db; border-radius: 8px;
                        padding: 9px 12px; font-size: .85rem; color: #1f2937;
                        width: 100%; background: #fff; outline: none;
                        box-sizing: border-box;
                        transition: border-color .15s, box-shadow .15s;
                        -webkit-appearance: none;
                        appearance: none;
                    }

                    /* ── Blue border glow on input focus ── */
                    .form-control:focus { border-color:#378ADD; box-shadow:0 0 0 3px rgba(55,138,221,.12); }
                    textarea.form-control { min-height: 88px; resize: vertical; }

                    /* ── Custom chevron arrow for select dropdowns ── */
                    select.form-control {
                        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
                        background-repeat: no-repeat;
                        background-position: right 12px center;
                        padding-right: 36px;
                        cursor: pointer;
                    }
                    select.form-control option { color: #1f2937; background: #fff; padding: 6px; }

                    /* ── Date input with pointer cursor ── */
                    input[type="date"].form-control { cursor: pointer; color: #1f2937; }
                    input[type="date"].form-control::-webkit-calendar-picker-indicator { opacity: 0.6; cursor: pointer; }

                    /* ── File input with styled upload button via webkit pseudo-element ── */
                    input[type="file"].form-control {
                        padding: 6px 12px; cursor: pointer; color: #6b7280; font-size: .82rem;
                    }
                    input[type="file"].form-control::-webkit-file-upload-button {
                        background: #185FA5; color: #fff; border: none; border-radius: 6px;
                        padding: 5px 14px; font-size: .8rem; font-weight: 600; cursor: pointer;
                        margin-right: 10px; font-family: inherit; transition: background .15s;
                    }
                    input[type="file"].form-control::-webkit-file-upload-button:hover { background: #0c447c; }
                    input[type="file"].form-control::file-selector-button {
                        background: #185FA5; color: #fff; border: none; border-radius: 6px;
                        padding: 5px 14px; font-size: .8rem; font-weight: 600; cursor: pointer;
                        margin-right: 10px; font-family: inherit; transition: background .15s;
                    }

                    /* ── Horizontal group of radio/checkbox items with labels ── */
                    .checkbox-group { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 6px; }
                    .checkbox-item { display: flex; align-items: center; gap: 6px; }
                    .checkbox-item input { cursor: pointer; }
                    .checkbox-item label { margin: 0; font-weight: 500; font-size: .83rem; cursor: pointer; }

                    /* ── Blue left-bordered info note box inside form sections ── */
                    .section-note {
                        background: #f0f9ff; border-left: 4px solid #185FA5;
                        padding: 10px 14px; border-radius: 4px;
                        font-size: .82rem; color: #0c5394; margin-bottom: 16px;
                    }

                    /* ═══════════════════════════════════════════════════
                       STAT CARDS — readonly clickable summary cards
                    ═══════════════════════════════════════════════════ */

                    /* ── Auto-fit responsive grid for stat summary cards ── */
                    .stats-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit,minmax(155px,1fr));
                        gap: 14px; margin-top: 4px;
                    }

                    /* ── Individual stat card with hover lift effect and pointer cursor ── */
                    .stat-card {
                        background: #fff; border: 1px solid #e5e7eb;
                        border-radius: 12px; padding: 16px 14px;
                        display: flex; flex-direction: column; gap: 10px;
                        transition: box-shadow .2s, border-color .2s, transform .15s;
                        cursor: pointer; user-select: none;
                    }

                    /* ── Blue border and shadow on stat card hover to indicate clickability ── */
                    .stat-card:hover {
                        box-shadow: 0 4px 16px rgba(24,95,165,.15);
                        border-color: #185FA5;
                        transform: translateY(-2px);
                    }

                    /* ── Active press effect on stat card click ── */
                    .stat-card:active { transform: translateY(0); }

                    /* ── Colored square icon container inside each stat card ── */
                    .stat-icon {
                        width: 36px; height: 36px; border-radius: 10px;
                        background: #eaf4ff; color: #185FA5;
                        display: flex; align-items: center; justify-content: center;
                        font-size: 1rem;
                    }

                    /* ── Muted small label text above the number value ── */
                    .stat-label { font-size: .74rem; font-weight: 600; color: #6b7280; line-height: 1.3; }

                    /* ── Large bold number display inside each stat card ── */
                    .stat-value {
                        font-size: 1.5rem; font-weight: 700; color: #111827;
                        text-align: center; line-height: 1;
                    }

                    /* ── Small click hint text at the bottom of each stat card ── */
                    .stat-click-hint {
                        font-size: .65rem; color: #9ca3af;
                        display: flex; align-items: center; gap: 3px;
                        justify-content: center;
                    }

                    /* ═══════════════════════════════════════════════════
                       POPUP OVERLAY & MODAL — full screen asset detail popup
                    ═══════════════════════════════════════════════════ */

                    /* ── Semi-transparent dark overlay covering the full viewport ── */
                    .stat-overlay {
                        display: none; position: fixed; inset: 0;
                        background: rgba(0,0,0,.48);
                        z-index: 9000; align-items: center;
                        justify-content: center; padding: 20px;
                        animation: fadeOverlay .2s ease;
                    }

                    /* ── Show overlay using flex when open class is added ── */
                    .stat-overlay.open { display: flex; }

                    /* ── Fade in animation for the overlay background ── */
                    @keyframes fadeOverlay {
                        from { opacity: 0; }
                        to   { opacity: 1; }
                    }

                    /* ── White popup modal container with max width and scrollable height ── */
                    .stat-popup {
                        background: #fff; border-radius: 16px;
                        border: 1px solid #e5e7eb;
                        width: min(100%, 820px); max-height: 88vh;
                        display: flex; flex-direction: column; overflow: hidden;
                        animation: slideUp .25s ease;
                        box-shadow: 0 24px 60px rgba(15,23,42,.18);
                    }

                    /* ── Slide up animation for popup appearance ── */
                    @keyframes slideUp {
                        from { opacity: 0; transform: translateY(20px); }
                        to   { opacity: 1; transform: translateY(0); }
                    }

                    /* ── Popup top header with title, badge, and close button ── */
                    .stat-popup-header {
                        padding: 18px 22px;
                        border-bottom: 1px solid #e5e7eb;
                        display: flex; align-items: center;
                        justify-content: space-between; gap: 12px; flex-shrink: 0;
                    }

                    /* ── Left side of popup header with icon and title ── */
                    .stat-popup-title {
                        display: flex; align-items: center; gap: 10px;
                        font-size: 1rem; font-weight: 700; color: #1f2937;
                    }

                    /* ── Colored badge showing count of records in popup header ── */
                    .stat-popup-badge {
                        padding: 3px 12px; border-radius: 20px;
                        font-size: .72rem; font-weight: 700;
                        display: inline-flex; align-items: center; gap: 4px;
                    }

                    /* ── Small close button in top right of popup header ── */
                    .stat-popup-close {
                        border: none; background: #f3f4f6; color: #6b7280;
                        width: 32px; height: 32px; border-radius: 8px;
                        cursor: pointer; display: flex;
                        align-items: center; justify-content: center;
                        font-size: .9rem; transition: background .15s; flex-shrink: 0;
                    }

                    /* ── Red background on close button hover ── */
                    .stat-popup-close:hover { background: #fee2e2; color: #991b1b; }

                    /* ── Scrollable body area of the popup below the header ── */
                    .stat-popup-body {
                        overflow-y: auto; padding: 18px 22px; flex: 1;
                    }

                    /* ── Search input bar inside the popup to filter assets ── */
                    .popup-search-bar {
                        display: flex; align-items: center; gap: 10px;
                        margin-bottom: 16px; padding: 9px 14px;
                        border: 1px solid #d1d5db; border-radius: 10px;
                        background: #f9fafb;
                    }

                    /* ── Search icon color inside the search bar ── */
                    .popup-search-bar i { color: #9ca3af; font-size: .9rem; }

                    /* ── Borderless transparent input inside the styled search bar ── */
                    .popup-search-bar input {
                        border: none; background: transparent; outline: none;
                        font-size: .84rem; color: #1f2937; flex: 1; font-family: inherit;
                    }

                    /* ── Full-width asset detail table inside popup ── */
                    .asset-popup-table { width: 100%; border-collapse: collapse; font-size: .82rem; }

                    /* ── Table header row with bottom border separator ── */
                    .asset-popup-table thead tr {
                        border-bottom: 2px solid #e5e7eb;
                    }

                    /* ── Table column header with muted uppercase label style ── */
                    .asset-popup-table th {
                        padding: 9px 10px; text-align: left;
                        font-size: .7rem; font-weight: 700;
                        color: #6b7280; text-transform: uppercase;
                        letter-spacing: .04em; white-space: nowrap;
                    }

                    /* ── Table data cell with thin bottom border and aligned content ── */
                    .asset-popup-table td {
                        padding: 11px 10px; color: #1f2937;
                        border-bottom: 1px solid #f0f0f0; vertical-align: middle;
                    }

                    /* ── Remove bottom border from last table row ── */
                    .asset-popup-table tr:last-child td { border-bottom: none; }

                    /* ── Light gray row hover background for readability ── */
                    .asset-popup-table tbody tr:hover td { background: #f9fafb; }

                    /* ── Small colored pill badge for status and category display ── */
                    .ap-pill {
                        display: inline-block; padding: 3px 10px;
                        border-radius: 20px; font-size: .7rem; font-weight: 700;
                    }

                    /* ── Monospace font for IDs and serial numbers in table ── */
                    .ap-mono {
                        font-family: 'Courier New', monospace;
                        font-size: .78rem; color: #4b5563;
                    }

                    /* ── Centered empty state message when no records found ── */
                    .popup-empty {
                        text-align: center; padding: 40px 0;
                        color: #9ca3af; font-size: .88rem;
                    }

                    /* ── Large icon above empty state message ── */
                    .popup-empty i { font-size: 2.5rem; display: block; margin-bottom: 10px; opacity: .35; }

                    /* ── Small muted subtitle under the empty state icon ── */
                    .popup-empty span { display: block; font-size: .76rem; color: #d1d5db; margin-top: 4px; }

                    /* ── Footer bar at bottom of popup with record count info ── */
                    .stat-popup-footer {
                        padding: 12px 22px; border-top: 1px solid #e5e7eb;
                        font-size: .75rem; color: #9ca3af;
                        display: flex; align-items: center;
                        justify-content: space-between; flex-shrink: 0;
                        background: #fafbfc;
                    }

                    /* ═══════════════════════════════════════════════════
                       HOME CTA — call-to-action area below stat cards
                    ═══════════════════════════════════════════════════ */

                    /* ── CTA row with text left and buttons right ── */
                    .home-cta-wrap {
                        margin-top: 24px; padding-top: 20px;
                        border-top: 1px dashed #d1d5db;
                        display: flex; align-items: center;
                        justify-content: space-between; flex-wrap: wrap; gap: 12px;
                    }
                    .home-cta-text { font-size: .84rem; color: #6b7280; }
                    .home-cta-text strong { display: block; color: #1f2937; font-size: .92rem; margin-bottom: 2px; }
                    .home-cta-buttons { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

                    /* ── Blue primary button to add asset and proceed to next step ── */
                    .btn-add-asset-home {
                        padding: 11px 24px; border-radius: 8px; background: #185FA5;
                        color: #fff; border: none; font-size: .87rem; font-weight: 600;
                        cursor: pointer; font-family: inherit;
                        display: inline-flex; align-items: center; gap: 8px;
                        transition: background .15s; white-space: nowrap;
                    }
                    .btn-add-asset-home:hover { background: #0c447c; }

                    /* ── Orange outlined button to trigger return asset panel ── */
                    .btn-return-asset-home {
                        padding: 11px 24px; border-radius: 8px;
                        background: #fff; border: 1.5px solid #ea580c; color: #ea580c;
                        font-size: .87rem; font-weight: 600;
                        cursor: pointer; font-family: inherit;
                        display: inline-flex; align-items: center; gap: 8px;
                        transition: background .15s, color .15s; white-space: nowrap;
                    }
                    .btn-return-asset-home:hover { background: #fff7ed; }

                    /* ═══════════════════════════════════════════════════
                       RETURN ASSET PANEL — slide-down return form panel
                    ═══════════════════════════════════════════════════ */

                    /* ── Orange-themed panel hidden by default, shown on return click ── */
                    #return-asset-panel {
                        display: none;
                        border: 1.5px solid #fed7aa;
                        border-radius: 12px;
                        background: #fff7ed;
                        padding: 24px;
                        margin-top: 20px;
                        animation: slideDown .25s ease;
                    }

                    /* ── Display the return panel as block when open class applied ── */
                    #return-asset-panel.open { display: block; }

                    /* ── Animate return panel sliding down when opened ── */
                    @keyframes slideDown {
                        from { opacity: 0; transform: translateY(-10px); }
                        to   { opacity: 1; transform: translateY(0); }
                    }

                    /* ── Return panel section heading style ── */
                    #return-asset-panel h5 {
                        font-size: 1rem; font-weight: 700; color: #9a3412;
                        margin: 0 0 4px; display: flex; align-items: center; gap: 8px;
                    }

                    /* ── Return panel description with orange bottom border ── */
                    #return-asset-panel .section-desc {
                        border-bottom-color: #ea580c; color: #7c3012; margin-bottom: 18px;
                    }

                    /* ── Header row inside return panel with title and close button ── */
                    .return-panel-header {
                        display: flex; align-items: center;
                        justify-content: space-between; margin-bottom: 16px;
                        padding-bottom: 12px; border-bottom: 2px solid #ea580c;
                    }
                    .return-panel-header h5 { margin: 0; padding: 0; border: none; }

                    /* ── Small red close button inside return panel header ── */
                    .btn-close-return {
                        border: none; background: #fee2e2; color: #991b1b;
                        width: 30px; height: 30px; border-radius: 8px;
                        cursor: pointer; display: inline-flex;
                        align-items: center; justify-content: center;
                        font-size: .85rem; transition: background .15s; flex-shrink: 0;
                    }
                    .btn-close-return:hover { background: #fecaca; }

                    /* ── Inputs inside return panel have white background ── */
                    #return-asset-panel .form-control { background: #fff; }

                    /* ── Orange submit button inside the return panel form ── */
                    .btn-submit-return {
                        padding: 10px 24px; border-radius: 8px; font-size: .85rem;
                        background: #ea580c; border: none; color: #fff;
                        font-weight: 600; cursor: pointer; font-family: inherit;
                        display: inline-flex; align-items: center; gap: 6px;
                        transition: background .15s;
                    }
                    .btn-submit-return:hover { background: #c2410c; }

                    /* ═══════════════════════════════════════════════════
                       DYNAMIC CARDS — repeatable asset, ticket, software cards
                    ═══════════════════════════════════════════════════ */

                    /* ── Vertical list wrapper for dynamically added cards ── */
                    .dyn-cards-wrap { display: flex; flex-direction: column; gap: 16px; margin-top: 12px; }

                    /* ── White card with subtle border for each dynamic entry ── */
                    .dyn-card {
                        background: #fff; border: 1px solid #e5e7eb;
                        border-radius: 12px; padding: 18px; transition: box-shadow .2s;
                    }
                    .dyn-card:hover { box-shadow: 0 4px 14px rgba(24,95,165,.07); }

                    /* ── Top row of dynamic card with title on left and remove button right ── */
                    .dyn-card-header {
                        display: flex; justify-content: space-between;
                        align-items: center; margin-bottom: 16px;
                        padding-bottom: 12px; border-bottom: 1px solid #f0f0f0;
                    }

                    /* ── Card title text with icon and badge ── */
                    .dyn-card-title {
                        font-size: .9rem; font-weight: 700; color: #1f2937;
                        display: flex; align-items: center; gap: 8px;
                    }

                    /* ── Small blue label badge inside card titles ── */
                    .dyn-badge {
                        background: #eaf4ff; color: #185FA5;
                        font-size: .67rem; font-weight: 700;
                        padding: 2px 8px; border-radius: 20px;
                    }

                    /* ── Red trash button to remove a dynamic card ── */
                    .btn-remove-card {
                        border: none; background: #fee2e2; color: #991b1b;
                        width: 32px; height: 32px; border-radius: 8px;
                        cursor: pointer; display: inline-flex;
                        align-items: center; justify-content: center;
                        font-size: .85rem; transition: background .15s; flex-shrink: 0;
                    }
                    .btn-remove-card:hover { background: #fecaca; }

                    /* ═══════════════════════════════════════════════════
                       PHOTO UPLOAD — drag and drop image uploader
                    ═══════════════════════════════════════════════════ */

                    /* ── Hide the actual file input, replaced by custom UI ── */
                    #photo-file-input { display: none !important; }

                    /* ── Dashed dropzone container for photo upload area ── */
                    .photo-dropzone {
                        border: 2px dashed #d1d5db; border-radius: 10px;
                        background: #f9fafb; padding: 20px 16px 16px;
                        transition: border-color .2s, background .2s; cursor: default;
                    }

                    /* ── Blue border and tint when file is dragged over dropzone ── */
                    .photo-dropzone.dragover { border-color: #185FA5; background: #eaf4ff; }

                    /* ── Top row inside dropzone with hint text and add button ── */
                    .photo-dz-info {
                        display: flex; align-items: center;
                        justify-content: space-between; margin-bottom: 14px;
                        flex-wrap: wrap; gap: 8px;
                    }

                    /* ── Small muted format hint text in dropzone top row ── */
                    .photo-dz-hint { font-size: .75rem; color: #9ca3af; display: flex; align-items: center; gap: 5px; }
                    .photo-dz-hint i { color: #185FA5; }

                    /* ── Blue outlined add photos button inside dropzone ── */
                    .btn-photo-add {
                        display: inline-flex; align-items: center; gap: 6px;
                        padding: 7px 16px; border-radius: 8px;
                        border: 1.5px solid #185FA5; background: #fff; color: #185FA5;
                        font-size: .8rem; font-weight: 600; cursor: pointer;
                        font-family: inherit; transition: background .15s, color .15s; white-space: nowrap;
                    }
                    .btn-photo-add:hover { background: #eaf4ff; }

                    /* ── Centered placeholder area when no photos are added yet ── */
                    .photo-empty-state {
                        display: flex; flex-direction: column; align-items: center;
                        justify-content: center; gap: 6px; padding: 24px 0 18px; cursor: pointer;
                    }
                    .photo-empty-state i { font-size: 2rem; color: #d1d5db; }
                    .photo-empty-state .pes-title { font-size: .85rem; font-weight: 600; color: #6b7280; }
                    .photo-empty-state .pes-sub { font-size: .73rem; color: #9ca3af; }
                    .photo-empty-state:hover i, .photo-empty-state:hover .pes-title { color: #185FA5; }

                    /* ── Horizontal flex row of photo thumbnail previews ── */
                    .photo-thumb-grid { display: flex; flex-wrap: wrap; gap: 10px; }

                    /* ── Square thumbnail container with overflow hidden for image crop ── */
                    .photo-thumb {
                        position: relative; width: 82px; height: 82px;
                        border-radius: 8px; overflow: hidden;
                        border: 1.5px solid #e5e7eb; background: #f3f4f6; flex-shrink: 0;
                    }

                    /* ── Thumbnail image fills the container with cover crop ── */
                    .photo-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

                    /* ── Small red X button on top right of each thumbnail ── */
                    .photo-thumb-remove {
                        position: absolute; top: 3px; right: 3px;
                        width: 20px; height: 20px; background: rgba(220,38,38,.88); color: #fff;
                        border: none; border-radius: 50%; font-size: .62rem;
                        display: flex; align-items: center; justify-content: center;
                        cursor: pointer; transition: background .15s; padding: 0; line-height: 1;
                    }
                    .photo-thumb-remove:hover { background: rgba(153,27,27,1); }

                    /* ── Filename label overlaid at the bottom of each thumbnail ── */
                    .photo-thumb-name {
                        position: absolute; bottom: 0; left: 0; right: 0;
                        background: rgba(0,0,0,.48); color: #fff; font-size: .55rem;
                        padding: 3px 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                    }

                    /* ── Blue pill badge showing total count of selected photos ── */
                    .photo-count-badge {
                        display: inline-flex; align-items: center; gap: 4px;
                        background: #eaf4ff; color: #185FA5;
                        font-size: .72rem; font-weight: 700;
                        padding: 3px 10px; border-radius: 20px;
                    }

                    /* ── Green card with checkbox for employee acceptance confirmation ── */
                    .acceptance-card {
                        background: #f0fdf4; border: 1px solid #bbf7d0;
                        border-radius: 10px; padding: 14px 16px;
                        display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px;
                    }
                    .acceptance-card input[type="checkbox"] {
                        margin-top: 2px; width: 16px; height: 16px; cursor: pointer; flex-shrink: 0;
                    }
                    .acceptance-card span { font-size: .84rem; color: #166534; font-weight: 500; }

                    /* ── Relative wrapper for license key input with toggle eye icon ── */
                    .lk-wrap { position: relative; }
                    .lk-wrap .form-control { padding-right: 40px; }

                    /* ── Eye toggle button positioned inside the license key input ── */
                    .toggle-key-btn {
                        position: absolute; right: 10px; top: 50%;
                        transform: translateY(-50%);
                        border: none; background: transparent;
                        color: #6b7280; cursor: pointer; font-size: .85rem; padding: 0;
                    }
                    .toggle-key-btn:hover { color: #185FA5; }

                    /* ── Responsive grid of policy acknowledgment cards ── */
                    .policy-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
                        gap: 14px; margin-top: 4px;
                    }

                    /* ── Clickable policy card with checkbox and icon ── */
                    .policy-card {
                        background: #fff; border: 2px solid #e5e7eb;
                        border-radius: 12px; padding: 18px 16px;
                        cursor: pointer; display: flex; gap: 12px; align-items: flex-start;
                        transition: border-color .2s, box-shadow .2s;
                    }
                    .policy-card:hover { border-color: #185FA5; box-shadow: 0 2px 10px rgba(24,95,165,.10); }

                    /* ── Blue border and light blue background when policy checkbox is checked ── */
                    .policy-card:has(input:checked) { border-color: #185FA5; background: #f0f7ff; }
                    .policy-card input[type="checkbox"] {
                        margin-top: 3px; width: 16px; height: 16px; flex-shrink: 0; cursor: pointer;
                    }

                    /* ── Colored icon block inside each policy card ── */
                    .policy-icon {
                        width: 36px; height: 36px; border-radius: 10px;
                        background: #eaf4ff; color: #185FA5;
                        display: flex; align-items: center; justify-content: center;
                        font-size: .95rem; flex-shrink: 0;
                    }
                    .policy-text strong { display: block; font-size: .84rem; color: #1f2937; margin-bottom: 3px; }
                    .policy-text p { font-size: .75rem; color: #6b7280; margin: 0; line-height: 1.4; }

                    /* ── Vertical stack for signature fields ── */
                    .signature-section { display: grid; grid-template-columns: 1fr; gap: 14px; margin-top: 16px; }
                    .signature-field { display: flex; flex-direction: column; gap: 8px; }

                    /* ── Clickable signature placeholder that opens the signature modal ── */
                    .signature-display {
                        min-height: 56px; border: 1px solid #d1d5db; border-radius: 10px;
                        background: #fff; display: flex; align-items: center;
                        justify-content: center; color: #6b7280; cursor: pointer; padding: 0 14px;
                        transition: border-color .15s, background .15s, color .15s;
                    }
                    .signature-display:hover { border-color: #185FA5; background: #f3f7ff; color: #185FA5; }

                    /* ── Blue border when signature has been drawn and saved ── */
                    .signature-display.has-value { border-color: #185FA5; padding: 8px; color: #111827; }

                    /* ── Full screen overlay for the signature drawing modal ── */
                    .signature-modal {
                        display: none; position: fixed; inset: 0;
                        background: rgba(0,0,0,.45); align-items: center;
                        justify-content: center; padding: 20px; z-index: 9999;
                    }
                    .signature-modal.open { display: flex; }

                    /* ── White modal box for the signature canvas and controls ── */
                    .signature-modal-content {
                        width: min(100%, 560px); background: #fff;
                        border-radius: 14px; box-shadow: 0 18px 50px rgba(15,23,42,.15); overflow: hidden;
                    }

                    /* ── Header of signature modal with title and close button ── */
                    .signature-modal-header {
                        padding: 18px 20px; display: flex; align-items: center;
                        justify-content: space-between; border-bottom: 1px solid #e5e7eb;
                    }
                    .signature-modal-header h6 { margin: 0; font-size: 1rem; font-weight: 700; color: #111827; }
                    .signature-modal-header button {
                        border: none; background: transparent; color: #6b7280;
                        font-size: 1.4rem; line-height: 1; cursor: pointer;
                    }
                    .signature-modal-header button:hover { color: #dc2626; }

                    /* ── White padded area containing the signature canvas ── */
                    .signature-canvas-wrapper { padding: 16px; background: #fff; }

                    /* ── The drawable canvas element for touch and mouse signatures ── */
                    .signature-canvas {
                        width: 100%; min-height: 220px; border: 1px solid #d1d5db;
                        border-radius: 10px; touch-action: none; background: #fff;
                    }
                    .signature-hint { text-align: center; font-size: .75rem; color: #9ca3af; margin-top: 8px; }

                    /* ── Bottom row of signature modal with clear and save buttons ── */
                    .signature-modal-actions {
                        display: flex; justify-content: space-between; gap: 10px;
                        padding: 14px 16px 18px; background: #f9fafb;
                    }

                    /* ═══════════════════════════════════════════════════
                       FORM ACTION BUTTONS — bottom navigation buttons
                    ═══════════════════════════════════════════════════ */

                    /* ── Right-aligned row of form navigation and action buttons ── */
                    .rform-actions {
                        display: flex; gap: 10px; justify-content: flex-end;
                        padding-top: 20px; border-top: 1px solid #e5e7eb; margin-top: 20px;
                    }

                    /* ── Gray outlined cancel and previous navigation button ── */
                    .btn-cancel {
                        padding: 10px 22px; border-radius: 8px; font-size: .85rem;
                        background: #fff; border: 1px solid #d1d5db; color: #4b5563;
                        cursor: pointer; font-family: inherit; font-weight: 500;
                        display: inline-flex; align-items: center; gap: 6px;
                        transition: background .15s; text-decoration: none;
                    }
                    .btn-cancel:hover { background: #f3f4f6; }

                    /* ── Blue primary submit and next step button ── */
                    .btn-submit {
                        padding: 10px 24px; border-radius: 8px; font-size: .85rem;
                        background: #185FA5; border: none; color: #fff;
                        font-weight: 600; cursor: pointer; font-family: inherit;
                        display: inline-flex; align-items: center; gap: 6px; transition: background .15s;
                    }
                    .btn-submit:hover { background: #0C447C; }

                    /* ── Amber outlined reset button for clearing current step fields ── */
                    .btn-reset {
                        padding: 10px 22px; border-radius: 8px; font-size: .85rem;
                        background: #fff; border: 1px solid #f59e0b; color: #b45309;
                        cursor: pointer; font-family: inherit; font-weight: 500;
                        display: inline-flex; align-items: center; gap: 6px; transition: background .15s;
                    }
                    .btn-reset:hover { background: #fffbeb; }

                    /* ── Dashed blue outlined add more items button ── */
                    .btn-add {
                        padding: 9px 18px; border-radius: 8px; font-size: .82rem;
                        background: #fff; border: 1.5px dashed #185FA5; color: #185FA5;
                        cursor: pointer; font-family: inherit; font-weight: 600;
                        display: inline-flex; align-items: center; gap: 6px;
                        transition: background .15s; margin-bottom: 4px;
                    }
                    .btn-add:hover { background: #eaf4ff; }
                </style>

                <?php
                /*
                 * ── PHP: Fetch all assets assigned to the employee from DB ──
                 * Replace $employee_id with actual session or URL param value.
                 * $assets array feeds into JS as JSON for the popup detail view.
                 *
                 * Example model call (adjust to your CI model/method):
                 *   $assets  = $this->asset_model->get_employee_assets($employee_id);
                 *   $tickets = $this->asset_model->get_employee_tickets($employee_id);
                 *
                 * For now we use empty arrays as fallback placeholders.
                 */
                $assets  = isset($assets)  ? $assets  : [];
                $tickets = isset($tickets) ? $tickets : [];

                /*
                 * ── PHP: Compute summary counts from the asset array ──
                 * These values populate the readonly stat card numbers on page load.
                 */
                $total_assets      = count($assets);
                $active_assets     = count(array_filter($assets, fn($a) => ($a['status'] ?? '') === 'Active'));
                $repair_assets     = count(array_filter($assets, fn($a) => ($a['status'] ?? '') === 'Repair'));
                $pending_returns   = count(array_filter($assets, fn($a) => ($a['status'] ?? '') === 'Pending Return'));
                $warranty_expiring = count(array_filter($assets, fn($a) => !empty($a['warranty_expiring'])));
                $open_tickets      = count(array_filter($tickets, fn($t) => ($t['status'] ?? '') !== 'Closed'));
                ?>

                <form method="POST" action="<?php echo admin_url('hr/assets_management/add'); ?>" enctype="multipart/form-data" id="asset-form">

                    <!-- Hidden file input triggered by the custom dropzone UI -->
                    <input type="file" id="photo-file-input" name="condition_photos[]" accept="image/*" multiple />

                    <!-- ══ Step Progress Bar ══ -->
                    <div class="step-progress" id="step-progress-wrap">
                        <div class="step-bar"><div class="step-fill" id="step-fill"></div></div>
                        <div class="step-labels">
                            <div class="step-item" data-step="2"><span class="step-num">1</span>My Assets</div>
                            <div class="step-item" data-step="3"><span class="step-num">2</span>Verification</div>
                            <div class="step-item" data-step="4"><span class="step-num">3</span>Support</div>
                            <div class="step-item" data-step="5"><span class="step-num">4</span>Software</div>
                            <div class="step-item" data-step="6"><span class="step-num">5</span>Compliance</div>
                        </div>
                    </div>

                    <!-- ══ STEP 1 — Home / Summary ══ -->
                    <div class="form-section step-section active" data-step="1">
                        <h5><i class="fa fa-home" style="color:#185FA5;"></i>Assigned Assets</h5>
                        <p class="section-desc">Summary of current asset management status for this employee. Click any card to view details.</p>

                        <!-- Stat cards grid — each card is readonly and clickable to open popup -->
                        <div class="stats-grid">

                            <!-- Total Assets card — shows all assigned assets on click -->
                            <div class="stat-card" id="sc-total" role="button" tabindex="0" aria-label="Total Assets Assigned. Click to view details.">
                                <div class="stat-icon"><i class="fa fa-cubes"></i></div>
                                <span class="stat-label">Total Assets Assigned</span>
                                <span class="stat-value" id="sv-total"><?php echo $total_assets; ?></span>
                                <span class="stat-click-hint"><i class="fa fa-eye" style="font-size:.65rem;"></i>&nbsp;click to view</span>
                            </div>

                            <!-- Active Assets card — filters only assets with Active status -->
                            <div class="stat-card" id="sc-active" role="button" tabindex="0" aria-label="Active Assets. Click to view details."
                                 style="--icon-bg:#dcfce7;--icon-color:#16a34a;">
                                <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="fa fa-check-circle"></i></div>
                                <span class="stat-label">Active Assets</span>
                                <span class="stat-value" id="sv-active"><?php echo $active_assets; ?></span>
                                <span class="stat-click-hint"><i class="fa fa-eye" style="font-size:.65rem;"></i>&nbsp;click to view</span>
                            </div>

                            <!-- Assets Under Repair card — filters assets sent for repair -->
                            <div class="stat-card" id="sc-repair" role="button" tabindex="0" aria-label="Assets Under Repair. Click to view details.">
                                <div class="stat-icon" style="background:#fef9c3;color:#d97706;"><i class="fa fa-wrench"></i></div>
                                <span class="stat-label">Assets Under Repair</span>
                                <span class="stat-value" id="sv-repair"><?php echo $repair_assets; ?></span>
                                <span class="stat-click-hint"><i class="fa fa-eye" style="font-size:.65rem;"></i>&nbsp;click to view</span>
                            </div>

                            <!-- Pending Return card — filters assets awaiting return from employee -->
                            <div class="stat-card" id="sc-pending" role="button" tabindex="0" aria-label="Pending Return Assets. Click to view details.">
                                <div class="stat-icon" style="background:#fff7ed;color:#ea580c;"><i class="fa fa-clock-o"></i></div>
                                <span class="stat-label">Pending Return Assets</span>
                                <span class="stat-value" id="sv-pending"><?php echo $pending_returns; ?></span>
                                <span class="stat-click-hint"><i class="fa fa-eye" style="font-size:.65rem;"></i>&nbsp;click to view</span>
                            </div>

                            <!-- Warranty Expiring card — shows assets whose warranty is about to expire -->
                            <div class="stat-card" id="sc-warranty" role="button" tabindex="0" aria-label="Warranty Expiring Soon. Click to view details.">
                                <div class="stat-icon" style="background:#fce7f3;color:#db2777;"><i class="fa fa-exclamation-triangle"></i></div>
                                <span class="stat-label">Warranty Expiring Soon</span>
                                <span class="stat-value" id="sv-warranty"><?php echo $warranty_expiring; ?></span>
                                <span class="stat-click-hint"><i class="fa fa-eye" style="font-size:.65rem;"></i>&nbsp;click to view</span>
                            </div>

                            <!-- Open Tickets card — shows all non-closed support tickets -->
                            <div class="stat-card" id="sc-tickets" role="button" tabindex="0" aria-label="Open Support Tickets. Click to view details.">
                                <div class="stat-icon" style="background:#ede9fe;color:#7c3aed;"><i class="fa fa-ticket"></i></div>
                                <span class="stat-label">Open Support Tickets</span>
                                <span class="stat-value" id="sv-tickets"><?php echo $open_tickets; ?></span>
                                <span class="stat-click-hint"><i class="fa fa-eye" style="font-size:.65rem;"></i>&nbsp;click to view</span>
                            </div>

                        </div>
                        <!-- End stats grid -->

                        <!-- Home CTA — buttons to proceed or return an asset -->
                        <div class="home-cta-wrap">
                            <div class="home-cta-text">
                                <strong>Ready to manage assets?</strong>
                                Click to add or review employee assets step by step.
                            </div>
                            <div class="home-cta-buttons">
                                <!-- Button to open the return asset panel below -->
                                <button type="button" class="btn-return-asset-home" id="btn-return-asset">
                                    <i class="fa fa-sign-out"></i> Return Asset
                                </button>
                                <!-- Button to go to Step 2 (My Assets) -->
                                <button type="button" class="btn-add-asset-home" id="btn-home-proceed">
                                    <i class="fa fa-plus"></i> Add Asset &amp; Proceed
                                </button>
                            </div>
                        </div>

                        <!-- ══ Return Asset Panel — shown when Return Asset is clicked ══ -->
                        <div id="return-asset-panel">
                            <div class="return-panel-header">
                                <h5><i class="fa fa-sign-out" style="color:#ea580c;"></i> Return / Exit Management</h5>
                                <button type="button" class="btn-close-return" id="btn-close-return" title="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <p style="font-size:.82rem;color:#7c3012;margin:0 0 18px;">
                                Manage asset return, replacement or upgrade when employee exits or transfers.
                            </p>

                            <!-- Three radio groups for return, replacement, and upgrade requests -->
                            <div class="fg3">
                                <div class="form-group">
                                    <label>Return Request</label>
                                    <div class="checkbox-group">
                                        <div class="checkbox-item">
                                            <input type="radio" id="return_yes" name="return_request" value="Yes">
                                            <label for="return_yes">Yes</label>
                                        </div>
                                        <div class="checkbox-item">
                                            <input type="radio" id="return_no" name="return_request" value="No">
                                            <label for="return_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Replacement Request</label>
                                    <div class="checkbox-group">
                                        <div class="checkbox-item">
                                            <input type="radio" id="replace_yes" name="replacement_request" value="Yes">
                                            <label for="replace_yes">Yes</label>
                                        </div>
                                        <div class="checkbox-item">
                                            <input type="radio" id="replace_no" name="replacement_request" value="No">
                                            <label for="replace_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Upgrade Request</label>
                                    <div class="checkbox-group">
                                        <div class="checkbox-item">
                                            <input type="radio" id="upgrade_yes" name="upgrade_request" value="Yes">
                                            <label for="upgrade_yes">Yes</label>
                                        </div>
                                        <div class="checkbox-item">
                                            <input type="radio" id="upgrade_no" name="upgrade_request" value="No">
                                            <label for="upgrade_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Return date and verification status row -->
                            <div class="fg2">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" name="return_date_display" id="return_date_display"
                                        class="form-control" placeholder="DD/MM/YYYY"
                                        maxlength="10" autocomplete="off" />
                                    <!-- Hidden input stores formatted date for backend submission -->
                                    <input type="hidden" name="return_date" id="return_date_hidden" />
                                </div>
                                <div class="form-group">
                                    <label>Asset Verification Status</label>
                                    <select name="verification_status" class="form-control">
                                        <option value="">— Select status —</option>
                                        <option value="Verified">Verified</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Failed">Failed</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Clearance status select field -->
                            <div class="form-group" style="max-width:340px;">
                                <label>Clearance Status</label>
                                <select name="clearance_status" class="form-control">
                                    <option value="">— Select clearance status —</option>
                                    <option value="Cleared">Cleared</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Blocked">Blocked</option>
                                </select>
                            </div>

                            <!-- Return panel action buttons -->
                            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:8px;">
                                <button type="button" class="btn-cancel" id="btn-cancel-return">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                                <button type="submit" class="btn-submit-return">
                                    <i class="fa fa-check"></i> Submit Return
                                </button>
                            </div>
                        </div>
                        <!-- ══ END Return Asset Panel ══ -->

                    </div>
                    <!-- ══ END STEP 1 ══ -->

                    <!-- ══ STEP 2 — My Assets (dynamic asset cards) ══ -->
                    <div class="form-section step-section" data-step="2">
                        <h5><i class="fa fa-laptop" style="color:#185FA5;"></i> My Assets</h5>
                        <p class="section-desc">Add and review each asset assigned to this employee.</p>
                        <button type="button" class="btn-add" id="btn-add-asset">
                            <i class="fa fa-plus"></i> Add Asset Item
                        </button>
                        <!-- Dynamic asset cards are injected here by JS -->
                        <div class="dyn-cards-wrap" id="asset-cards-wrap"></div>
                    </div>

                    <!-- ══ STEP 3 — Condition & Verification ══ -->
                    <div class="form-section step-section" data-step="3">
                        <h5><i class="fa fa-check-square-o" style="color:#185FA5;"></i> Condition &amp; Verification</h5>
                        <p class="section-desc">Capture handover condition, accessories and employee acceptance.</p>

                        <!-- Handover and current condition dropdowns -->
                        <div class="fg2">
                            <div class="form-group">
                                <label>Asset Condition at Handover</label>
                                <select name="condition_handover" class="form-control">
                                    <option value="">— Select condition —</option>
                                    <option value="New">New</option>
                                    <option value="Good">Good</option>
                                    <option value="Refurbished">Refurbished</option>
                                    <option value="Damaged">Damaged</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Current Condition</label>
                                <select name="condition_current" class="form-control">
                                    <option value="">— Select condition —</option>
                                    <option value="New">New</option>
                                    <option value="Good">Good</option>
                                    <option value="Refurbished">Refurbished</option>
                                    <option value="Damaged">Damaged</option>
                                </select>
                            </div>
                        </div>

                        <!-- Photo upload dropzone and accessories textarea -->
                        <div class="fg2">
                            <div class="form-group">
                                <label>Photos of Asset</label>
                                <div class="photo-dropzone" id="photo-dropzone">
                                    <div class="photo-dz-info">
                                        <span class="photo-dz-hint">
                                            <i class="fa fa-info-circle"></i>
                                            JPG, PNG, WEBP &mdash; drag &amp; drop or click +
                                        </span>
                                        <button type="button" class="btn-photo-add" id="btn-photo-add">
                                            <i class="fa fa-plus"></i>
                                            <span id="btn-photo-add-label">Add Photos</span>
                                        </button>
                                    </div>
                                    <!-- Shown when no photos are selected, clicking it also opens file picker -->
                                    <div class="photo-empty-state" id="photo-empty-state"
                                         onclick="document.getElementById('photo-file-input').click()">
                                        <i class="fa fa-image"></i>
                                        <span class="pes-title">No photos added yet</span>
                                        <span class="pes-sub">Click here or drag images into this area</span>
                                    </div>
                                    <!-- Thumbnail preview grid shown when photos are selected -->
                                    <div class="photo-thumb-grid" id="photo-thumb-grid" style="display:none;"></div>
                                    <!-- Photo count badge shown below thumbnails -->
                                    <div id="photo-count-wrap" style="margin-top:10px;display:none;">
                                        <span class="photo-count-badge" id="photo-count-badge">
                                            <i class="fa fa-images"></i>
                                            <span id="photo-count-text">0 photos</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Accessories Included</label>
                                <textarea name="accessories_included" class="form-control"
                                    placeholder="e.g. Charger, Bag, Mouse, HDMI Cable, Keyboard..."></textarea>
                            </div>
                        </div>

                        <!-- Employee acceptance checkbox confirmation -->
                        <div class="acceptance-card">
                            <input type="checkbox" id="employee_acceptance" name="employee_acceptance" value="1">
                            <span>Employee accepts the condition and accessories listed above and confirms the details are accurate.</span>
                        </div>

                        <!-- Digital signature capture field -->
                        <div class="signature-section">
                            <div class="signature-field">
                                <label>Digital Signature</label>
                                <!-- Hidden input stores the base64 PNG of the drawn signature -->
                                <input type="hidden" name="employee_signature" id="employee_signature" />
                                <!-- Clickable display area that opens the signature drawing modal -->
                                <div class="signature-display" id="signature-display">
                                    <i class="fa fa-pencil-square-o"></i>&nbsp; Click here to draw signature
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ══ Signature Drawing Modal ══ -->
                    <div class="signature-modal" id="signature-modal">
                        <div class="signature-modal-content">
                            <div class="signature-modal-header">
                                <h6><i class="fa fa-pencil"></i> Draw Your Signature</h6>
                                <button type="button" id="signature-close" title="Close">&times;</button>
                            </div>
                            <div class="signature-canvas-wrapper">
                                <!-- Canvas element where user draws their signature with mouse or touch -->
                                <canvas id="signature-canvas" class="signature-canvas"></canvas>
                                <p class="signature-hint">Draw your signature using mouse or touch</p>
                            </div>
                            <div class="signature-modal-actions">
                                <button type="button" class="btn-cancel" id="signature-clear"><i class="fa fa-trash-o"></i> Clear</button>
                                <button type="button" class="btn-submit" id="signature-save"><i class="fa fa-check"></i> Save Signature</button>
                            </div>
                        </div>
                    </div>

                    <!-- ══ STEP 4 — Service & Support (ticket cards) ══ -->
                    <div class="form-section step-section" data-step="4">
                        <h5><i class="fa fa-headphones" style="color:#185FA5;"></i> Service &amp; Support</h5>
                        <p class="section-desc">Raise and track support tickets for hardware or software issues.</p>
                        <button type="button" class="btn-add" id="btn-add-ticket">
                            <i class="fa fa-plus"></i> Raise New Ticket
                        </button>
                        <!-- Dynamic support ticket cards injected by JS -->
                        <div class="dyn-cards-wrap" id="ticket-cards-wrap"></div>
                    </div>

                    <!-- ══ STEP 5 — Software Assets ══ -->
                    <div class="form-section step-section" data-step="5">
                        <h5><i class="fa fa-code" style="color:#185FA5;"></i> Software Assets</h5>
                        <p class="section-desc">Add licensed software and assigned digital access for this employee.</p>
                        <button type="button" class="btn-add" id="btn-add-software">
                            <i class="fa fa-plus"></i> Add Software Asset
                        </button>
                        <!-- Dynamic software asset cards injected by JS -->
                        <div class="dyn-cards-wrap" id="software-cards-wrap"></div>
                    </div>

                    <!-- ══ STEP 6 — Compliance & Policies ══ -->
                    <div class="form-section step-section" data-step="6">
                        <h5><i class="fa fa-shield" style="color:#185FA5;"></i> Compliance &amp; Policies</h5>
                        <p class="section-desc">Confirm policy acknowledgments and compliance before submitting.</p>
                        <div class="section-note">
                            <i class="fa fa-info-circle"></i>
                            Please read and acknowledge each policy. All checkboxes must be confirmed before submitting.
                        </div>

                        <!-- Grid of policy acknowledgment cards with checkbox inside each -->
                        <div class="policy-grid">
                            <label class="policy-card">
                                <input type="checkbox" name="policy_usage" value="1" />
                                <div class="policy-icon"><i class="fa fa-desktop"></i></div>
                                <div class="policy-text">
                                    <strong>Asset Usage Policy</strong>
                                    <p>I agree to use company assets only for official work and follow the usage guidelines.</p>
                                </div>
                            </label>
                            <label class="policy-card">
                                <input type="checkbox" name="policy_security" value="1" />
                                <div class="policy-icon" style="background:#fef9c3;color:#d97706;"><i class="fa fa-lock"></i></div>
                                <div class="policy-text">
                                    <strong>Security Policy</strong>
                                    <p>I agree to follow device security guidelines including antivirus and firewall compliance.</p>
                                </div>
                            </label>
                            <label class="policy-card">
                                <input type="checkbox" name="policy_data" value="1" />
                                <div class="policy-icon" style="background:#dcfce7;color:#16a34a;"><i class="fa fa-database"></i></div>
                                <div class="policy-text">
                                    <strong>Data Protection Policy</strong>
                                    <p>I agree to protect all confidential and sensitive data stored or accessed on this device.</p>
                                </div>
                            </label>
                            <label class="policy-card">
                                <input type="checkbox" name="policy_password" value="1" />
                                <div class="policy-icon" style="background:#ede9fe;color:#7c3aed;"><i class="fa fa-key"></i></div>
                                <div class="policy-text">
                                    <strong>Device Lock / Password Policy</strong>
                                    <p>I agree to set strong passwords and enable auto-lock on all assigned devices.</p>
                                </div>
                            </label>
                            <label class="policy-card">
                                <input type="checkbox" name="policy_nda" value="1" />
                                <div class="policy-icon" style="background:#fee2e2;color:#dc2626;"><i class="fa fa-file-text-o"></i></div>
                                <div class="policy-text">
                                    <strong>Company NDA Confirmation</strong>
                                    <p>I confirm I have read and agree to the terms of the company Non-Disclosure Agreement.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- ══ Form Navigation Buttons ══ -->
                    <div class="rform-actions">
                        <!-- Previous step button, hidden on step 1 -->
                        <button type="button" class="btn-cancel" id="step-prev" style="display:none;">
                            <i class="fa fa-arrow-left"></i> Previous
                        </button>
                        <!-- Next step button, hidden on last step and step 1 -->
                        <button type="button" class="btn-submit" id="step-next" style="display:none;">
                            Next <i class="fa fa-arrow-right"></i>
                        </button>
                        <!-- Final submit button, shown only on last step -->
                        <button type="submit" class="btn-submit" id="form-submit" style="display:none;">
                            <i class="fa fa-check"></i> Submit Asset Details
                        </button>
                        <!-- Reset button clears all inputs on the current step only -->
                        <button type="button" class="btn-reset" id="step-reset">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                    </div>

                </form>
                <!-- ══ END FORM ══ -->

                <!-- ════════════════════════════════════════════════════════
                     STAT POPUP OVERLAY — shown when any stat card is clicked
                ════════════════════════════════════════════════════════ -->
                <div class="stat-overlay" id="stat-overlay" role="dialog" aria-modal="true" aria-labelledby="stat-popup-title">
                    <div class="stat-popup">

                        <!-- Popup header with colored icon, title, count badge, and close button -->
                        <div class="stat-popup-header">
                            <div class="stat-popup-title">
                                <span id="stat-popup-icon" style="font-size:1.1rem;"></span>
                                <span id="stat-popup-title">Asset Details</span>
                                <span class="stat-popup-badge" id="stat-popup-badge">0 records</span>
                            </div>
                            <button class="stat-popup-close" id="stat-popup-close" aria-label="Close popup">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>

                        <!-- Scrollable popup body containing search bar and asset table -->
                        <div class="stat-popup-body" id="stat-popup-body">

                            <!-- Live search bar to filter rows in the popup table -->
                            <div class="popup-search-bar">
                                <i class="fa fa-search"></i>
                                <input type="text" id="popup-search-input"
                                       placeholder="Search by asset ID, name, brand, serial..." />
                            </div>

                            <!-- Table or ticket list rendered here by JS on card click -->
                            <div id="popup-table-wrap"></div>

                        </div>

                        <!-- Popup footer showing visible vs total record count -->
                        <div class="stat-popup-footer">
                            <span id="popup-footer-text">Showing all records</span>
                            <span style="color:#185FA5;font-weight:600;">Asset Management System</span>
                        </div>

                    </div>
                </div>
                <!-- ════════════════════════════════════════════════════════
                     END STAT POPUP OVERLAY
                ════════════════════════════════════════════════════════ -->

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ═══════════════════════════════════════════════════════════════
       SECTION 1: DATA — PHP arrays passed as JS JSON
    ═══════════════════════════════════════════════════════════════ */

    // All asset records from PHP backend, encoded as JSON for JS popup use
    var ASSET_DATA = <?php echo json_encode(array_values($assets)); ?>;

    // All ticket records from PHP backend, encoded as JSON for JS popup use
    var TICKET_DATA = <?php echo json_encode(array_values($tickets)); ?>;

    /* ═══════════════════════════════════════════════════════════════
       SECTION 2: STEP NAVIGATION — controls which step section shows
    ═══════════════════════════════════════════════════════════════ */

    // Collect all step section elements in DOM order
    var steps        = Array.from(document.querySelectorAll('.step-section'));

    // Progress bar wrapper that becomes visible after step 1
    var progressWrap = document.getElementById('step-progress-wrap');

    // The blue fill bar that grows as user advances through steps
    var progressFill = document.getElementById('step-fill');

    // Clickable step label items in the progress bar below the fill
    var stepItems    = Array.from(document.querySelectorAll('.step-item[data-step]'));

    // Previous step navigation button
    var prevBtn      = document.getElementById('step-prev');

    // Next step navigation button
    var nextBtn      = document.getElementById('step-next');

    // Final submit button shown only on the last step
    var submitBtn    = document.getElementById('form-submit');

    // Total number of step sections including the home step
    var totalSteps   = steps.length;

    // Step number where the main flow begins (step 2 = My Assets)
    var FLOW_START   = 2;

    // Number of flow steps excluding the home step
    var FLOW_STEPS   = totalSteps - 1;

    // Current active step, starts at 1 (home)
    var currentStep  = 1;

    // Update all visual elements to reflect the current active step number
    function updateStepDisplay() {

        // Show only the section matching current step, hide all others
        steps.forEach(function (s) {
            s.classList.toggle('active', Number(s.dataset.step) === currentStep);
        });

        // Hide progress bar on step 1 (home), show and update on all other steps
        if (currentStep === 1) {
            progressWrap.classList.remove('visible');
        } else {
            progressWrap.classList.add('visible');
            // Calculate percentage fill based on position within the flow steps
            var pct = FLOW_STEPS > 1 ? ((currentStep - FLOW_START) / (FLOW_STEPS - 1)) * 100 : 0;
            progressFill.style.width = Math.min(pct, 100) + '%';
        }

        // Mark each step label as active or completed based on current position
        stepItems.forEach(function (item) {
            var n = Number(item.dataset.step);
            item.classList.toggle('active',    n === currentStep);
            item.classList.toggle('completed', n < currentStep);
        });

        // Show Previous button for all steps except step 1
        prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-flex';

        // Show Next button for all steps except the last and step 1
        nextBtn.style.display = (currentStep === totalSteps || currentStep === 1) ? 'none' : 'inline-flex';

        // Show Submit button only on the last step
        submitBtn.style.display = currentStep === totalSteps ? 'inline-flex' : 'none';
    }

    // Navigate to a specific step number and scroll to top
    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        currentStep = step;
        updateStepDisplay();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Home proceed button navigates to step 2 (My Assets)
    document.getElementById('btn-home-proceed').addEventListener('click', function () { goToStep(2); });

    // Previous button goes back one step
    prevBtn.addEventListener('click', function () { goToStep(currentStep - 1); });

    // Next button advances one step
    nextBtn.addEventListener('click', function () { goToStep(currentStep + 1); });

    // Clicking a step label in the progress bar jumps directly to that step
    stepItems.forEach(function (item) {
        item.addEventListener('click', function () { goToStep(Number(item.dataset.step)); });
    });

    // Reset button clears all inputs on the currently visible step section
    document.getElementById('step-reset').addEventListener('click', function () {
        var sec = document.querySelector('.step-section[data-step="' + currentStep + '"]');
        if (!sec) return;

        // Reset text, select, number, checkbox, and radio inputs to defaults
        sec.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (el.type === 'checkbox' || el.type === 'radio') el.checked = false;
            else if (el.type === 'number') el.value = el.min || '0';
            else el.value = '';
        });

        // Also clear photo array and re-render empty state if on step 3
        if (currentStep === 3) { photoFiles = []; renderPhotoUI(); }
    });

    /* ═══════════════════════════════════════════════════════════════
       SECTION 3: STAT POPUP — opens on stat card click with asset table
    ═══════════════════════════════════════════════════════════════ */

    // Overlay element that dims the background behind the popup
    var statOverlay   = document.getElementById('stat-overlay');

    // Popup body div where the asset table will be rendered
    var popupTableWrap= document.getElementById('popup-table-wrap');

    // Search input inside the popup to filter the table rows live
    var popupSearch   = document.getElementById('popup-search-input');

    // Footer text showing count of visible vs total records
    var popupFooter   = document.getElementById('popup-footer-text');

    // Holds the currently visible rows in the popup for search filtering
    var currentPopupRows = [];

    // Holds the key identifying which stat card opened the current popup
    var currentPopupKey  = '';

    // Configuration for each stat card: filter function and display settings
    var STAT_CONFIG = {

        // Total card shows all assets regardless of status
        'sc-total': {
            title:      'Total Assets Assigned',
            icon:       'fa fa-cubes',
            iconStyle:  'background:#eaf4ff;color:#185FA5;',
            badgeStyle: 'background:#eaf4ff;color:#185FA5;',
            type:       'asset',
            filter:     function (a) { return true; }
        },

        // Active card filters only assets where status equals Active
        'sc-active': {
            title:      'Active Assets',
            icon:       'fa fa-check-circle',
            iconStyle:  'background:#dcfce7;color:#16a34a;',
            badgeStyle: 'background:#dcfce7;color:#16a34a;',
            type:       'asset',
            filter:     function (a) { return (a.status || '').toLowerCase() === 'active'; }
        },

        // Repair card filters only assets currently under repair status
        'sc-repair': {
            title:      'Assets Under Repair',
            icon:       'fa fa-wrench',
            iconStyle:  'background:#fef9c3;color:#d97706;',
            badgeStyle: 'background:#fef9c3;color:#d97706;',
            type:       'asset',
            filter:     function (a) { return (a.status || '').toLowerCase() === 'repair'; }
        },

        // Pending return card filters assets awaiting return from employee
        'sc-pending': {
            title:      'Pending Return Assets',
            icon:       'fa fa-clock-o',
            iconStyle:  'background:#fff7ed;color:#ea580c;',
            badgeStyle: 'background:#fff7ed;color:#ea580c;',
            type:       'asset',
            filter:     function (a) { return (a.status || '').toLowerCase() === 'pending return'; }
        },

        // Warranty card filters assets flagged as warranty expiring soon
        'sc-warranty': {
            title:      'Warranty Expiring Soon',
            icon:       'fa fa-exclamation-triangle',
            iconStyle:  'background:#fce7f3;color:#db2777;',
            badgeStyle: 'background:#fce7f3;color:#db2777;',
            type:       'asset',
            filter:     function (a) { return !!a.warranty_expiring; }
        },

        // Tickets card shows open support tickets instead of asset records
        'sc-tickets': {
            title:      'Open Support Tickets',
            icon:       'fa fa-ticket',
            iconStyle:  'background:#ede9fe;color:#7c3aed;',
            badgeStyle: 'background:#ede9fe;color:#7c3aed;',
            type:       'ticket',
            filter:     function (t) { return (t.status || '').toLowerCase() !== 'closed'; }
        }
    };

    // Status pill color map for asset and ticket status badges
    var STATUS_COLORS = {
        'active':         { bg:'#dcfce7', color:'#15803d' },
        'repair':         { bg:'#fef9c3', color:'#b45309' },
        'pending return': { bg:'#fff7ed', color:'#c2410c' },
        'warranty':       { bg:'#fce7f3', color:'#be185d' },
        'open':           { bg:'#fee2e2', color:'#b91c1c' },
        'in progress':    { bg:'#fef9c3', color:'#b45309' },
        'closed':         { bg:'#dcfce7', color:'#15803d' },
        'high':           { bg:'#fee2e2', color:'#b91c1c' },
        'medium':         { bg:'#fef9c3', color:'#b45309' },
        'low':            { bg:'#dcfce7', color:'#15803d' }
    };

    // Build a colored pill badge HTML string for a given label text
    function makePill(text) {
        if (!text) return '<span style="color:#9ca3af;">—</span>';
        var key = text.trim().toLowerCase();
        var s   = STATUS_COLORS[key] || { bg:'#f3f4f6', color:'#6b7280' };
        return '<span class="ap-pill" style="background:' + s.bg + ';color:' + s.color + ';">' +
               escHtml(text) + '</span>';
    }

    // Escape special HTML characters to prevent XSS in table cell content
    function escHtml(str) {
        return String(str || '—')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Render asset rows into the popup table — called on open and on search input
    function renderAssetTable(rows) {
        if (rows.length === 0) {
            // Show empty state message when no assets match the filter or search
            popupTableWrap.innerHTML =
                '<div class="popup-empty">' +
                    '<i class="fa fa-inbox"></i>' +
                    'No assets found' +
                    '<span>Try a different search term or check back later.</span>' +
                '</div>';
            popupFooter.textContent = 'No records found';
            return;
        }

        // Build the full asset table with all required columns
        var html =
            '<table class="asset-popup-table">' +
            '<thead><tr>' +
                '<th>Asset ID</th>' +
                '<th>Asset Type</th>' +
                '<th>Category</th>' +
                '<th>Asset Name</th>' +
                '<th>Brand</th>' +
                '<th>Model Number</th>' +
                '<th>Serial Number</th>' +
                '<th>Status</th>' +
            '</tr></thead><tbody>';

        // Add one row per asset record with all fields populated
        rows.forEach(function (a) {
            html +=
                '<tr>' +
                    '<td><span class="ap-mono">' + escHtml(a.asset_tag || a.id) + '</span></td>' +
                    '<td>' + escHtml(a.asset_type  || a.type)     + '</td>' +
                    '<td><span class="ap-pill" style="background:#f3f4f6;color:#4b5563;font-weight:600;">' + escHtml(a.asset_category || a.category) + '</span></td>' +
                    '<td style="font-weight:600;">' + escHtml(a.asset_name || a.name)   + '</td>' +
                    '<td>' + escHtml(a.brand_name   || a.brand)   + '</td>' +
                    '<td style="color:#6b7280;">' + escHtml(a.model_number  || a.model)  + '</td>' +
                    '<td><span class="ap-mono">' + escHtml(a.serial_number || a.serial) + '</span></td>' +
                    '<td>' + makePill(a.status) + '</td>' +
                '</tr>';
        });

        html += '</tbody></table>';
        popupTableWrap.innerHTML = html;

        // Update footer with count of visible rows vs total filtered rows
        popupFooter.textContent = 'Showing ' + rows.length + ' of ' + currentPopupRows.length + ' records';
    }

    // Render ticket rows into the popup table for the tickets stat card
    function renderTicketTable(rows) {
        if (rows.length === 0) {
            // Show empty state when no open tickets exist
            popupTableWrap.innerHTML =
                '<div class="popup-empty">' +
                    '<i class="fa fa-check-circle" style="color:#16a34a;opacity:.5;"></i>' +
                    'No open tickets' +
                    '<span>All support tickets have been resolved or closed.</span>' +
                '</div>';
            popupFooter.textContent = 'No open tickets found';
            return;
        }

        // Build the ticket table with issue type, asset, priority, status, and description columns
        var html =
            '<table class="asset-popup-table">' +
            '<thead><tr>' +
                '<th>Ticket ID</th>' +
                '<th>Issue Type</th>' +
                '<th>Asset ID</th>' +
                '<th>Priority</th>' +
                '<th>Status</th>' +
                '<th>Description</th>' +
            '</tr></thead><tbody>';

        // Add one row per ticket record
        rows.forEach(function (t) {
            html +=
                '<tr>' +
                    '<td><span class="ap-mono" style="color:#7c3aed;">' + escHtml(t.ticket_id || t.id) + '</span></td>' +
                    '<td>' + escHtml(t.issue_type || t.type) + '</td>' +
                    '<td><span class="ap-mono">' + escHtml(t.asset_tag || t.asset) + '</span></td>' +
                    '<td>' + makePill(t.priority) + '</td>' +
                    '<td>' + makePill(t.status)   + '</td>' +
                    '<td style="color:#6b7280;max-width:200px;">' + escHtml(t.description || t.desc) + '</td>' +
                '</tr>';
        });

        html += '</tbody></table>';
        popupTableWrap.innerHTML = html;

        // Update footer count for ticket records
        popupFooter.textContent = 'Showing ' + rows.length + ' of ' + currentPopupRows.length + ' tickets';
    }

    // Open the stat popup for the clicked card using its config key
    function openStatPopup(cardKey) {
        var cfg = STAT_CONFIG[cardKey];
        if (!cfg) return;

        currentPopupKey = cardKey;

        // Filter source data using the config's filter function
        var source = cfg.type === 'ticket' ? TICKET_DATA : ASSET_DATA;
        currentPopupRows = source.filter(cfg.filter);

        // Set the popup header icon with matching colored style
        var iconEl = document.getElementById('stat-popup-icon');
        iconEl.className = 'fa ' + cfg.icon;
        iconEl.style.cssText = cfg.iconStyle + ' width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;';

        // Set popup title text from config
        document.getElementById('stat-popup-title').textContent = cfg.title;

        // Set the badge with count and matching color from config
        var badgeEl = document.getElementById('stat-popup-badge');
        badgeEl.textContent = currentPopupRows.length + ' record' + (currentPopupRows.length !== 1 ? 's' : '');
        badgeEl.style.cssText = cfg.badgeStyle;

        // Clear any previous search input before rendering fresh data
        popupSearch.value = '';

        // Render the table with all filtered rows, no search applied yet
        if (cfg.type === 'ticket') {
            renderTicketTable(currentPopupRows);
        } else {
            renderAssetTable(currentPopupRows);
        }

        // Show the overlay by adding the open class
        statOverlay.classList.add('open');

        // Focus the close button for accessibility keyboard navigation
        document.getElementById('stat-popup-close').focus();
    }

    // Close the stat popup by removing the open class from overlay
    function closeStatPopup() {
        statOverlay.classList.remove('open');
        popupSearch.value = '';
    }

    // Attach click listeners to all six stat cards to open their popups
    Object.keys(STAT_CONFIG).forEach(function (key) {
        var card = document.getElementById(key);
        if (!card) return;

        // Mouse click opens popup for this card
        card.addEventListener('click', function () { openStatPopup(key); });

        // Keyboard Enter and Space also open popup for accessibility
        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openStatPopup(key); }
        });
    });

    // Clicking outside the popup box (on the overlay) closes it
    statOverlay.addEventListener('click', function (e) {
        if (e.target === statOverlay) closeStatPopup();
    });

    // Close button inside popup header closes the popup
    document.getElementById('stat-popup-close').addEventListener('click', closeStatPopup);

    // Escape key anywhere on the page closes the popup if it is open
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && statOverlay.classList.contains('open')) closeStatPopup();
    });

    // Live search input filters visible table rows by matching text content
    popupSearch.addEventListener('input', function () {
        var term = this.value.trim().toLowerCase();
        var cfg  = STAT_CONFIG[currentPopupKey];
        if (!cfg) return;

        if (!term) {
            // Empty search shows all currently filtered rows without further filtering
            if (cfg.type === 'ticket') renderTicketTable(currentPopupRows);
            else                       renderAssetTable(currentPopupRows);
            return;
        }

        // Filter rows by checking if any field value contains the search term
        var filtered = currentPopupRows.filter(function (row) {
            return Object.values(row).some(function (v) {
                return String(v || '').toLowerCase().includes(term);
            });
        });

        // Re-render table with only the search-matched rows
        if (cfg.type === 'ticket') renderTicketTable(filtered);
        else                       renderAssetTable(filtered);
    });

    /* ═══════════════════════════════════════════════════════════════
       SECTION 4: RETURN ASSET PANEL — toggle open/close behavior
    ═══════════════════════════════════════════════════════════════ */

    // The return asset panel element shown when the return button is clicked
    var returnPanel     = document.getElementById('return-asset-panel');

    // Button that opens or toggles the return panel
    var btnReturn       = document.getElementById('btn-return-asset');

    // X button inside the return panel header that closes it
    var btnCloseReturn  = document.getElementById('btn-close-return');

    // Cancel button at the bottom of the return panel form
    var btnCancelReturn = document.getElementById('btn-cancel-return');

    // Open the return panel and scroll it into view smoothly
    function openReturnPanel() {
        returnPanel.classList.add('open');
        returnPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Close the return panel by removing the open class
    function closeReturnPanel() { returnPanel.classList.remove('open'); }

    // Toggle return panel open/close on button click
    btnReturn.addEventListener('click', function () {
        returnPanel.classList.contains('open') ? closeReturnPanel() : openReturnPanel();
    });

    // Header close button and footer cancel button both close the panel
    btnCloseReturn.addEventListener('click',  closeReturnPanel);
    btnCancelReturn.addEventListener('click', closeReturnPanel);

    /* ═══════════════════════════════════════════════════════════════
       SECTION 5: DATE HELPER — auto-format dates as DD/MM/YYYY
    ═══════════════════════════════════════════════════════════════ */

    // Return today's date formatted as DD/MM/YYYY string
    function getTodayDDMMYYYY() {
        var d  = new Date();
        var dd = String(d.getDate()).padStart(2,'0');
        var mm = String(d.getMonth()+1).padStart(2,'0');
        return dd + '/' + mm + '/' + d.getFullYear();
    }

    // Return date display input element (formatted for user view)
    var returnDateDisplay = document.getElementById('return_date_display');

    // Hidden input that stores the date value for form submission
    var returnDateHidden  = document.getElementById('return_date_hidden');

    if (returnDateDisplay) {
        // Pre-fill return date with today's date on page load
        returnDateDisplay.value = getTodayDDMMYYYY();
        returnDateHidden.value  = getTodayDDMMYYYY();

        // Auto-insert slashes as user types digits into the return date field
        returnDateDisplay.addEventListener('input', function () {
            var v   = this.value.replace(/\D/g,'').substring(0,8);
            var out = v.length > 4 ? v.substring(0,2)+'/'+v.substring(2,4)+'/'+v.substring(4)
                    : v.length > 2 ? v.substring(0,2)+'/'+v.substring(2) : v;
            this.value             = out;
            returnDateHidden.value = out;
        });
    }

    /* ═══════════════════════════════════════════════════════════════
       SECTION 6: PHOTO UPLOAD — drag and drop image preview system
    ═══════════════════════════════════════════════════════════════ */

    // Array holding the currently selected File objects for the photo upload
    var photoFiles  = [];

    // Hidden file input that receives files from both dropzone and file picker
    var fileInput   = document.getElementById('photo-file-input');

    // Visual dropzone container that accepts drag-and-drop and shows previews
    var dropzone    = document.getElementById('photo-dropzone');

    // Placeholder state shown when no photos have been added yet
    var emptyState  = document.getElementById('photo-empty-state');

    // Grid container where thumbnail previews are rendered
    var thumbGrid   = document.getElementById('photo-thumb-grid');

    // Wrapper that shows the photo count badge below thumbnails
    var countWrap   = document.getElementById('photo-count-wrap');

    // Text inside the count badge showing how many photos are selected
    var countText   = document.getElementById('photo-count-text');

    // Button that triggers the file picker for adding or adding more photos
    var addBtn      = document.getElementById('btn-photo-add');

    // Label text inside the add button, changes to Add More when photos exist
    var addBtnLabel = document.getElementById('btn-photo-add-label');

    // Clicking add button opens the file picker without bubbling to dropzone
    addBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        fileInput.click();
    });

    // When file input changes, add selected files and clear input for reuse
    fileInput.addEventListener('change', function () {
        handlePhotoFiles(this.files);
        this.value = '';
    });

    // Add blue border when a file is dragged over the dropzone area
    dropzone.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });

    // Remove blue border when dragged file leaves the dropzone area
    dropzone.addEventListener('dragleave', function (e) {
        if (!dropzone.contains(e.relatedTarget)) dropzone.classList.remove('dragover');
    });

    // Handle dropped files by passing them to the photo handler function
    dropzone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        handlePhotoFiles(e.dataTransfer.files);
    });

    // Process new file list, skip non-images and duplicates, then re-render
    function handlePhotoFiles(newFiles) {
        Array.from(newFiles).forEach(function (f) {
            // Skip files that are not image types
            if (!f.type.startsWith('image/')) return;
            // Skip duplicate files that are already in the array
            if (photoFiles.find(function (x) { return x.name === f.name && x.size === f.size; })) return;
            photoFiles.push(f);
        });
        renderPhotoUI();
    }

    // Remove a photo from the array by index and re-render the thumbnail grid
    window.removePhoto = function (idx) {
        photoFiles.splice(idx, 1);
        renderPhotoUI();
    };

    // Re-render the photo dropzone UI based on current photoFiles array state
    function renderPhotoUI() {
        thumbGrid.innerHTML = '';

        if (photoFiles.length === 0) {
            // Show placeholder, hide thumbnails and count badge when empty
            emptyState.style.display  = 'flex';
            thumbGrid.style.display   = 'none';
            countWrap.style.display   = 'none';
            addBtnLabel.textContent   = 'Add Photos';
        } else {
            // Hide placeholder, show thumbnails and count badge when photos exist
            emptyState.style.display = 'none';
            thumbGrid.style.display  = 'flex';
            countWrap.style.display  = 'block';
            addBtnLabel.textContent  = 'Add More';
            countText.textContent    = photoFiles.length + ' photo' + (photoFiles.length > 1 ? 's' : '') + ' selected';

            // Create a thumbnail preview element for each selected photo file
            photoFiles.forEach(function (file, i) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var thumb = document.createElement('div');
                    thumb.className = 'photo-thumb';
                    thumb.innerHTML =
                        '<img src="' + e.target.result + '" alt="' + file.name + '" />' +
                        '<span class="photo-thumb-name">' + file.name + '</span>' +
                        '<button type="button" class="photo-thumb-remove" onclick="removePhoto(' + i + ')" title="Remove this photo">' +
                            '<i class="fa fa-times"></i>' +
                        '</button>';
                    thumbGrid.appendChild(thumb);
                };
                reader.readAsDataURL(file);
            });
        }

        // Sync the actual file input with the current photoFiles array via DataTransfer
        try {
            var dt = new DataTransfer();
            photoFiles.forEach(function (f) { dt.items.add(f); });
            fileInput.files = dt.files;
        } catch (e) { /* DataTransfer not supported in older browsers, fallback silently */ }
    }

    // Render the initial empty state of the photo dropzone on page load
    renderPhotoUI();

    /* ═══════════════════════════════════════════════════════════════
       SECTION 7: DYNAMIC CARD ENGINE — asset, ticket, software cards
    ═══════════════════════════════════════════════════════════════ */

    // Global index counter ensuring each dynamic card has a unique name key
    var gIdx = 0;

    // Helper: wrap label and input HTML in a form-group div
    function fg(label, inner) {
        return '<div class="form-group"><label>' + label + '</label>' + inner + '</div>';
    }

    // Helper: build an input element with name array notation for PHP POST
    function inp(type, name, idx, ph, extra) {
        return '<input type="' + type + '" name="' + name + '[' + idx + ']" class="form-control" placeholder="' + (ph||'') + '" ' + (extra||'') + ' />';
    }

    // Helper: build a textarea element with name array notation for PHP POST
    function ta(name, idx, ph) {
        return '<textarea name="' + name + '[' + idx + ']" class="form-control" placeholder="' + (ph||'') + '"></textarea>';
    }

    // Helper: build a select element with options array and name array notation
    function sel(name, idx, opts) {
        var o = opts.map(function(x){ return '<option value="'+x.v+'">'+x.l+'</option>'; }).join('');
        return '<select name="' + name + '[' + idx + ']" class="form-control">' + o + '</select>';
    }

    // Helper: wrap two form-group elements in a two-column grid row
    function wrap2(a,b)   { return '<div class="fg2">'+a+b+'</div>'; }

    // Helper: wrap three form-group elements in a three-column grid row
    function wrap3(a,b,c) { return '<div class="fg3">'+a+b+c+'</div>'; }

    // Remove a dynamic card by its unique index from the given container
    window.removeCard = function (containerId, idx) {
        var card = document.querySelector('#'+containerId+' [data-dyn-id="'+idx+'"]');
        if (card) card.parentNode.removeChild(card);
    };

    // Build the card header HTML with title, numbered badge, and remove button
    function makeHeader(label, num, badge, containerId, idx) {
        return '<div class="dyn-card-header">' +
            '<span class="dyn-card-title">' +
                '<i class="fa fa-cube" style="color:#185FA5;margin-right:6px;"></i>' + label + ' #' + num +
                (badge ? ' <span class="dyn-badge">' + badge + '</span>' : '') +
            '</span>' +
            '<button type="button" class="btn-remove-card" onclick="removeCard(\''+containerId+'\','+idx+')"><i class="fa fa-times"></i></button>' +
        '</div>';
    }

    // Add a new hardware asset card with all required fields to the asset list
    function addAssetCard() {
        var idx   = gIdx++;
        var count = document.querySelectorAll('#asset-cards-wrap [data-dyn-id]').length + 1;
        var card  = document.createElement('div');
        card.className = 'dyn-card';
        card.setAttribute('data-dyn-id', idx);
        card.innerHTML =
            makeHeader('Asset', count, 'Hardware', 'asset-cards-wrap', idx) +
            wrap2(
                fg('Asset ID / Tag Number', inp('text','asset_tag',idx,'e.g. AST-001')),
                fg('Asset Type', sel('asset_type',idx,[
                    {v:'',l:'— Select asset type —'},
                    {v:'Laptop',l:'Laptop'},{v:'Mobile',l:'Mobile'},{v:'Monitor',l:'Monitor'},
                    {v:'Keyboard',l:'Keyboard'},{v:'Mouse',l:'Mouse'},{v:'Headset',l:'Headset'},
                    {v:'SIM',l:'SIM Card'},{v:'Access Card',l:'Access Card'},{v:'Dongle',l:'Dongle'},{v:'Other',l:'Other'}
                ]))
            ) +
            wrap2(
                fg('Asset Category', sel('asset_category',idx,[
                    {v:'',l:'— Select category —'},{v:'Hardware',l:'Hardware'},{v:'Software',l:'Software'},{v:'Accessories',l:'Accessories'}
                ])),
                fg('Asset Name', inp('text','asset_name',idx,'Enter asset name'))
            ) +
            wrap3(
                fg('Brand Name',    inp('text','brand_name',   idx,'e.g. Dell, HP, Apple')),
                fg('Model Number',  inp('text','model_number', idx,'e.g. Inspiron 15')),
                fg('Serial Number', inp('text','serial_number',idx,'Enter serial number'))
            ) +
            fg('Configuration / Specs',
                '<textarea name="configuration['+idx+']" class="form-control" style="min-height:70px;" placeholder="e.g. Intel i7, 16GB RAM, 512GB SSD, Windows 11 Pro"></textarea>');
        document.getElementById('asset-cards-wrap').appendChild(card);
    }

    // Add a new support ticket card with issue, priority, status, and media fields
    function addTicketCard() {
        var idx   = gIdx++;
        var count = document.querySelectorAll('#ticket-cards-wrap [data-dyn-id]').length + 1;
        var card  = document.createElement('div');
        card.className = 'dyn-card';
        card.setAttribute('data-dyn-id', idx);
        card.innerHTML =
            '<div class="dyn-card-header">' +
                '<span class="dyn-card-title"><i class="fa fa-ticket" style="color:#185FA5;margin-right:6px;"></i> Ticket #'+count+' <span class="dyn-badge" style="background:#fee2e2;color:#991b1b;">Open</span></span>' +
                '<button type="button" class="btn-remove-card" onclick="removeCard(\'ticket-cards-wrap\','+idx+')"><i class="fa fa-times"></i></button>' +
            '</div>' +
            wrap3(
                fg('Issue Type', sel('ticket_issue_type',idx,[
                    {v:'',l:'— Select issue type —'},
                    {v:'Hardware',l:'Hardware'},{v:'Software',l:'Software'},{v:'Damage',l:'Damage'},
                    {v:'Performance',l:'Performance'},{v:'Missing Accessory',l:'Missing Accessory'}
                ])),
                fg('Priority', sel('ticket_priority',idx,[
                    {v:'',l:'— Select priority —'},{v:'Low',l:'Low'},{v:'Medium',l:'Medium'},{v:'High',l:'High'}
                ])),
                fg('Ticket Status', sel('ticket_status',idx,[
                    {v:'',l:'— Select status —'},{v:'Open',l:'Open'},{v:'In Progress',l:'In Progress'},{v:'Closed',l:'Closed'}
                ]))
            ) +
            wrap2(
                fg('Description',         ta('ticket_description',idx,'Describe the issue in detail...')),
                fg('IT Support Comments', ta('ticket_comments',   idx,'IT team notes and resolution steps...'))
            ) +
            fg('Upload Photo / Video',
                '<input type="file" name="ticket_media['+idx+']" class="form-control" accept="image/*,video/*" />') +
            '<span class="upload-hint" style="display:block;margin-top:-8px;margin-bottom:4px;font-size:.72rem;color:#9ca3af;">JPG, PNG, MP4, MOV accepted</span>';
        document.getElementById('ticket-cards-wrap').appendChild(card);
    }

    // Add a new software asset card with name, license key, expiry, and access fields
    function addSoftwareCard() {
        var idx   = gIdx++;
        var count = document.querySelectorAll('#software-cards-wrap [data-dyn-id]').length + 1;
        var card  = document.createElement('div');
        card.className = 'dyn-card';
        card.setAttribute('data-dyn-id', idx);
        var todayVal = getTodayDDMMYYYY();
        card.innerHTML =
            '<div class="dyn-card-header">' +
                '<span class="dyn-card-title"><i class="fa fa-code" style="color:#185FA5;margin-right:6px;"></i> Software Asset #'+count+'</span>' +
                '<button type="button" class="btn-remove-card" onclick="removeCard(\'software-cards-wrap\','+idx+')"><i class="fa fa-times"></i></button>' +
            '</div>' +
            wrap2(
                fg('Software Name', inp('text','software_name',idx,'e.g. Microsoft Office, Adobe CC')),
                fg('License Expiry Date',
                    '<input type="text" name="license_expiry['+idx+']" id="le_'+idx+'" class="form-control" placeholder="DD/MM/YYYY" maxlength="10" autocomplete="off" value="'+todayVal+'" oninput="autoDateSlash(this)" />'
                )
            ) +
            fg('License Key',
                '<div class="lk-wrap">' +
                    // Password input hides license key by default with toggle eye button
                    '<input type="password" name="license_key['+idx+']" id="lk_'+idx+'" class="form-control" placeholder="Enter license key" autocomplete="off" />' +
                    '<button type="button" class="toggle-key-btn" onclick="toggleKey('+idx+')"><i class="fa fa-eye" id="lk_icon_'+idx+'"></i></button>' +
                '</div>') +
            fg('Assigned Access / Permissions',
                '<textarea name="assigned_access['+idx+']" class="form-control" style="min-height:70px;" placeholder="e.g. VPN, GitHub, AWS, CRM, ERP, Google Workspace..."></textarea>');
        document.getElementById('software-cards-wrap').appendChild(card);
    }

    // Auto-insert slashes after day and month digits as user types the expiry date
    window.autoDateSlash = function (input) {
        var v   = input.value.replace(/\D/g,'').substring(0,8);
        var out = v.length > 4 ? v.substring(0,2)+'/'+v.substring(2,4)+'/'+v.substring(4)
                : v.length > 2 ? v.substring(0,2)+'/'+v.substring(2) : v;
        input.value = out;
    };

    /* ═══════════════════════════════════════════════════════════════
       SECTION 8: SIGNATURE PAD — canvas-based digital signature capture
    ═══════════════════════════════════════════════════════════════ */

    // Hidden input that stores the base64 PNG of the drawn signature for POST
    var sigInput   = document.getElementById('employee_signature');

    // Clickable display area that shows a preview of the saved signature
    var sigDisplay = document.getElementById('signature-display');

    // Full-screen overlay modal containing the signature canvas
    var sigModal   = document.getElementById('signature-modal');

    // Canvas element where the employee draws their signature
    var sigCanvas  = document.getElementById('signature-canvas');

    // Close button in the signature modal header
    var sigClose   = document.getElementById('signature-close');

    // Clear button that wipes the canvas so the user can redraw
    var sigClear   = document.getElementById('signature-clear');

    // Save button that converts canvas to base64 and stores it in the hidden input
    var sigSave    = document.getElementById('signature-save');

    // 2D drawing context used to render the signature strokes on canvas
    var ctx        = sigCanvas ? sigCanvas.getContext('2d') : null;

    // Boolean tracking whether the user is currently drawing on the canvas
    var drawing    = false;

    // Last X and Y coordinates of the drawing pointer for smooth line rendering
    var lx = 0, ly = 0;

    // Set canvas resolution to match display DPI for crisp signature rendering
    function setupCanvas() {
        if (!sigCanvas || !ctx) return;
        var ratio = window.devicePixelRatio || 1;
        var rect  = sigCanvas.getBoundingClientRect();
        sigCanvas.width  = rect.width  * ratio;
        sigCanvas.height = rect.height * ratio;
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.scale(ratio, ratio);
        ctx.lineJoin = 'round'; ctx.lineCap = 'round';
        ctx.lineWidth = 2.5; ctx.strokeStyle = '#111827';
    }

    // Open the signature modal and set up the canvas for drawing
    function openModal() {
        if (!sigModal) return;
        sigModal.classList.add('open');
        setTimeout(function () {
            setupCanvas();
            // If a signature was already saved, redraw it on the canvas
            if (sigInput && sigInput.value) {
                var img = new Image();
                img.onload = function () {
                    var r = window.devicePixelRatio || 1;
                    ctx.drawImage(img, 0, 0, sigCanvas.width / r, sigCanvas.height / r);
                };
                img.src = sigInput.value;
            }
        }, 50);
    }

    // Close the signature modal by removing the open class
    function closeModal() { if (sigModal) sigModal.classList.remove('open'); }

    // Extract X and Y coordinates from both mouse and touch events
    function getXY(e) {
        var rect = sigCanvas.getBoundingClientRect();
        var src  = e.touches ? e.touches[0] : e;
        return { x: src.clientX - rect.left, y: src.clientY - rect.top };
    }

    if (sigCanvas && ctx) {
        // Mouse events for desktop signature drawing on canvas
        sigCanvas.addEventListener('mousedown',  function(e){ drawing=true; var p=getXY(e); lx=p.x; ly=p.y; });
        sigCanvas.addEventListener('mousemove',  function(e){ if(!drawing) return; var p=getXY(e); ctx.beginPath(); ctx.moveTo(lx,ly); ctx.lineTo(p.x,p.y); ctx.stroke(); lx=p.x; ly=p.y; });
        sigCanvas.addEventListener('mouseup',    function(){ drawing=false; });
        sigCanvas.addEventListener('mouseleave', function(){ drawing=false; });

        // Touch events for mobile signature drawing with preventDefault to stop scroll
        sigCanvas.addEventListener('touchstart', function(e){ e.preventDefault(); drawing=true; var p=getXY(e); lx=p.x; ly=p.y; }, {passive:false});
        sigCanvas.addEventListener('touchmove',  function(e){ e.preventDefault(); if(!drawing) return; var p=getXY(e); ctx.beginPath(); ctx.moveTo(lx,ly); ctx.lineTo(p.x,p.y); ctx.stroke(); lx=p.x; ly=p.y; }, {passive:false});
        sigCanvas.addEventListener('touchend',   function(){ drawing=false; });
    }

    // Clicking the signature display area opens the drawing modal
    if (sigDisplay) sigDisplay.addEventListener('click', openModal);

    // Close button and clicking outside modal content both close the modal
    if (sigClose)   sigClose.addEventListener('click',   closeModal);
    if (sigModal)   sigModal.addEventListener('click',   function(e){ if(e.target===sigModal) closeModal(); });

    // Clear button erases all strokes from the canvas so user can redraw
    if (sigClear)   sigClear.addEventListener('click',   function(){ if (ctx) ctx.clearRect(0, 0, sigCanvas.width, sigCanvas.height); });

    // Save button exports canvas to PNG, stores in hidden input, shows preview
    if (sigSave)    sigSave.addEventListener('click', function () {
        if (!sigCanvas) return;

        // Compare current canvas to blank canvas to check if anything was drawn
        var blank = document.createElement('canvas');
        blank.width  = sigCanvas.width;
        blank.height = sigCanvas.height;
        if (sigCanvas.toDataURL() === blank.toDataURL()) {
            alert('Please draw your signature before saving.');
            return;
        }

        // Export canvas as base64 PNG and store in the hidden form input
        var dataURL = sigCanvas.toDataURL('image/png');
        if (sigInput)   sigInput.value = dataURL;

        // Replace the clickable placeholder with a preview of the saved signature
        if (sigDisplay) {
            sigDisplay.classList.add('has-value');
            sigDisplay.innerHTML = '<img src="' + dataURL + '" style="max-height:48px;max-width:100%;" alt="Signature" />';
        }
        closeModal();
    });

    // Toggle license key input between password hidden and text visible modes
    window.toggleKey = function (idx) {
        var input = document.getElementById('lk_'+idx);
        var icon  = document.getElementById('lk_icon_'+idx);
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.className = 'fa fa-eye-slash';
        } else {
            input.type = 'password';
            if (icon) icon.className = 'fa fa-eye';
        }
    };

    /* ═══════════════════════════════════════════════════════════════
       SECTION 9: INIT — attach add buttons and create first default cards
    ═══════════════════════════════════════════════════════════════ */

    // Attach click listener to Add Asset Item button in step 2
    document.getElementById('btn-add-asset').addEventListener('click',    addAssetCard);

    // Attach click listener to Raise New Ticket button in step 4
    document.getElementById('btn-add-ticket').addEventListener('click',   addTicketCard);

    // Attach click listener to Add Software Asset button in step 5
    document.getElementById('btn-add-software').addEventListener('click', addSoftwareCard);

    // Pre-populate each section with one default card on initial page load
    addAssetCard();
    addTicketCard();
    addSoftwareCard();

    // Initialize the step display state for step 1 (home) on page load
    updateStepDisplay();

});
</script>



<?php init_tail(); ?>