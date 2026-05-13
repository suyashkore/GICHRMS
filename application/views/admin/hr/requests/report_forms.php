<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Leave And Attendance Report View -->
<!-- This file loads report data and markup -->

<div class="rform-body" style="padding: 18px 22px 22px; display: block;">
      <p class="rform-sub" id="report-subtitle">Select a report type to view data.</p>
      
      <div class="report-controls" style="margin-bottom: 16px; display: flex; gap: 10px; align-items: center; justify-content: space-between;">
            <div class="report-filters" style="flex: 1;">
                  <!-- Filter controls can be added here -->
                  <input type="text" id="report-search" placeholder="Search employee name..." style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; width: 100%; max-width: 250px; font-size: 0.85rem;">
            </div>
            <div class="report-actions" style="display: flex; gap: 8px;">
                  <button class="btn btn-sm btn-default" onclick="reportModule.exportToCSV(activeOption)" style="padding: 6px 14px; border-radius: 6px; border: 1px solid #d1d5db; background: #f3f4f6; cursor: pointer; font-size: 0.8rem; font-weight: 600;">
                        Export CSV
                  </button>
            </div>
      </div>

      <div class="table-responsive" id="report-table-container">
            <table class="report-table">
                  <thead>
                        <tr>
                              <th>Date</th>
                              <th>Employee</th>
                              <th>Type</th>
                              <th>Status</th>
                              <th>Remarks</th>
                        </tr>
                  </thead>
                  <tbody id="report-tbody">
                        <tr>
                              <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                                    Loading report data...
                              </td>
                        </tr>
                  </tbody>
            </table>
      </div>

</div>

<style>
      .report-controls {
            border-bottom: 1px solid #f0f2f5;
            padding-bottom: 12px;
      }

      #report-search {
            transition: border-color 0.15s, box-shadow 0.15s;
      }

      #report-search:focus {
            outline: none;
            border-color: #378ADD;
            box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
      }

      .btn-sm {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 6px;
      }

      .btn-default {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            color: #4b5563;
            cursor: pointer;
      }

      .btn-default:hover {
            background: #e5e7eb;
      }
</style>

<script>
      // This script initializes the report view with data from reportModule
      document.addEventListener('DOMContentLoaded', function() {
            // Initialize with empty state
            var tbody = document.getElementById('report-tbody');
            if (tbody && activeOption) {
                  var data = reportModule.getReportData(activeOption);
                  updateReportTable(data);
            }

            // Search functionality
            var searchInput = document.getElementById('report-search');
            if (searchInput) {
                  searchInput.addEventListener('keyup', function() {
                        filterReportTable(this.value);
                  });
            }
      });

      function updateReportTable(data) {
            var tbody = document.getElementById('report-tbody');
            if (!tbody) return;

            if (data.length === 0) {
                  tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #999;">No records found.</td></tr>';
                  return;
            }

            var html = '';
            data.forEach(function(row) {
                  var statusClass = 'badge';
                  if (row.status === 'Approved') {
                        statusClass += ' badge-success';
                  } else if (row.status === 'Pending') {
                        statusClass += ' badge-warning';
                  } else if (row.status === 'Rejected') {
                        statusClass += ' badge-danger';
                  }

                  html += '<tr data-employee="' + row.employee.toLowerCase() + '">';
                  html += '<td>' + row.date + '</td>';
                  html += '<td>' + row.employee + '</td>';
                  html += '<td>' + row.type + '</td>';
                  html += '<td><span class="' + statusClass + '">' + row.status + '</span></td>';
                  html += '<td>' + row.remarks + '</td>';
                  html += '</tr>';
            });

            tbody.innerHTML = html;
      }

      function filterReportTable(searchTerm) {
            var rows = document.querySelectorAll('#report-tbody tr');
            searchTerm = searchTerm.toLowerCase();

            rows.forEach(function(row) {
                  var employeeName = row.getAttribute('data-employee') || '';
                  if (employeeName.includes(searchTerm)) {
                        row.style.display = '';
                  } else {
                        row.style.display = 'none';
                  }
            });
      }
</script>
