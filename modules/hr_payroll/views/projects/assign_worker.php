<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="vueApp">
    <div class="panel_s">
        <div class="panel-body">
            <?php if(has_permission('projects', '','create') || has_permission('hrp_attendance', '','create') ){ ?>
                <div class="row mbot5">
                    <div class="col-md-12">
                        <a href="#" onclick="assign_worker_modal(0); return false;" class="btn btn-info pull-right display-block">
                            <?php echo _l('new'); ?>
                        </a>
                    </div>
                </div>
            <?php } ?>
            <div class="project_receipt">
                <?php echo form_hidden('_project_id', $project->id); ?>
                <?php render_datatable(array(
                    _l('id'),
                    _l('ps_employee_name'),
                    _l('hrp_hours'),
                    _l('hrp_hourly_rate'),
                    _l('hrp_amount'),
                    _l('hrp_date'),
                    _l('description_lable'),
                    _l('actions'),
                ),'table_assign_worker',['delivery_sm' => 'delivery_sm']); ?>
            </div>
        </div>
    </div>
</div>
<div id="modal_wrapper"></div>
