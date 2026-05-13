<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="form-Asset Request" class="rform-body" style="display:none;">
      <div class="asset-request-panel">
            <div class="asset-header-row">
                  <button id="add-asset-item-btn" type="button" class="btn btn-default">Add Asset Item</button>
                  <div class="asset-total-label">Total Items: <strong id="asset_total_items">0</strong></div>
            </div>
            <div id="asset_items_container" class="asset-items"></div>
      </div>
      <div class="rform-actions">
            <button class="btn-cancel" onclick="closeForm()">Cancel</button>
            <button class="btn-submit" onclick="alert('Asset request submitted');">Submit Request</button>
      </div>
</div>

<style>
      .asset-request-panel {
            display: grid;
            gap: 18px;
      }

      .asset-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
      }

      .asset-total-label {
            font-size: 0.95rem;
            color: #111827;
            font-weight: 600;
      }

      .asset-items {
            display: grid;
            gap: 16px;
      }

      .asset-item {
            border: 1px solid #d1d5db;
            border-radius: 18px;
            background: #ffffff;
            padding: 18px;
      }

      .asset-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
      }

      .asset-item-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
      }

      .asset-remove-btn {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: none;
            background: #fee2e2;
            color: #b91c1c;
            font-size: 1.1rem;
            cursor: pointer;
      }

      .asset-item-row {
            display: grid;
            gap: 16px;
      }

      @media (min-width: 720px) {
            .asset-item-row {
                  grid-template-columns: repeat(2, 1fr);
            }
      }

      @media (min-width: 1024px) {
            .asset-item-row {
                  grid-template-columns: repeat(3, 1fr);
            }
      }

      .asset-item-row.wide {
            grid-template-columns: 1fr;
      }

      .asset-receipt-section {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
      }

      .asset-receipt-name {
            color: #6b7280;
            font-size: 0.85rem;
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
