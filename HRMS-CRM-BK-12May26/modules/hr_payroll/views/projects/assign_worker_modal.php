    <div class="modal fade z-index-none" id="assign_workerModal">
        <div class="modal-dialog setting-assign_worker-table modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo html_entity_decode($title) ?></h4>
                </div>
                <?php 
                $id = '';
                
                if(isset($assign_worker)){
                    $id = $assign_worker->id;
                }

                ?>
                <?php echo form_open_multipart(admin_url('hr_payroll/add_edit_assign_worker'), array('id' => 'add_edit_assign_worker', 'autocomplete'=>'off')); ?>
                <?php 

                $project_id = isset($_project_id) ? $_project_id : '';
                $is_bonus = '';
                $description = '';
                $date_assign = _d(date('Y-m-d'));
                $hours = '';
                $hourly_rate = '';
                $bonus_amount = '';
                $staff_ids = [];
                $hourly_rate_hide = '';
                $bonus_hide = ' hide';


                if(isset($assign_worker)){
                    $project_id = $assign_worker->project_id;
                    $is_bonus = $assign_worker->is_bonus;
                    $description = $assign_worker->description;
                    $date_assign = _d($assign_worker->date_assign);
                    $hours = $assign_worker->hours;
                    $hourly_rate = $assign_worker->hourly_rate;
                    $bonus_amount = $assign_worker->bonus_amount;
                    if($is_bonus == 1){
                        $hourly_rate_hide = ' hide';
                        $bonus_hide = '';
                    }else{
                        $hourly_rate_hide = '';
                        $bonus_hide = ' hide';
                    }
                    if(isset($assign_worker->project_for_staffs)){
                        foreach ($assign_worker->project_for_staffs as $key => $value) {
                            $staff_ids[] = $value['staff_id'];
                        }
                    }
                }

                ?>

                <input type="hidden" name="id" value="<?php echo html_entity_decode($id); ?>">
                <input type="hidden" name="project_id" value="<?php echo html_entity_decode($project_id); ?>">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="is_bonus" value="1" id="is_bonus" <?php if(isset($assign_worker) && $assign_worker->is_bonus == 1){ echo "checked";} ?>><label for="is_bonus"><?php echo _l('hrp_is_bonus'); ?></label>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?php echo render_select('staff_id[]', $staffs, ['staffid', ['firstname','lastname']], 'ps_employee_name', $staff_ids, ['multiple' => true, 'data-actions-box' => true], [], '', '', false); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo render_date_input('date_assign', 'hrp_date', $date_assign); ?>
                        </div>
                    </div>
                    <div class="row hourly_rate_hide <?php echo html_entity_decode($hourly_rate_hide); ?>">
                        <div class="col-md-6">
                            <?php echo render_input('hours', 'hrp_hours', $hours, 'number', ['min' => '0', 'step' => 'any']); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('hourly_rate', 'hrp_hourly_rate', $hourly_rate, 'number', ['min' => '0', 'step' => 'any']); ?>
                        </div>
                    </div>
                    <div class="row bonus_hide <?php echo html_entity_decode($bonus_hide); ?>">
                        <div class="col-md-12">
                            <?php echo render_input('bonus_amount', 'hrp_bonus_amount', $bonus_amount, 'number', ['min' => '0', 'step' => 'any']); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?php echo render_textarea('description', 'description_lable', $description); ?>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info assign_worker_submit_button"><?php echo _l('submit'); ?></button>
                </div>
            </div>

        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php require 'modules/hr_payroll/assets/js/projects/assign_worker_modal_js.php';  ?>
