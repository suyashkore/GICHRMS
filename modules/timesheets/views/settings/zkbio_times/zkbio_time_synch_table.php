<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'name',
	'punch_time',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'timesheets_kteco_sync_states';

$where = [];
$join= [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

$output = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
	$row = [];
	$row[] = $aRow['id'];
	$row[] = $aRow['name'];
	$row[] = _dt($aRow['punch_time']);

	$output['aaData'][] = $row;
}

