<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'emp_code',
	'first_name',
	'last_name',
	'1',
	'punch_time',
	'punch_state_display',
	'terminal_sn',
	'synch_to_attendance',

];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'timesheets_kteco_attendance_logs';

$where = [];
$join= [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

$output = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
	$row = [];
	$row[] = $aRow['id'];
	$row[] = $aRow['emp_code'];
	$row[] = $aRow['first_name'];
	$row[] = $aRow['last_name'];
	$row[] = date('H:i:s',strtotime($aRow['punch_time']));
	$row[] = date('Y-m-d', strtotime($aRow['punch_time']));
	$row[] = $aRow['punch_state_display'];
	$row[] = $aRow['terminal_sn'];
	if($aRow['synch_to_attendance'] == 1){
		$row[] = '<span class="label label-success inline-block">'._l('yes').'</span>';	
	}else{
		$row[] = '';
	}

	$output['aaData'][] = $row;
}

