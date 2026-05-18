<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="asset-form-container">
                            <div class="asset-form-header">
                                <div class="asset-form-title-section">
                                    <h1 class="asset-form-title">Add Asset</h1>
                                    <p class="asset-form-description">Fill in the asset details to add it to your inventory.</p>
                                </div>
                            </div>

                            <?php echo form_open(admin_url('hr/assets_management/add'), array('class' => 'asset-form-body')); ?>
                    
                            <div class="asset-form-panel">
                                <div class="asset-header-section">
                                    <div class="asset-summary">
                                        <span class="summary-label">Total Asset Value :- ₹<span id="total_value">0.00</span></span>
                                    </div>
                                    <button type="button" id="add_asset_btn" class="btn btn-default">Add Asset Item</button>
                                </div>

                                <div id="asset_items_container" class="asset-items">
                                    <!-- Asset items will be added here -->
                                </div>

                                <div class="form-actions">
                                    <a href="<?php echo admin_url('hr/assets_management'); ?>" class="btn-cancel">Cancel</a>
                                    <button type="submit" class="btn-submit">Submit Assets</button>
                                </div>
                            </div>

                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .asset-form-container {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .asset-form-header {
        padding: 32px 32px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .asset-form-title-section {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .asset-form-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.02em;
    }

    .asset-form-description {
        margin: 0;
        font-size: 0.95rem;
        color: #6b7280;
        line-height: 1.5;
    }

    .asset-form-body {
        display: block;
    }

    .asset-form-panel {
        padding: 32px;
        display: grid;
        gap: 24px;
    }

    .asset-header-section {
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

    .asset-summary {
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

    .asset-summary .summary-label {
        font-size: 0.8rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    .asset-items {
        display: grid;
        gap: 18px;
    }

    .asset-item {
        border: 1px solid #d1d5db;
        border-radius: 16px;
        padding: 24px;
        background: #ffffff;
        position: relative;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        transition: box-shadow 0.2s, border-color 0.2s;
    }

    .asset-item:hover {
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        border-color: #b0bec5;
    }

    .asset-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .asset-item-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111827;
    }

    .asset-remove-btn {
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

    .asset-remove-btn:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    .asset-item-row {
        display: grid;
        gap: 16px;
    }

    @media (min-width: 720px) {
        .asset-item-row {
            grid-template-columns: 1fr 1fr;
        }
    }

    .asset-item-row.wide {
        grid-template-columns: 1fr;
    }

    .asset-item-row .form-group.wide {
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
    let assetIndex = 0;
    const container = document.getElementById('asset_items_container');
    const addBtn = document.getElementById('add_asset_btn');
    const totalValue = document.getElementById('total_value');

    function addAssetItem() {
        const itemIndex = assetIndex++;
        const assetItem = createAssetItem(itemIndex);
        container.appendChild(assetItem);
        updateTotal();
    }

    function createAssetItem(index) {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'asset-item';
        itemDiv.setAttribute('data-index', index);

        itemDiv.innerHTML = `
            <div class="asset-item-header">
                <div class="asset-item-title">Asset Item ${index + 1}</div>
                <button type="button" class="asset-remove-btn" onclick="removeAssetItem(${index})">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="asset-item-row">
                <div class="form-group">
                    <label class="form-label">Asset Name</label>
                    <input type="text" name="asset_name[${index}]" class="form-control" placeholder="Enter asset name" required />
                </div>
                <div class="form-group">
                    <label class="form-label">Asset Code</label>
                    <input type="text" name="asset_code[${index}]" class="form-control" placeholder="Enter asset code" required />
                </div>
            </div>
            <div class="asset-item-row">
                <div class="form-group">
                    <label class="form-label">Asset Category</label>
                    <input type="text" name="asset_category[${index}]" class="form-control" placeholder="e.g. Computers, Furniture" required />
                </div>
                <div class="form-group">
                    <label class="form-label">Purchase Date</label>
                    <input type="date" name="purchase_date[${index}]" class="form-control date-input" required />
                </div>
            </div>
            <div class="asset-item-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status[${index}]" class="form-control" required>
                        <option value="">Select Status</option>
                        <option value="available">Available</option>
                        <option value="assigned">Assigned</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Asset Value (₹)</label>
                    <div class="amount-input-wrapper">
                        <span class="amount-prefix">₹</span>
                        <input type="number" name="value[${index}]" class="form-control amount-input" min="0" step="0.01" placeholder="0.00" onchange="updateTotal()" required />
                    </div>
                </div>
            </div>
            <div class="asset-item-row wide">
                <div class="form-group wide">
                    <label class="form-label">Description</label>
                    <textarea name="description[${index}]" class="form-control description-textarea" placeholder="Describe the asset details"></textarea>
                </div>
            </div>
        `;

        return itemDiv;
    }

    window.removeAssetItem = function(index) {
        const item = document.querySelector(`.asset-item[data-index="${index}"]`);
        if (item) {
            item.remove();
            updateTotal();
        }
    };

    window.updateTotal = function() {
        const inputs = document.querySelectorAll('.asset-item .amount-input');
        let total = 0;
        inputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        totalValue.textContent = total.toFixed(2);
    };

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Add first asset item by default
        addAssetItem();
        
        // Attach button click handler
        if (addBtn) {
            addBtn.addEventListener('click', function(e) {
                e.preventDefault();
                addAssetItem();
            });
        }
    });
</script>

<?php init_tail(); ?>