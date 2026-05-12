<?php

defined('BASEPATH') or exit('No direct script access allowed');
$staff = $this->ci->staff_model->get('', ['active' => 1]);

$aColumns = [
	'id',
	'zkbio_emp_code',
	'zkbio_first_name',
	'zkbio_last_name',
	'perfex_staff_id',

];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'timesheets_kteco_employees';

$where = [];
$join = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

$output = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
	$row = [];
	$row[] = $aRow['id'];
	$row[] = $aRow['zkbio_emp_code'];
	$row[] = $aRow['zkbio_first_name'];
	$row[] = $aRow['zkbio_last_name'];

	$assignees_data = '';
	if (staff_can('edit', 'timesheet_settings') && $aRow['perfex_staff_id'] == 0) {
		$assignees_data .= '<div class="simple-bootstrap-select tw-mb-2">
    <select data-width="100%" 
    data-task-id="' . $aRow['id'] . '" id="add_task_assignees"
    class="text-muted task-action-select selectpicker" name="table-select-assignees" data-live-search="true"
    title="' . _l('ts_no_mapping') . '"
    data-none-selected-text="' . _l('dropdown_non_selected_tex') . '">';

		$options = '';
		foreach ($staff as $assignee) {
			if (!($assignee['staffid'] == $aRow['perfex_staff_id'])) {
				$options .= '<option value="' . $assignee['staffid'] . '">' . $assignee['full_name'] . '</option>';
			}
		}
		$assignees_data .= $options;

		$assignees_data .= '</select>';
		$assignees_data .= '</div>';

	}

	$assignees_data .= '<div class="task_users_wrapper">';
	$_assignees = '';

	if ($aRow['perfex_staff_id'] > 0) {
		$_remove_assigne = '';
		if (staff_can('edit', 'timesheet_settings')) {
			$_remove_assigne = ' <a href="#" class="remove-task-user text-danger" onclick="remove_mapping_employee(' . $aRow['id'] . ',' . $aRow['id'] . ',' . $aRow['id'] . '); return false;"><i class="fa fa-remove"></i></a>';
		}
		$_assignees .= ' <div class="task-user"  data-toggle="tooltip" data-title="' . html_escape(get_staff_full_name($aRow['perfex_staff_id'])) . '">
<a href="' . admin_url('profile/' . $aRow['perfex_staff_id']) . '" target="_blank">' . staff_profile_image($aRow['perfex_staff_id'], [
				'staff-profile-image-small',
			]) . '</a> ' . $_remove_assigne . '</span>
</div> ' . get_staff_full_name($aRow['perfex_staff_id']) . '<br>';
	}

	$assignees_data .= $_assignees;
	$assignees_data .= '</div>';

	$row[] = $assignees_data;

	$row[] = $aRow['perfex_staff_id'];

	$output['aaData'][] = $row;
}

