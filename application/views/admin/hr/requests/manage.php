<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
$calendar_summary = array_merge([
    'present'  => 0,
    'absent'   => 0,
    'half_day' => 0,
    'week_off' => 0,
    'upcoming' => 0,
    'leave'    => 0,
], isset($calendar_data['summary']) ? $calendar_data['summary'] : []);

$today      = date('Y-m-d');
$prev_month = date('Y-m', strtotime($current_month . ' -1 month'));
$next_month = date('Y-m', strtotime($current_month . ' +1 month'));
?>

<div id="wrapper">
      <div class="content">
            <div class="panel_s">
                  <div class="panel-body">

                        <!-- Page Header -->
                        <div class="page-actions mb-4">
                              <div>
                                    <h4 class="page-heading">HR / Leave Requests</h4>
                                    <p class="page-subtitle">Track leave requests, presence, and attendance status at a glance.</p>
                              </div>
                        </div>

                        <!-- Main Layout -->
                        <div class="req-layout">

                              <!-- LEFT: Cards -->
                              <div class="req-left">

                                    <!-- Card 1: Leave Request -->
                                    <div class="req-card req-card-leave" id="card-leave" onclick="toggleCard('leave')">
                                          <div class="req-card-inner">
                                                <div class="req-card-icon rc-green">
                                                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="3" y="4" width="18" height="18" rx="2" />
                                                            <line x1="16" y1="2" x2="16" y2="6" />
                                                            <line x1="8" y1="2" x2="8" y2="6" />
                                                            <line x1="3" y1="10" x2="21" y2="10" />
                                                            <line x1="12" y1="14" x2="12" y2="18" />
                                                            <line x1="10" y1="16" x2="14" y2="16" />
                                                      </svg>
                                                </div>
                                                <div class="req-card-text">
                                                      <h5>Leave Request</h5>
                                                      <p>Apply for leave and submit for approval</p>
                                                </div>
                                                <span class="req-card-chevron" id="chev-leave">&#8250;</span>
                                          </div>
                                          <!-- Dropdown Options -->
                                          <div class="req-options" id="opts-leave">
                                                <div class="req-option" onclick="openForm(event,'leave','Leave')">
                                                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                      Leave
                                                </div>
                                                <div class="req-option" onclick="openForm(event,'leave','Regularisation')">
                                                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                      Regularisation
                                                </div>
                                                <div class="req-option" onclick="openForm(event,'leave','Work From Home')">
                                                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                                      Work From Home
                                                </div>
                                                <div class="req-option" onclick="openForm(event,'leave','On Duty')">
                                                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                                      On Duty
                                                </div>
                                                <div class="req-option" onclick="openForm(event,'leave','Comp Off')">
                                                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                                                      Comp Off
                                                </div>
                                                <div class="req-option" onclick="openForm(event,'leave','Short Leave')">
                                                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                      Short Leave
                                                </div>
                                          </div>
                                    </div>

                              </div><!-- /req-left -->

                              <!-- RIGHT: Panel -->
                              <div class="req-right" id="req-right">

                                    <!-- Empty State with Summary Cards -->
                                    <div class="req-right-empty" id="req-empty">

                                          <!-- Leave Balance Panel -->
                                          <div class="summary-header">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                      <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                                </svg>
                                                Leave Balance
                                          </div>

                                          <div class="summary-grid" id="leave-balance-grid">
                                                <div class="summary-box">
                                                      <p class="summary-hint">Loading leave balance...</p>
                                                </div>
                                          </div>

                                          <p class="summary-hint">Select an option from the left to raise a request</p>

                                    </div><!-- /req-right-empty -->

                                    <!-- Dynamic Form Area -->
                                    <div id="req-form-area" style="display:none;">
                                          <div class="rform-header">
                                                <div>
                                                      <span class="rform-badge" id="rform-badge">Leave</span>
                                                      <h5 class="rform-title" id="rform-title">Leave Request</h5>
                                                      <p class="rform-sub" id="rform-sub">Fill in the details and submit your request</p>
                                                </div>
                                                <button class="rform-close" onclick="closeForm()">&#10005;</button>
                                          </div>

                                          <!-- Dynamic Form Content -->
                                          <div id="dynamic-form-content"></div>

                                    </div><!-- /req-form-area -->

                              </div><!-- /req-right -->

                        </div><!-- /req-layout -->

                  </div>
            </div>
      </div>
</div>

<style>
      * {
            box-sizing: border-box;
      }

      /* ── Page Header ── */
      .page-actions {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding: 0 4px;
      }

      .page-heading {
            margin: 0 0 4px;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
      }

      .page-subtitle {
            margin: 0;
            color: #6b7280;
            font-size: 0.875rem;
      }

      /* ── Layout ── */
      .req-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 16px;
            align-items: flex-start;
      }

      @media (max-width: 860px) {
            .req-layout {
                  grid-template-columns: 1fr;
            }
      }

      /* ── Left Cards ── */
      .req-left {
            display: flex;
            flex-direction: column;
            gap: 10px;
      }

      .req-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: border-color 0.18s, box-shadow 0.18s;
      }

      .req-card:hover {
            border-color: #b0bec5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
      }

      .req-card-leave {
            border-left: 3px solid #1D9E75;
      }

      .req-card-att {
            border-left: 3px solid #378ADD;
      }

      .req-card-onboarding {
            border-left: 3px solid #0F766E;
      }

      .req-card-assets {
            border-left: 3px solid #F59E0B;
      }

      .req-card-offboarding {
            border-left: 3px solid #DC2626;
      }

      .req-card-inner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
      }

      .req-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
      }

      .rc-green  { background: #E1F5EE; color: #0F6E56; }
      .rc-blue   { background: #E6F1FB; color: #185FA5; }
      .rc-teal   { background: #D1FAE5; color: #0F766E; }
      .rc-yellow { background: #FEF3C7; color: #B45309; }
      .rc-red    { background: #FEE2E2; color: #B91C1C; }
      .rc-orange { background: #FEF3C7; color: #B45309; }
      .rc-purple { background: #F3E8FF; color: #6D28D9; }

      .req-card-report  { border-left: 3px solid #8B5CF6; }
      .req-card-expense { border-left: 3px solid #F59E0B; }

      .req-card-text { flex: 1; }

      .req-card-text h5 {
            margin: 0 0 2px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1f2937;
      }

      .req-card-text p {
            margin: 0;
            font-size: 0.775rem;
            color: #6b7280;
            line-height: 1.4;
      }

      .req-card-chevron {
            font-size: 1.3rem;
            color: #9ca3af;
            transition: transform 0.22s;
            line-height: 1;
      }

      .req-card.open .req-card-chevron {
            transform: rotate(90deg);
      }

      /* ── Options Dropdown ── */
      .req-options {
            flex-direction: column;
            border-top: 1px solid #f0f2f5;
            padding: 4px 0;
            background: #f8f9fb;
      }

      #opts-leave {
            display: flex;
      }

      .req-options.show {
            display: flex;
      }

      .req-option {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 18px;
            font-size: 0.825rem;
            color: #4b5563;
            cursor: pointer;
            transition: background 0.13s, color 0.13s;
            border-left: 3px solid transparent;
      }

      .req-option svg {
            flex-shrink: 0;
            color: #9ca3af;
            transition: color 0.13s;
      }

      .req-option:hover {
            background: #EBF4FF;
            color: #185FA5;
            border-left-color: #378ADD;
      }

      .req-option:hover svg { color: #378ADD; }

      .req-option.active {
            background: #EBF4FF;
            color: #185FA5;
            border-left-color: #378ADD;
            font-weight: 600;
      }

      .req-option.active svg { color: #378ADD; }

      /* ── Right Panel ── */
      .req-right {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            min-height: 400px;
            overflow: hidden;
      }

      /* ── Empty State ── */
      .req-right-empty {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start;
            padding: 20px;
            gap: 0;
      }

      /* ── Summary Header ── */
      .summary-header {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f2f5;
      }

      .summary-header svg { color: #9ca3af; }

      /* ── Summary Grid ── */
      .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            width: 100%;
      }

      /* ── Old Summary Box (kept for fallback) ── */
      .summary-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            padding: 18px 10px 16px;
            border: 1px solid transparent;
            gap: 3px;
            min-height: 100px;
            transition: transform 0.15s, box-shadow 0.15s;
            cursor: default;
      }

      /* ── NEW: Leave Balance Cards ── */
      .slb-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 14px 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: transform 0.15s, box-shadow 0.15s;
            cursor: default;
      }

      .slb-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
      }

      .slb-top {
            display: flex;
            align-items: flex-start;
            gap: 8px;
      }

      .slb-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
      }

      .slb-name {
            flex: 1;
            font-size: 0.82rem;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.3;
            min-width: 0;
      }

      .slb-code {
            font-size: 0.68rem;
            font-weight: 600;
            border-radius: 5px;
            padding: 2px 7px;
            white-space: nowrap;
            flex-shrink: 0;
      }

      .slb-counts {
            display: flex;
            align-items: center;
            gap: 10px;
      }

      .slb-stat {
            display: flex;
            flex-direction: column;
            gap: 2px;
      }

      .slb-val {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1;
      }

      .slb-alloc {
            color: #9ca3af;
            font-size: 1rem;
            font-weight: 500;
      }

      .slb-lbl {
            font-size: 0.68rem;
            color: #9ca3af;
            font-weight: 500;
      }

      .slb-sep {
            width: 1px;
            height: 28px;
            background: #e5e7eb;
            flex-shrink: 0;
      }

      .slb-bar-track {
            height: 4px;
            background: #f0f2f5;
            border-radius: 99px;
            overflow: hidden;
      }

      .slb-bar-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.5s ease;
      }

      /* ── Summary Hint ── */
      .summary-hint {
            text-align: center;
            font-size: 0.78rem;
            color: #c8d0e0;
            margin: 16px 0 0;
      }

      /* ── Form Header ── */
      .rform-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 18px 22px 14px;
            border-bottom: 1px solid #f0f2f5;
            background: #ffffff;
      }

      .rform-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 99px;
            background: #EBF4FF;
            color: #185FA5;
            font-size: 0.72rem;
            font-weight: 600;
            margin-bottom: 5px;
            letter-spacing: 0.01em;
      }

      .rform-title {
            margin: 0 0 2px;
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
      }

      .rform-sub {
            margin: 0;
            font-size: 0.775rem;
            color: #6b7280;
      }

      .rform-close {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            font-size: 0.9rem;
            cursor: pointer;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
            flex-shrink: 0;
      }

      .rform-close:hover {
            background: #e5e7eb;
            color: #374151;
      }

      /* ── Form Body ── */
      .rform-body { padding: 18px 22px 22px; }

      .fg2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
      }

      @media (max-width: 580px) {
            .fg2 { grid-template-columns: 1fr; }
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

      .form-group small {
            font-weight: 400;
            color: #9ca3af;
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

      .form-control-card {
            min-height: 52px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #d1d5db;
            box-shadow: none;
      }

      .form-control-card:focus {
            border-color: #378ADD;
            box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
      }

      .segmented-control {
            display: flex;
            gap: 10px;
            background: #eef2f7;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            padding: 6px;
      }

      .segmented-control .seg-btn {
            flex: 1;
            min-height: 46px;
            border: none;
            border-radius: 12px;
            background: transparent;
            color: #374151;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s, color 0.18s;
      }

      .segmented-control .seg-btn.active {
            background: #ffffff;
            color: #0f172a;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
      }

      .segmented-control .seg-btn:hover {
            background: rgba(255, 255, 255, 0.8);
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

      /* ── Form Actions ── */
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

      .btn-submit:hover { background: #0C447C; }

      /* ── Report / Badge ── */
      .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
      }

      .report-table th,
      .report-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
            color: #344054;
      }

      .report-table th {
            background: #f8fafc;
            font-weight: 700;
            color: #0f172a;
      }

      .report-table tbody tr:hover { background: #f8f9fb; }

      .report-empty {
            margin-top: 18px;
            padding: 18px;
            border-radius: 14px;
            background: #f8fafc;
            color: #475569;
            font-size: 0.92rem;
      }

      .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
      }

      .badge-success { background: #D1FAE5; color: #065F46; }
      .badge-warning { background: #FEF3C7; color: #92400E; }
      .badge-danger  { background: #FEE2E2; color: #991B1B; }
</style>

<script>
      var base_url       = '<?php echo base_url(); ?>';
      var laravelApiBase = base_url + 'mobile/api/v1/';
      var laravelWebBase = base_url + 'mobile/';
      var ciStaffEmail   = <?php echo json_encode(isset($current_user->email) ? $current_user->email : ''); ?>;
      var openCardId     = null;
      var activeOption   = null;

      /* ── Leave colour + icon map ── */
      var leaveColorMap = {
            'CSL': { bg: '#E1F5EE', color: '#0F6E56', bar: '#1D9E75',  icon: '&#128338;' },
            'MEL': { bg: '#FBE7F3', color: '#993556', bar: '#D4537E',  icon: '&#10084;'  },
            'CPL': { bg: '#EEEDFE', color: '#534AB7', bar: '#7F77DD',  icon: '&#8635;'   },
            'LOP': { bg: '#FCEBEB', color: '#A32D2D', bar: '#E24B4A',  icon: '&#8722;'   },
            'ADL': { bg: '#E6F1FB', color: '#185FA5', bar: '#378ADD',  icon: '&#128737;' },
            'WFH': { bg: '#FEF3C7', color: '#854F0B', bar: '#EF9F27',  icon: '&#127968;' },
            'PL' : { bg: '#EAF3DE', color: '#3B6D11', bar: '#639922',  icon: '&#9749;'   },
      };
      var defaultLeaveColor = { bg: '#F1EFE8', color: '#5F5E5A', bar: '#888780', icon: '&#128197;' };

      /* ── Helpers ── */
      function escapeHtml(text) {
            return String(text || '').replace(/[&<>"']/g, function (m) {
                  return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m];
            });
      }

      /* ── Render leave balance cards ── */
      function renderLeaveBalance(data) {
            var container = document.getElementById('leave-balance-grid');
            if (!container) return;

            if (!Array.isArray(data) || data.length === 0) {
                  container.innerHTML = '<div class="summary-box"><p class="summary-hint">No leave balance data available.</p></div>';
                  return;
            }

            var html = '';
            data.forEach(function (item) {
                  var remaining = parseFloat(item.remaining) || 0;
                  var allocated = parseFloat(item.allocated) || 0;
                  var pct       = allocated > 0 ? Math.min(100, Math.round((remaining / allocated) * 100)) : 0;
                  var c         = leaveColorMap[item.code] || defaultLeaveColor;

                  html +=
                        '<div class="slb-card">' +
                              '<div class="slb-top">' +
                                    '<div class="slb-icon" style="background:' + c.bg + '; color:' + c.color + ';">' + c.icon + '</div>' +
                                    '<div class="slb-name">' + escapeHtml(item.name) + '</div>' +
                                    '<span class="slb-code" style="background:' + c.bg + '; color:' + c.color + ';">' + escapeHtml(item.code) + '</span>' +
                              '</div>' +
                              '<div class="slb-counts">' +
                                    '<div class="slb-stat">' +
                                          '<span class="slb-val">' + remaining.toFixed(0) + '</span>' +
                                          '<span class="slb-lbl">Remaining</span>' +
                                    '</div>' +
                                    '<div class="slb-sep"></div>' +
                                    '<div class="slb-stat">' +
                                          '<span class="slb-val slb-alloc">' + allocated.toFixed(0) + '</span>' +
                                          '<span class="slb-lbl">Allocated</span>' +
                                    '</div>' +
                              '</div>' +
                              '<div class="slb-bar-track"><div class="slb-bar-fill" style="width:' + pct + '%; background:' + c.bar + ';"></div></div>' +
                        '</div>';
            });

            container.innerHTML = html;
      }

      /* ── Load leave balance from API ── */
      function loadLeaveBalance() {
            var container = document.getElementById('leave-balance-grid');
            if (!container) return;

            if (!ciStaffEmail) {
                  container.innerHTML = '<div class="summary-box"><p class="summary-hint">Staff email not found.</p></div>';
                  return;
            }

            container.innerHTML = '<div class="summary-box"><p class="summary-hint">Loading leave balance...</p></div>';

            fetch(laravelWebBase + 'leave/ci-balance/' + encodeURIComponent(ciStaffEmail), {
                  headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (response) { return response.json(); })
            .then(function (result) {
                  if (result && result.status && Array.isArray(result.data)) {
                        renderLeaveBalance(result.data);
                  } else {
                        container.innerHTML = '<p class="summary-hint">' + escapeHtml(result.message || 'Unable to load leave balance.') + '</p>';
                  }
            })
            .catch(function (error) {
                  console.error('Leave balance error:', error);
                  container.innerHTML = '<p class="summary-hint">Unable to load leave balance.</p>';
            });
      }

      /* ── Card toggle ── */
      function toggleCard(id) {
            var card   = document.getElementById('card-' + id);
            var opts   = document.getElementById('opts-' + id);
            var isOpen = card.classList.contains('open');

            ['leave','att','report','expense','assets','onboarding','offboarding'].forEach(function (c) {
                  var cardEl = document.getElementById('card-' + c);
                  var optsEl = document.getElementById('opts-' + c);
                  if (cardEl) cardEl.classList.remove('open');
                  if (optsEl) optsEl.classList.remove('show');
            });

            if (!isOpen) {
                  card.classList.add('open');
                  opts.classList.add('show');
                  openCardId = id;
            } else {
                  openCardId = null;
            }
      }

      /* ── Open form ── */
      function openForm(e, cardId, formName) {
            e.stopPropagation();

            document.querySelectorAll('.req-option').forEach(function (o) { o.classList.remove('active'); });
            e.currentTarget.classList.add('active');
            activeOption = formName;

            if (cardId === 'report') {
                  fetch('<?php echo base_url("admin/hr/requests/load_report_forms"); ?>')
                  .then(function(r){ return r.text(); })
                  .then(function(data){
                        document.getElementById('dynamic-form-content').innerHTML = data;
                        document.getElementById('rform-badge').textContent = formName;
                        document.getElementById('rform-title').textContent = formName + ' Report';
                        document.getElementById('rform-sub').textContent   = 'View leave and attendance report data for ' + formName.toLowerCase() + '.';
                        document.getElementById('req-empty').style.display    = 'none';
                        document.getElementById('req-form-area').style.display = 'block';
                        if (typeof reportModule !== 'undefined' && typeof reportModule.loadReport === 'function') {
                              reportModule.loadReport(formName);
                        }
                  })
                  .catch(function(err){ console.error(err); alert('Error loading report. Please try again.'); });
                  return;
            }

            if (cardId === 'expense') {
                  fetch('<?php echo base_url("admin/hr/requests/load_expense_form"); ?>')
                  .then(function(r){ return r.text(); })
                  .then(function(data){
                        document.getElementById('dynamic-form-content').innerHTML = data;
                        document.getElementById('rform-badge').textContent = formName;
                        document.getElementById('rform-title').textContent = formName;
                        document.getElementById('rform-sub').textContent   = 'Submit an expense request with receipt details.';
                        document.getElementById('req-empty').style.display    = 'none';
                        document.getElementById('req-form-area').style.display = 'block';
                        if (typeof expenseModule !== 'undefined' && typeof expenseModule.loadExpenseUI === 'function') {
                              expenseModule.loadExpenseUI();
                        }
                  })
                  .catch(function(err){ console.error(err); alert('Error loading expense screen. Please try again.'); });
                  return;
            }

            if (cardId === 'assets') {
                  fetch('<?php echo base_url("admin/hr/requests/load_assets_forms"); ?>')
                  .then(function(r){ return r.text(); })
                  .then(function(data){
                        document.getElementById('dynamic-form-content').innerHTML = data;
                        var target = document.getElementById('form-' + formName);
                        if (target) target.style.display = 'block';
                        document.getElementById('rform-badge').textContent = formName;
                        document.getElementById('rform-title').textContent = formName;
                        document.getElementById('rform-sub').textContent   = 'Request equipment, software, or other assets.';
                        document.getElementById('req-empty').style.display    = 'none';
                        document.getElementById('req-form-area').style.display = 'block';
                        if (typeof assetModule !== 'undefined' && typeof assetModule.loadAssetUI === 'function') {
                              assetModule.loadAssetUI();
                        }
                  })
                  .catch(function(err){ console.error(err); alert('Error loading assets screen. Please try again.'); });
                  return;
            }

            if (cardId === 'onboarding') {
                  fetch('<?php echo base_url("admin/hr/requests/load_onboarding_forms"); ?>')
                  .then(function(r){ return r.text(); })
                  .then(function(data){
                        document.getElementById('dynamic-form-content').innerHTML = data;
                        var target = document.getElementById('form-' + formName);
                        if (target) target.style.display = 'block';
                        document.getElementById('rform-badge').textContent = formName;
                        document.getElementById('rform-title').textContent = formName;
                        document.getElementById('rform-sub').textContent   = 'Start onboarding by submitting CTC approval details.';
                        document.getElementById('req-empty').style.display    = 'none';
                        document.getElementById('req-form-area').style.display = 'block';
                  })
                  .catch(function(err){ console.error(err); alert('Error loading onboarding screen. Please try again.'); });
                  return;
            }

            if (cardId === 'offboarding') {
                  fetch('<?php echo base_url("admin/hr/requests/load_offboarding_forms"); ?>')
                  .then(function(r){ return r.text(); })
                  .then(function(data){
                        document.getElementById('dynamic-form-content').innerHTML = data;
                        var target = document.getElementById('form-' + formName);
                        if (target) target.style.display = 'block';
                        document.getElementById('rform-badge').textContent = formName;
                        document.getElementById('rform-title').textContent = formName;
                        document.getElementById('rform-sub').textContent   = 'Submit resignation details for offboarding.';
                        document.getElementById('req-empty').style.display    = 'none';
                        document.getElementById('req-form-area').style.display = 'block';
                  })
                  .catch(function(err){ console.error(err); alert('Error loading offboarding screen. Please try again.'); });
                  return;
            }

            /* leave / att */
            var moduleUrl = '';
            if (cardId === 'leave') {
                  moduleUrl = '<?php echo base_url("admin/hr/requests/load_leave_forms"); ?>';
            } else if (cardId === 'att') {
                  moduleUrl = '<?php echo base_url("admin/hr/requests/load_attendance_forms"); ?>';
            }

            if (moduleUrl) {
                  fetch(moduleUrl)
                  .then(function(r){ return r.text(); })
                  .then(function(data){
                        document.getElementById('dynamic-form-content').innerHTML = data;

                        document.querySelectorAll('.rform-body').forEach(function (f) { f.style.display = 'none'; });

                        var target = document.getElementById('form-' + formName);
                        if (target) target.style.display = 'block';

                        document.getElementById('rform-badge').textContent = formName;
                        document.getElementById('rform-title').textContent = formName + ' Request';
                        document.getElementById('rform-sub').textContent   = 'Fill in the details and submit your ' + formName.toLowerCase() + ' request.';

                        document.getElementById('req-empty').style.display    = 'none';
                        document.getElementById('req-form-area').style.display = 'block';

                        if (typeof updateShortLeaveSummary === 'function') {
                              updateShortLeaveSummary();
                        }
                  })
                  .catch(function(err){ console.error(err); alert('Error loading form. Please try again.'); });
            }
      }

      function setWorkFromHomeDuration(button, value) {
            var container = button.closest('.segmented-control');
            if (!container) return;
            container.querySelectorAll('.seg-btn').forEach(function (btn) { btn.classList.remove('active'); });
            button.classList.add('active');
            var targetId = container.getAttribute('data-target');
            if (targetId) {
                  var hidden = document.getElementById(targetId);
                  if (hidden) hidden.value = value;
            }
      }

      function closeForm() {
            document.getElementById('req-empty').style.display      = 'flex';
            document.getElementById('req-form-area').style.display  = 'none';
            document.getElementById('dynamic-form-content').innerHTML = '';
            document.querySelectorAll('.req-option').forEach(function (o) { o.classList.remove('active'); });
            activeOption = null;
      }

      /* ── Init ── */
      if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadLeaveBalance);
      } else {
            loadLeaveBalance();
      }
</script>

<script src="<?php echo base_url('assets/js/leave_module.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/report_module.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/expense_module.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/asset_module.js'); ?>"></script>
<?php init_tail(); ?>