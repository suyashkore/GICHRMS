<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>

	<div class="row">
		<div class="col-md-6">
			<h4><?php echo _l('ts_zkbio_time_integration'); ?></h4>
		</div>
	</div>
	<div class="clearfix"></div>
	<hr>

	<div class="row">
		<div class="col-md-6">
			<h4><?php echo _l('ts_employees'); ?></h4>
		</div>
		<div class="col-md-6">
			<a class="btn btn-success pull-right" href="#" onclick="zkbio_mapping_employee(); return false;"><i class="fa-solid fa-rotate"></i> <?php echo _l('ts_mapping_employees'); ?></a>
			<a class="btn btn-default  pull-right mright5" href="<?php echo admin_url('timesheets/setting?group=zkbio_time'); ?>"><?php echo _l('ts_back_attendance_transaction'); ?></a>
		</div>
		
	</div>
	<?php
	render_datatable(
		array(
			_l('id'),
			_l('zkbio_emp_code'),
			_l('zkbio_first_name'),
			_l('zkbio_last_name'),
			_l('perfex_staff_id'),
			
		),
		'zkbio_employee_table'
	);
	?>
	</body>

	</html>