<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<form action="<?php echo admin_url('hr/expense/add'); ?>" method="post" enctype="multipart/form-data">
<div class="rform-body" style="display:block; padding:18px 22px 22px;">
      <div class="expense-request-panel">
            <div class="expense-request-top">
                  <div class="expense-request-info">
                        <div class="expense-request-label">Expense Request</div>
                        <div class="expense-request-description">Submit an expense request with receipts, notes, and total tracking.</div>
                  </div>
                  <div class="expense-request-summary">
                        <span class="summary-label">Total amount</span>
                        <strong class="summary-value">₹<span id="expense_total_amount">0.00</span></strong>
                  </div>
            </div>

            <div class="expense-header-row">
                  <button id="add-expense-item-btn" type="button" class="btn btn-default">Add Expense Item</button>
            </div>

            <div id="expense_items_container" class="expense-items"></div>

            <div class="expense-actions">
                  <button type="submit" class="btn btn-submit">Submit Expense</button>
            </div>
      </div>
</div>
</form>

<style>
      .expense-request-panel {
            display: grid;
            gap: 22px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
      }

      .expense-request-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 18px;
            align-items: flex-start;
      }

      .expense-request-info {
            min-width: 200px;
      }

      .expense-request-label {
            margin-bottom: 8px;
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
      }

      .expense-request-description {
            margin: 0;
            color: #6b7280;
            font-size: 0.95rem;
            max-width: 520px;
            line-height: 1.5;
      }

      .expense-request-summary {
            display: inline-flex;
            flex-direction: column;
            gap: 6px;
            padding: 14px 18px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #d1d5db;
            min-width: 180px;
            text-align: right;
      }

      .expense-request-summary .summary-label {
            font-size: 0.85rem;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

      .expense-request-summary .summary-value {
            color: #111827;
            font-size: 1.2rem;
            font-weight: 700;
      }

      .expense-header-row {
            display: flex;
            justify-content: flex-start;
            align-items: center;
      }

      .expense-items {
            display: grid;
            gap: 18px;
      }

      .expense-item {
            border: 1px solid #d1d5db;
            border-radius: 20px;
            padding: 22px;
            background: #ffffff;
      }

      .expense-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
      }

      .expense-item-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
      }

      .expense-remove-btn {
            border: none;
            background: #fee2e2;
            color: #991B1B;
            font-size: 1rem;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            cursor: pointer;
      }

      .expense-item-row {
            display: grid;
            gap: 16px;
      }

      @media (min-width: 720px) {
            .expense-item-row {
                  grid-template-columns: 1fr 1fr;
            }
      }

      .expense-item-row.wide {
            grid-template-columns: 1fr;
      }

      .expense-item-row .form-group.wide {
            grid-column: 1 / -1;
      }

      .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 0;
      }

      .expense-item-row .form-group label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
      }

      .expense-item-row .form-control,
      .expense-item-row textarea,
      .expense-item-row select {
            min-height: 48px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            color: #1f2937;
      }

      .expense-item-row textarea {
            resize: vertical;
            min-height: 90px;
            padding-top: 12px;
      }

      .expense-item-row .form-control:focus,
      .expense-item-row textarea:focus,
      .expense-item-row select:focus {
            outline: none;
            border-color: #378ADD;
            box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
      }

      .expense-receipt-section {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
      }

      .expense-receipt-name {
            color: #6b7280;
            font-size: 0.85rem;
      }

      .expense-amount-input {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 52px;
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
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #eaf4ff;
            color: #185FA5;
            font-weight: 700;
            font-size: 0.95rem;
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
            height: 46px;
            font-size: 0.95rem;
      }

      .expense-amount-input .form-control:focus {
            border: none;
            box-shadow: none;
            outline: none;
      }

      .expense-actions {
            margin-top: 8px;
      }

      .expense-actions .btn-submit {
            width: 100%;
            padding: 14px 18px;
      }

      .btn-default {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 18px;
            border-radius: 14px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #1f2937;
            cursor: pointer;
      }

      .btn-default:hover {
            background: #f3f4f6;
      }
</style>
