<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>

	<div class="row">
		<div class="col-md-6">
			<h4><?php echo _l('ts_zkbio_time_integration'); ?></h4>
		</div>
	</div>
	<div class="clearfix"></div>
	<hr>

	<?php if (is_admin()) { ?>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="checkbox checkbox-primary">
						<input onchange="setting_zkbio_time('kteco_integration'); return false" type="checkbox"
							id="kteco_integration" name="kteco_integration" <?php if (get_option('kteco_integration') == 1) {
								echo 'checked';
							} ?> value="kteco_integration">
						<label for="kteco_integration"><?php echo _l('ts_zkbio_time_integration'); ?>
							<a href="#" class="pull-right display-block input_method"></a>
						</label>
					</div>
				</div>
			</div>
		</div>
	
		<div class="row">
			<div class="col-md-12">
			<p class="text-danger bold"><?php echo _l('ts_superuser_account'); ?></p>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label><?php echo _l('ts_kteco_host_name'); ?></label>
					<div onchange="setting_zkbio_time('kteco_host_name'); return false" class="form-group"
						app-field-wrapper="kteco_host_name">
						<input type="text" id="kteco_host_name" name="kteco_host_name" class="form-control"
							value="<?php echo get_option('kteco_host_name'); ?>">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label><?php echo _l('ts_kteco_port_number'); ?></label>
					<div onchange="setting_zkbio_time('kteco_port_number'); return false" class="form-group"
						app-field-wrapper="kteco_port_number">
						<input type="number" id="kteco_port_number" name="kteco_port_number" class="form-control"
							value="<?php echo get_option('kteco_port_number'); ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label><?php echo _l('ts_kteco_username'); ?></label>
					<div onchange="setting_zkbio_time('kteco_username'); return false" class="form-group"
						app-field-wrapper="kteco_username">
						<input type="text" id="kteco_username" name="kteco_username" class="form-control"
							value="<?php echo get_option('kteco_username'); ?>">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label><?php echo _l('ts_kteco_password'); ?></label>
					<div onchange="setting_zkbio_time('kteco_password'); return false" class="form-group"
						app-field-wrapper="kteco_password">
						<input type="password" id="kteco_password" name="kteco_password" class="form-control"
							value="<?php echo get_option('kteco_password'); ?>">
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="checkbox checkbox-primary">
						<input onchange="setting_zkbio_time('kteco_create_employee'); return false" type="checkbox"
							id="kteco_create_employee" name="kteco_create_employee" <?php if (get_option('kteco_create_employee') == 1) {
								echo 'checked';
							} ?> value="kteco_create_employee">
						<label for="kteco_create_employee"><?php echo _l('ts_kteco_create_employee'); ?>
							<a href="#" class="pull-right display-block input_method"></a>
						</label>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="clearfix"></div>
	<hr>
	<div class="row">
		<div class="col-md-5">
			<h4><?php echo _l('ts_attendance_transaction'); ?></h4>
		</div>
		<div class="col-md-7">
			<a class="btn btn-primary  pull-right" href="<?php echo admin_url('timesheets/setting?group=zkbio_employee'); ?>"><?php echo _l('ts_ZKBio_employees'); ?></a>
			<a class="btn btn-success pull-right mright5" href="#" onclick="zkbio_mapping_timekeeping(); return false;"><i class="fa-solid fa-rotate"></i> <?php echo _l('ts_Synchronize_Timekeeping_from_ZKBio'); ?></a>
			<a class="btn btn-success pull-right mright5" href="#" onclick="cal_timesheet_hours(); return false;"><i class="fa-solid fa-rotate"></i> <?php echo _l('ts_cal_timesheet_hours'); ?></a>
		</div>
		
	</div>
	<?php
	render_datatable(
		array(
			_l('id'),
			_l('ts_emp_code'),
			_l('first_name'),
			_l('last_name'),
			_l('ts_punch_time'),
			_l('ts_punch_date'),
			_l('ts_punch_state_display'),
			_l('ts_terminal_sn'),
			_l('ts_Synch_to_Attendance'),
			
		),
		'zkbio_time_table'
	);
	?>

	<div class="clearfix"></div>
	<hr>
	<div class="row">
		<div class="col-md-5">
			<h4><?php echo _l('ts_history_Synchronize_ZKBio'); ?></h4>
		</div>
	</div>
	<?php
	render_datatable(
		array(
			_l('id'),
			_l('name'),
			_l('ts_datecreated'),
		),
		'zkbio_time_synch_table'
	);
	?>

	</body>

	<div class="modal" id="cal_timesheet_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog popup-with modal-dialog-with">
		<?php echo form_open_multipart(admin_url('timesheets/cal_timesheet_hours'), array('id' => 'cal_timesheet')); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<span class="edit-title"><?php echo _l('ts_cal_timesheet_hours'); ?></span>
						<span class="add-title"><?php echo _l('ts_cal_timesheet_hours'); ?></span>
					</h4>
				</div>
	
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
								<?php echo render_input('timesheet_month', 'month', date('Y-m'), 'month'); ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
					<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
		<!-- box loading -->
		<div id="box-loading"></div>
	</div>

	</html>