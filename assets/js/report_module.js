/**
 * Leave And Attendance Report Module
 * Handles report rendering, filtering, and data display
 */

var reportModule = (function() {
      'use strict';

      // Sample data storage for reports
      var reportData = {
            'Leave': [
                  { date: '2026-05-12', employee: 'Amit Patil', type: 'Leave', status: 'Approved', remarks: 'No issues' },
                  { date: '2026-05-11', employee: 'Neha Joshi', type: 'Leave', status: 'Pending', remarks: 'Waiting for manager approval' },
                  { date: '2026-05-10', employee: 'Ravi Kumar', type: 'Leave', status: 'Approved', remarks: 'Approved on time' }
            ],
            'Regularisation': [
                  { date: '2026-05-12', employee: 'Priya Singh', type: 'Regularisation', status: 'Approved', remarks: 'Late arrival regularized' },
                  { date: '2026-05-11', employee: 'Vikram Desai', type: 'Regularisation', status: 'Pending', remarks: 'Under review' }
            ],
            'Work From Home': [
                  { date: '2026-05-12', employee: 'Anjali Sharma', type: 'Work From Home', status: 'Approved', remarks: 'WFH approved' },
                  { date: '2026-05-11', employee: 'Rohit Verma', type: 'Work From Home', status: 'Approved', remarks: 'WFH approved' }
            ],
            'On Duty': [
                  { date: '2026-05-12', employee: 'Sandeep Kumar', type: 'On Duty', status: 'Approved', remarks: 'Client visit' },
                  { date: '2026-05-10', employee: 'Meera Nair', type: 'On Duty', status: 'Rejected', remarks: 'Incomplete information' }
            ],
            'Comp Off': [
                  { date: '2026-05-12', employee: 'Arjun Patel', type: 'Comp Off', status: 'Approved', remarks: 'Weekend work compensated' }
            ],
            'Short Leave': [
                  { date: '2026-05-12', employee: 'Nitin Chopra', type: 'Short Leave', status: 'Approved', remarks: '1 hour leave taken' },
                  { date: '2026-05-11', employee: 'Divya Reddy', type: 'Short Leave', status: 'Approved', remarks: '30 mins leave taken' }
            ],
            'Restricted Holiday': [
                  { date: '2026-05-12', employee: 'Sanjay Rao', type: 'Restricted Holiday', status: 'Approved', remarks: 'Holiday request approved' }
            ]
      };

      /**
       * Get status badge HTML with appropriate color
       */
      function getStatusBadge(status) {
            var badgeClass = 'badge';
            if (status === 'Approved') {
                  badgeClass += ' badge-success';
            } else if (status === 'Pending') {
                  badgeClass += ' badge-warning';
            } else if (status === 'Rejected') {
                  badgeClass += ' badge-danger';
            }
            return '<span class="' + badgeClass + '">' + status + '</span>';
      }

      /**
       * Render report table HTML
       */
      function renderReportTable(reportName) {
            var data = reportData[reportName] || [];
            var tableHtml = '';

            tableHtml += '<div class="rform-body" style="display:block; padding:18px 22px 22px;">';
            tableHtml += '<p class="rform-sub">Showing the latest ' + reportName.toLowerCase() + ' entries.</p>';
            tableHtml += '<div class="table-responsive">';
            tableHtml += '<table class="report-table">';
            tableHtml += '<thead>';
            tableHtml += '<tr>';
            tableHtml += '<th>Date</th>';
            tableHtml += '<th>Employee</th>';
            tableHtml += '<th>Type</th>';
            tableHtml += '<th>Status</th>';
            tableHtml += '<th>Remarks</th>';
            tableHtml += '</tr>';
            tableHtml += '</thead>';
            tableHtml += '<tbody>';

            if (data.length === 0) {
                  tableHtml += '<tr><td colspan="5" class="text-center" style="padding: 20px; color: #999;">No records found for this report.</td></tr>';
            } else {
                  data.forEach(function(row) {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + row.date + '</td>';
                        tableHtml += '<td>' + row.employee + '</td>';
                        tableHtml += '<td>' + row.type + '</td>';
                        tableHtml += '<td>' + getStatusBadge(row.status) + '</td>';
                        tableHtml += '<td>' + row.remarks + '</td>';
                        tableHtml += '</tr>';
                  });
            }

            tableHtml += '</tbody>';
            tableHtml += '</table>';
            tableHtml += '</div>';
            tableHtml += '</div>';

            return tableHtml;
      }

      /**
       * Load and display report
       */
      function loadReport(reportName) {
            var formContent = document.getElementById('dynamic-form-content');
            if (!formContent) return;

            // Mark active option
            document.querySelectorAll('.req-option').forEach(function(o) {
                  o.classList.remove('active');
            });

            // Generate and inject report table
            var tableMarkup = renderReportTable(reportName);
            formContent.innerHTML = tableMarkup;

            // Update header
            document.getElementById('rform-badge').textContent = reportName;
            document.getElementById('rform-title').textContent = reportName + ' Report';
            document.getElementById('rform-sub').textContent = 'View leave and attendance report data for ' + reportName.toLowerCase() + '.';

            // Show form area
            document.getElementById('req-empty').style.display = 'none';
            document.getElementById('req-form-area').style.display = 'block';
      }

      /**
       * Add sample data (for demo purposes)
       */
      function addSampleData(reportName, dataRow) {
            if (!reportData[reportName]) {
                  reportData[reportName] = [];
            }
            reportData[reportName].push(dataRow);
      }

      /**
       * Get all data for a report type
       */
      function getReportData(reportName) {
            return reportData[reportName] || [];
      }

      /**
       * Export to CSV
       */
      function exportToCSV(reportName) {
            var data = reportData[reportName] || [];
            if (data.length === 0) {
                  alert('No data to export');
                  return;
            }

            var csv = 'Date,Employee,Type,Status,Remarks\n';
            data.forEach(function(row) {
                  csv += row.date + ',' + row.employee + ',' + row.type + ',' + row.status + ',' + row.remarks + '\n';
            });

            var blob = new Blob([csv], { type: 'text/csv' });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = reportName + '_' + new Date().getTime() + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
      }

      // Public API
      return {
            loadReport: loadReport,
            addSampleData: addSampleData,
            getReportData: getReportData,
            exportToCSV: exportToCSV,
            renderReportTable: renderReportTable
      };

})();
