<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('hr/expense_management/add'); ?>" class="btn btn-info pull-left display-block">
                                <?php echo _l('add_expense'); ?>
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('expense_name'),
                            _l('amount'),
                            _l('date'),
                            _l('status'),
                            _l('options')
                        ), 'expense-management'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function(){
    initDataTable('.table-expense-management', window.location.href, [5], [5]);
});
</script>