/**
 * Asset Module for HR Requests
 * Allows multiple asset items to be added in the asset request form.
 */
var assetModule = (function() {
      'use strict';

      var nextAssetIndex = 0;

      function createAssetRow(index) {
            var row = document.createElement('div');
            row.className = 'asset-item';
            row.dataset.index = index;
            row.innerHTML = '' +
                  '<div class="asset-item-header">' +
                        '<div class="asset-item-title">Asset Item ' + (index + 1) + '</div>' +
                        '<button type="button" class="asset-remove-btn" data-index="' + index + '">×</button>' +
                  '</div>' +
                  '<div class="asset-item-row">' +
                        '<div class="form-group">' +
                              '<label>Asset Type</label>' +
                              '<select name="asset_type[' + index + ']" class="form-control">' +
                                    '<option value="">Select Asset</option>' +
                                    '<option>Laptop</option>' +
                                    '<option>Monitor</option>' +
                                    '<option>Keyboard / Mouse</option>' +
                                    '<option>Mobile Phone</option>' +
                                    '<option>Software License</option>' +
                                    '<option>Other</option>' +
                              '</select>' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Brand/Model</label>' +
                              '<input type="text" name="asset_brand_model[' + index + ']" class="form-control" placeholder="e.g. Dell Inspiron 15" />' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Serial No</label>' +
                              '<input type="text" name="asset_serial_no[' + index + ']" class="form-control" placeholder="Enter serial number" />' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Quantity</label>' +
                              '<input type="number" name="asset_quantity[' + index + ']" class="form-control" min="1" value="1" />' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Required By</label>' +
                              '<input type="date" name="asset_required_by[' + index + ']" class="form-control" value="' + new Date().toISOString().slice(0, 10) + '" />' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Warranty Date</label>' +
                              '<input type="date" name="asset_warranty_date[' + index + ']" class="form-control" value="' + new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toISOString().slice(0, 10) + '" />' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Status</label>' +
                              '<select name="asset_status[' + index + ']" class="form-control">' +
                                    '<option value="">Select Status</option>' +
                                    '<option>New</option>' +
                                    '<option>Used</option>' +
                                    '<option>Refurbished</option>' +
                              '</select>' +
                        '</div>' +
                        '<div class="form-group wide">' +
                              '<label>Reason</label>' +
                              '<textarea name="asset_reason[' + index + ']" class="form-control" rows="3" placeholder="Why do you need this asset?"></textarea>' +
                        '</div>' +
                        '<div class="form-group asset-receipt-section">' +
                              '<button type="button" class="btn btn-default asset-upload-btn" data-index="' + index + '">Upload Document</button>' +
                              '<span id="asset_receipt_name_' + index + '" class="asset-receipt-name">No file chosen</span>' +
                              '<input type="file" name="asset_document[' + index + ']" class="asset-receipt-input" data-index="' + index + '" accept="image/*,.pdf" style="display:none;" />' +
                        '</div>' +
                  '</div>';
            return row;
      }

      function addAssetRow() {
            var index = nextAssetIndex++;
            var container = document.getElementById('asset_items_container');
            if (!container) return;

            var row = createAssetRow(index);
            container.appendChild(row);
            attachItemEvents(row);
            updateTotalItems();
      }

      function attachItemEvents(row) {
            var index = row.dataset.index;

            var removeButton = row.querySelector('.asset-remove-btn');
            if (removeButton) {
                  removeButton.addEventListener('click', function() {
                        removeAssetRow(index);
                  });
            }

            var uploadBtn = row.querySelector('.asset-upload-btn');
            var receiptInput = row.querySelector('.asset-receipt-input');
            if (uploadBtn && receiptInput) {
                  uploadBtn.addEventListener('click', function() {
                        receiptInput.click();
                  });

                  receiptInput.addEventListener('change', function() {
                        var file = this.files[0];
                        updateReceiptName(index, file ? file.name : 'No file chosen');
                  });
            }
      }

      function removeAssetRow(index) {
            var row = document.querySelector('.asset-item[data-index="' + index + '"]');
            if (row) {
                  row.remove();
            }
            if (document.querySelectorAll('.asset-item').length === 0) {
                  addAssetRow();
            }
            updateTotalItems();
      }

      function updateReceiptName(index, name) {
            var label = document.getElementById('asset_receipt_name_' + index);
            if (label) {
                  label.textContent = name || 'No file chosen';
            }
      }

      function updateTotalItems() {
            var totalLabel = document.getElementById('asset_total_items');
            if (!totalLabel) return;
            totalLabel.textContent = document.querySelectorAll('.asset-item').length;
      }

      function loadAssetUI() {
            nextAssetIndex = 0;
            var container = document.getElementById('asset_items_container');
            if (!container) return;
            container.innerHTML = '';
            addAssetRow();

            var addButton = document.getElementById('add-asset-item-btn');
            if (addButton) {
                  addButton.addEventListener('click', addAssetRow);
            }
      }

      return {
            loadAssetUI: loadAssetUI,
            addAssetRow: addAssetRow,
            removeAssetRow: removeAssetRow,
            updateTotalItems: updateTotalItems
      };
})();
