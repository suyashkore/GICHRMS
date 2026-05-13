/**
 * Expense Module for HR Requests
 * Provides UI behavior for the Expense request card.
 */
var expenseModule = (function() {
      'use strict';

      var nextExpenseIndex = 0;

      function formatAmount(value) {
            var amount = parseFloat(value);
            return isNaN(amount) ? 0 : amount;
      }

      function addExpenseRow() {
            var index = nextExpenseIndex++;
            var container = document.getElementById('expense_items_container');
            if (!container) return;

            var row = document.createElement('div');
            row.className = 'expense-item';
            row.dataset.index = index;
            row.innerHTML = '' +
                  '<div class="expense-item-header">' +
                        '<div class="expense-item-title">Expense Item ' + (index + 1) + '</div>' +
                        '<button type="button" class="expense-remove-btn" data-index="' + index + '">×</button>' +
                  '</div>' +
                  '<div class="expense-item-row">' +
                        '<div class="form-group">' +
                              '<label>Title</label>' +
                              '<input type="text" name="expense_title[' + index + ']" class="form-control expense-item-title-input" placeholder="Expense title" />' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Category</label>' +
                              '<select name="expense_category[' + index + ']" class="form-control expense-item-category">' +
                                    '<option value="Travel">Travel</option>' +
                                    '<option value="Food">Food</option>' +
                                    '<option value="Office Supplies">Office Supplies</option>' +
                                    '<option value="Other">Other</option>' +
                              '</select>' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Date</label>' +
                              '<input type="date" name="expense_date[' + index + ']" class="form-control expense-item-date" value="' + new Date().toISOString().slice(0, 10) + '" />' +
                        '</div>' +
                        '<div class="form-group">' +
                              '<label>Amount</label>' +
                              '<div class="expense-amount-input">' +
                                    '<span class="expense-prefix">₹</span>' +
                                    '<input type="number" min="0" step="0.01" name="expense_amount[' + index + ']" class="form-control expense-item-amount" placeholder="0.00" value="0.00" />' +
                              '</div>' +
                        '</div>' +
                        '<div class="form-group wide">' +
                              '<label>Notes</label>' +
                              '<textarea name="expense_notes[' + index + ']" class="form-control expense-item-notes" placeholder="Add expense notes or comments"></textarea>' +
                        '</div>' +
                        '<div class="form-group expense-receipt-section">' +
                              '<button type="button" class="btn btn-default expense-upload-btn" data-index="' + index + '">Upload Receipt</button>' +
                              '<span id="expense_receipt_name_' + index + '" class="expense-receipt-name">No file chosen</span>' +
                              '<input type="file" name="expense_receipt[' + index + ']" class="expense-receipt-input" data-index="' + index + '" accept="image/*,.pdf" style="display:none;" />' +
                        '</div>' +
                  '</div>';

            container.appendChild(row);
            attachItemEvents(row);
            updateTotalAmount();
      }

      function attachItemEvents(row) {
            var index = row.dataset.index;

            var amountInput = row.querySelector('.expense-item-amount');
            if (amountInput) {
                  amountInput.addEventListener('input', function() {
                        updateTotalAmount();
                  });
            }

            var removeButton = row.querySelector('.expense-remove-btn');
            if (removeButton) {
                  removeButton.addEventListener('click', function() {
                        removeExpenseRow(index);
                  });
            }

            var uploadBtn = row.querySelector('.expense-upload-btn');
            var receiptInput = row.querySelector('.expense-receipt-input');
            if (uploadBtn && receiptInput) {
                  uploadBtn.addEventListener('click', function() {
                        receiptInput.click();
                  });

                  receiptInput.addEventListener('change', function() {
                        updateReceiptName(index, this.files[0] ? this.files[0].name : 'No file chosen');
                  });
            }
      }

      function removeExpenseRow(index) {
            var row = document.querySelector('.expense-item[data-index="' + index + '"]');
            if (row) {
                  row.remove();
            }
            if (document.querySelectorAll('.expense-item').length === 0) {
                  addExpenseRow();
            }
            updateTotalAmount();
      }

      function updateReceiptName(index, name) {
            var label = document.getElementById('expense_receipt_name_' + index);
            if (label) {
                  label.textContent = name || 'No file chosen';
            }
      }

      function updateTotalAmount() {
            var total = 0;
            document.querySelectorAll('.expense-item-amount').forEach(function(input) {
                  var value = formatAmount(input.value);
                  if (!isNaN(value)) {
                        total += value;
                  }
            });
            var totalLabel = document.getElementById('expense_total_amount');
            if (totalLabel) {
                  totalLabel.textContent = total.toFixed(2);
            }
      }

      function loadExpenseUI() {
            nextExpenseIndex = 0;
            var container = document.getElementById('expense_items_container');
            if (!container) return;
            container.innerHTML = '';
            addExpenseRow();

            var addButton = document.getElementById('add-expense-item-btn');
            if (addButton) {
                  addButton.addEventListener('click', addExpenseRow);
            }
      }

      return {
            loadExpenseUI: loadExpenseUI,
            addExpenseRow: addExpenseRow,
            removeExpenseRow: removeExpenseRow,
            updateTotalAmount: updateTotalAmount
      };
})();
