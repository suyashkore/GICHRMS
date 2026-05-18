<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="expense-form-container">
                            <div class="expense-form-header">
                                <div class="expense-form-title-section">
                                    <h1 class="expense-form-title">Expense Request</h1>
                                    <p class="expense-form-description">Fill in the details and submit your expense request.</p>
                                </div>
                            </div>

                            <?php echo form_open_multipart(admin_url('hr/expense_management/add'), array('class' => 'expense-form-body')); ?>
                    
                    <div class="expense-form-panel">
                        <div class="expense-header-section">
                            <div class="expense-summary">
                                <span class="summary-label">Total Amount :- ₹<span id="total_amount">0.00</span></span>
                            </div>
                            <button type="button" id="add_expense_btn" class="btn btn-default">Add Expense Item</button>
                        </div>

                        <div id="expense_items_container" class="expense-items">
                            <!-- Expense items will be added here -->
                        </div>

                        <div class="form-actions">
                            <a href="<?php echo admin_url('hr/expense_management'); ?>" class="btn-cancel">Cancel</a>
                            <button type="submit" class="btn-submit">Submit Request</button>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .expense-form-container {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .expense-form-header {
        padding: 32px 32px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .expense-form-title-section {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .expense-form-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.02em;
    }

    .expense-form-description {
        margin: 0;
        font-size: 0.95rem;
        color: #6b7280;
        line-height: 1.5;
    }

    .expense-form-body {
        display: block;
    }

    .expense-form-panel {
        padding: 32px;
        display: grid;
        gap: 24px;
    }

    .file-upload-wrapper {
        position: relative;
    }

    .file-input-hidden {
        position: absolute;
        opacity: 0;
        display: none !important;
        width: 0;
        height: 0;
        pointer-events: none;
        visibility: hidden;
    }

    .expense-header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .expense-summary {
        display: inline-flex;
        flex-direction: column;
        gap: 6px;
        padding: 16px 20px;
        border-radius: 14px;
        background: #ffffff;
        border: 2px solid #d1d5db;
        min-width: 200px;
        text-align: right;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
    }

    .expense-summary .summary-label {
        font-size: 0.8rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    .expense-summary .summary-value {
        color: #185FA5;
        font-size: 1.4rem;
        font-weight: 700;
    }

    .expense-items {
        display: grid;
        gap: 18px;
    }

    .expense-item {
        border: 1px solid #d1d5db;
        border-radius: 16px;
        padding: 24px;
        background: #ffffff;
        position: relative;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        transition: box-shadow 0.2s, border-color 0.2s;
    }

    .expense-item:hover {
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        border-color: #b0bec5;
    }

    .expense-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .expense-item-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111827;
    }

    .expense-remove-btn {
        border: none;
        background: #fee2e2;
        color: #991B1B;
        font-size: 1.1rem;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .expense-remove-btn:hover {
        background: #fecaca;
        transform: scale(1.05);
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
        gap: 8px;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .optional-badge {
        font-size: 0.75rem;
        color: #9ca3af;
        font-weight: 400;
    }

    .form-control {
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 0.95rem;
        color: #1f2937;
        background: #ffffff;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        min-height: 44px;
    }

    .form-control:focus {
        outline: none;
        border-color: #378ADD;
        box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
        background: #f8fafc;
    }

    .form-control:hover {
        border-color: #b0bec5;
    }

    .expense-type-select,
    .date-input {
        min-height: 44px;
        padding: 12px 14px;
    }

    .description-textarea {
        min-height: 100px;
        resize: vertical;
        padding: 12px 14px;
    }

    .amount-input-wrapper {
        display: flex;
        align-items: center;
        gap: 0;
        background: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 0 12px;
        height: 44px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .amount-input-wrapper:focus-within {
        border-color: #378ADD;
        box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
    }

    .amount-input-wrapper:hover {
        border-color: #b0bec5;
    }

    .amount-prefix {
        font-size: 0.95rem;
        font-weight: 700;
        color: #185FA5;
        display: inline-flex;
        align-items: center;
        height: 100%;
        padding: 0 8px;
        border-right: 1px solid #e5e7eb;
    }

    .amount-input {
        border: none;
        background: transparent;
        padding: 0 12px;
        height: 100%;
        font-size: 0.95rem;
        color: #1f2937;
        flex: 1;
        min-width: 0;
    }

    .amount-input:focus {
        outline: none;
        box-shadow: none;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 18px;
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #fafbfc;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        min-height: 48px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #374151;
    }

    .file-upload-label:hover {
        border-color: #378ADD;
        background: #eef4ff;
        color: #185FA5;
    }

    .file-upload-label i {
        font-size: 1.1rem;
        color: #185FA5;
    }

    .file-name {
        font-size: 0.9rem;
        color: #4b5563;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
        margin-top: 8px;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 10px 28px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
    }

    .btn-cancel:hover {
        background: #f3f4f6;
        border-color: #b0bec5;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 10px 32px;
        border-radius: 10px;
        border: none;
        background: #185FA5;
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    .btn-submit:hover {
        background: #0C447C;
    }

    .btn-submit:active {
        transform: scale(0.98);
    }

    .btn-default {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 10px 24px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
    }

    .btn-default:hover {
        background: #f3f4f6;
        border-color: #b0bec5;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let expenseIndex = 0;
        const container = document.getElementById('expense_items_container');
        const addBtn = document.getElementById('add_expense_btn');
        const totalAmount = document.getElementById('total_amount');

        // Add first expense item by default
        addExpenseItem();

        addBtn.addEventListener('click', addExpenseItem);

        function addExpenseItem() {
            const itemIndex = expenseIndex++;
            const expenseItem = createExpenseItem(itemIndex);
            container.appendChild(expenseItem);
            updateTotal();
        }

        function createExpenseItem(index) {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'expense-item';
            itemDiv.setAttribute('data-index', index);

            itemDiv.innerHTML = `
                <div class="expense-item-header">
                    <div class="expense-item-title">Expense Item ${index + 1}</div>
                    <button type="button" class="expense-remove-btn" onclick="removeExpenseItem(${index})">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="expense-item-row">
                    <div class="form-group">
                        <label class="form-label">Expense Type</label>
                        <select name="expense_name[${index}]" class="form-control expense-type-select" required>
                            <option value="">Select Expense Type</option>
                            <option value="Travel">Travel</option>
                            <option value="Food">Food</option>
                            <option value="Office Supplies">Office Supplies</option>
                            <option value="Accommodation">Accommodation</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Amount (₹)</label>
                        <div class="amount-input-wrapper">
                            <span class="amount-prefix">₹</span>
                            <input type="number" name="amount[${index}]" class="form-control amount-input" min="0" step="0.01" placeholder="0.00" onchange="updateTotal()" required />
                        </div>
                    </div>
                </div>
                <div class="expense-item-row">
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="date[${index}]" class="form-control date-input" required />
                    </div>
                </div>
                <div class="expense-item-row wide">
                    <div class="form-group wide">
                        <label class="form-label">Description</label>
                        <textarea name="description[${index}]" class="form-control description-textarea" placeholder="Describe the expense"></textarea>
                    </div>
                </div>
                <div class="expense-item-row wide">
                    <div class="form-group wide">
                        <label class="form-label">Bill / Receipt <span class="optional-badge">(optional)</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="receipt[${index}]" id="receipt_file_${index}" class="file-input-hidden" accept="image/*,.pdf" />
                            <label for="receipt_file_${index}" class="file-upload-label">
                                <i class="fa fa-cloud-upload"></i>
                                <span class="file-name" id="file-name-${index}">Choose File - No file chosen</span>
                            </label>
                        </div>
                    </div>
                </div>
            `;

            // Add file input change listener
            const fileInput = itemDiv.querySelector(`#receipt_file_${index}`);
            const fileName = itemDiv.querySelector(`#file-name-${index}`);
            
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileName.textContent = this.files[0].name;
                } else {
                    fileName.textContent = 'Choose File - No file chosen';
                }
            });

            return itemDiv;
        }

        window.removeExpenseItem = function(index) {
            const item = document.querySelector(`.expense-item[data-index="${index}"]`);
            if (item) {
                item.remove();
                updateTotal();
            }
        };

        function updateTotal() {
            let total = 0;
            const amountInputs = document.querySelectorAll('.amount-input');
            amountInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            totalAmount.textContent = total.toFixed(2);
        }
    });
</script>

<?php init_tail(); ?>