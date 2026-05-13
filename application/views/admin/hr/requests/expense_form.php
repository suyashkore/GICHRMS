<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="rform-body" style="display:block; padding:18px 22px 22px;">
      <div class="expense-request-panel">
            <div class="expense-header-row">
                  <button id="add-expense-item-btn" type="button" class="btn btn-default">Add Expense Item</button>
                  <div class="expense-total-label">
                        Total Amount: <strong>₹<span id="expense_total_amount">0.00</span></strong>
                  </div>
            </div>

            <div id="expense_items_container" class="expense-items"></div>
      </div>

      <div class="form-group" style="margin-top: 18px;">
            <button type="button" class="btn btn-submit" onclick="alert('Expense request submitted');">Submit Expense</button>
      </div>
</div>

<style>
      .expense-request-panel {
            display: grid;
            gap: 18px;
      }

      .expense-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
      }

      .expense-total-label {
            font-size: 0.95rem;
            color: #111827;
            font-weight: 600;
      }

      .expense-items {
            display: grid;
            gap: 18px;
      }

      .expense-item {
            border: 1px solid #d1d5db;
            border-radius: 18px;
            padding: 18px;
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
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
      }

      .expense-remove-btn {
            border: none;
            background: #FEE2E2;
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

      .expense-form-fields .form-control,
      .expense-form-fields textarea,
      .expense-form-fields select {
            min-height: 48px;
            padding: 10px 12px;
      }

      .expense-receipt-section {
            display: flex;
            align-items: center;
            gap: 10px;
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
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #185FA5;
            font-weight: 700;
      }

      .btn-default {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 16px;
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
