<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
      <div class="content">
            <div class="panel_s">
                  <div class="panel-body">
                        <div class="page-actions mb-4">
                              <div>
                                    <h4 class="page-heading">HR / Reports</h4>
                                    <p class="page-subtitle">View leave and attendance reports in one dedicated workspace.</p>
                              </div>
                        </div>

                        <div class="report-layout">
                              <div class="report-left">
                                    <div class="report-card">
                                          <div class="report-card-inner">
                                                <div class="report-card-icon rc-purple">
                                                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M4 6h16M4 10h16M8 14h8M6 18h12"/>
                                                      </svg>
                                                </div>
                                                <div class="report-card-text">
                                                      <h5>Leave And Attendance Report</h5>
                                                      <p>Choose one of the HR report types below.</p>
                                                </div>
                                          </div>

                                          <div class="report-options" id="report-options">
                                                <div class="report-option active" onclick="openReport('Leave', event)">Leave</div>
                                                <div class="report-option" onclick="openReport('Regularisation', event)">Regularisation</div>
                                                <div class="report-option" onclick="openReport('Work From Home', event)">Work From Home</div>
                                                <div class="report-option" onclick="openReport('On Duty', event)">On Duty</div>
                                                <div class="report-option" onclick="openReport('Comp Off', event)">Comp Off</div>
                                                <div class="report-option" onclick="openReport('Short Leave', event)">Short Leave</div>
                                                <div class="report-option" onclick="openReport('Restricted Holiday', event)">Restricted Holiday</div>
                                          </div>
                                    </div>
                              </div>

                              <div class="report-right">
                                    <div class="report-right-empty" id="report-empty">
                                          <div class="empty-state-title">Select a report type</div>
                                          <p class="empty-state-text">Click a report type on the left to load leave and attendance report data.</p>
                                    </div>

                                    <div id="report-form-area" style="display:none;">
                                          <div class="rform-header">
                                                <div>
                                                      <span class="rform-badge" id="rform-badge">Leave</span>
                                                      <h5 class="rform-title" id="rform-title">Leave Report</h5>
                                                      <p class="rform-sub" id="rform-sub">View leave and attendance report data.</p>
                                                </div>
                                                <button class="rform-close" onclick="closeReport()">&#10005;</button>
                                          </div>
                                          <div class="report-controls">
                                                <div class="report-filters">
                                                      <input type="text" id="report-search" placeholder="Search employee name..." />
                                                </div>
                                                <div class="report-actions">
                                                      <button class="btn btn-sm btn-default" onclick="reportModule.exportToCSV(activeReportOption)">Export CSV</button>
                                                </div>
                                          </div>
                                          <div id="dynamic-report-content"></div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<style>
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

      .report-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 16px;
      }

      @media (max-width: 860px) {
            .report-layout {
                  grid-template-columns: 1fr;
            }
      }

      .report-left {
            display: flex;
            flex-direction: column;
            gap: 10px;
      }

      .report-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
      }

      .report-card-inner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
      }

      .report-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
      }

      .rc-purple {
            background: #F3E8FF;
            color: #6D28D9;
      }

      .report-card-text h5 {
            margin: 0 0 4px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
      }

      .report-card-text p {
            margin: 0;
            font-size: 0.825rem;
            color: #6b7280;
         }

      .report-options {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 12px 14px 16px;
            background: #f8fafc;
      }

      .report-option {
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: #ffffff;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 0.9rem;
      }

      .report-option:hover,
      .report-option.active {
            border-color: #c7d2fe;
            background: #eef2ff;
            color: #1d4ed8;
      }

      .report-right {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            min-height: 420px;
            overflow: hidden;
      }

      .report-right-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 28px;
            text-align: center;
            color: #475569;
      }

      .empty-state-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
      }

      .empty-state-text {
            margin: 0;
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
      }

      .report-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 18px 22px 8px;
            border-bottom: 1px solid #e5e7eb;
      }

      .report-filters {
            flex: 1;
      }

      #report-search {
            width: 100%;
            max-width: 280px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.15s, box-shadow 0.15s;
      }

      #report-search:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.18);
      }

      .report-actions {
            display: flex;
            gap: 8px;
      }

      .btn-default {
            border: 1px solid #d1d5db;
            background: #f8fafc;
            color: #344054;
      }

      .btn-default:hover {
            background: #eef2ff;
        }

      .rform-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 18px 22px 14px;
            border-bottom: 1px solid #e5e7eb;
            background: #ffffff;
      }

      .rform-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            background: #eef2ff;
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 5px;
      }

      .rform-title {
            margin: 0 0 4px;
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
      }

      .rform-sub {
            margin: 0;
            color: #64748b;
            font-size: 0.9rem;
      }

      .rform-close {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background: #f8fafc;
            color: #475569;
            cursor: pointer;
            font-size: 0.9rem;
      }

      .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0 24px;
      }

      .report-table th,
      .report-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 0.92rem;
            color: #334155;
      }

      .report-table th {
            background: #f8fafc;
            color: #0f172a;
            font-weight: 700;
      }

      .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
      }

      .badge-success { background: #DCFCE7; color: #166534; }
      .badge-warning { background: #FEF3C7; color: #92400E; }
      .badge-danger { background: #FEE2E2; color: #B91C1C; }
</style>

<script src="<?php echo base_url('assets/js/report_module.js'); ?>"></script>
<script>
      var activeReportOption = 'Leave';

      function setActiveOption(element) {
            document.querySelectorAll('.report-option').forEach(function (option) {
                  option.classList.remove('active');
            });
            if (element) {
                  element.classList.add('active');
            }
      }

      function openReport(reportName, event) {
            if (event) {
                  event.stopPropagation();
            }
            activeReportOption = reportName;
            setActiveOption(event ? event.currentTarget : null);
            document.getElementById('report-empty').style.display = 'none';
            document.getElementById('report-form-area').style.display = 'block';
            document.getElementById('rform-badge').textContent = reportName;
            document.getElementById('rform-title').textContent = reportName + ' Report';
            document.getElementById('rform-sub').textContent = 'View leave and attendance report data for ' + reportName.toLowerCase() + '.';

            if (typeof reportModule !== 'undefined' && typeof reportModule.loadReport === 'function') {
                  reportModule.loadReport(reportName);
            }

            var searchInput = document.getElementById('report-search');
            if (searchInput) {
                  searchInput.value = '';
            }
      }

      function closeReport() {
            document.getElementById('report-empty').style.display = 'flex';
            document.getElementById('report-form-area').style.display = 'none';
            document.getElementById('dynamic-report-content').innerHTML = '';
            activeReportOption = null;
            document.querySelectorAll('.report-option').forEach(function (option) {
                  option.classList.remove('active');
            });
      }

      document.addEventListener('DOMContentLoaded', function () {
            var firstOption = document.querySelector('.report-option.active');
            if (firstOption) {
                  firstOption.click();
            }

            var searchInput = document.getElementById('report-search');
            if (searchInput) {
                  searchInput.addEventListener('keyup', function () {
                        var searchTerm = this.value.toLowerCase();
                        var rows = document.querySelectorAll('#dynamic-report-content table tbody tr');
                        rows.forEach(function (row) {
                              var employeeName = row.querySelector('td:nth-child(2)');
                              if (!employeeName) return;
                              var text = employeeName.textContent.toLowerCase();
                              row.style.display = text.indexOf(searchTerm) !== -1 ? '' : 'none';
                        });
                  });
            }
      });
</script>

<?php init_tail(); ?>
