<?php

defined('BASEPATH') or exit('No direct script access allowed');
$baseCurrency = get_base_currency();

$aColumns = [
    'id',
    '(SELECT GROUP_CONCAT(firstname," ",lastname SEPARATOR "<br>") FROM ' . db_prefix() . 'hrp_project_for_staffs JOIN '.db_prefix().'staff on '.db_prefix().'staff.staffid = '.db_prefix().'hrp_project_for_staffs.staff_id  WHERE ' . db_prefix() . 'hrp_project_for_staffs.project_cost_id = ' . db_prefix() . 'hrp_project_costs.id ORDER by '.db_prefix().'hrp_project_for_staffs.id ASC) as employees_name',
    'hours',
    'hourly_rate',
    'bonus_amount',
    'date_assign',
    'description',
    '1',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'hrp_project_costs';

$where = [];
$join= [];
$join         = [
];

$status_filter = $this->ci->input->post('status_filter');
$_project_id = $this->ci->input->post('_project_id');
$from_date_filter = $this->ci->input->post('from_date_filter');
$to_date_filter = $this->ci->input->post('to_date_filter');

if($from_date_filter != '' &&  $to_date_filter != ''){
    $from_date_filter = to_sql_date($from_date_filter);
    $to_date_filter = to_sql_date($to_date_filter);

    array_push($where, ' AND ( (date_format('.db_prefix().'hrp_project_costs.start_date,"%Y-%m-%d")  >= "'.$from_date_filter.'" AND date_format('.db_prefix().'hrp_project_costs.end_date,"%Y-%m-%d")  <= "'.$to_date_filter.'") )');

}elseif( $from_date_filter != '' && $to_date_filter == ''){
    $from_date_filter = to_sql_date($from_date_filter);

    array_push($where, ' AND ( (date_format('.db_prefix().'hrp_project_costs.start_date,"%Y-%m-%d") >= "'.$from_date_filter.'" ))');

}elseif($from_date_filter == '' && $to_date_filter != ''){
    $to_date_filter = to_sql_date($to_date_filter);

    array_push($where, ' AND ( (date_format('.db_prefix().'hrp_project_costs.end_date,"%Y-%m-%d") <= "'.$to_date_filter.'" ))');
}
if($_project_id){
    array_push($where, ' AND ('.db_prefix().'hrp_project_costs.project_id = "'.$_project_id.'" )');
}


if (isset($status_filter)) {
    $where_status_filter = '';
    foreach ($status_filter as $status) {
        if ($status != '') {
            if ($where_status_filter == '') {
                $where_status_filter .= ' AND ('.db_prefix().'hrp_project_costs.status = "' . $status . '"';
            } else {
                $where_status_filter .= ' or '.db_prefix().'hrp_project_costs.status = "' . $status . '"';
            }
        }
    }
    if ($where_status_filter != '') {
        $where_status_filter .= ')';
        array_push($where, $where_status_filter);
    }
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['is_bonus']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $row[] = $aRow['id'];
    $options = $aRow['employees_name'];
    $row[] = $options;
    $row[] = $aRow['hours'];
    $row[] = $aRow['hourly_rate'];
    $amount = app_format_money((float)$aRow['hours'] * (float)$aRow['hourly_rate'] + (float)$aRow['bonus_amount'], '');
    if($aRow['is_bonus'] == 1){
        $amount  .= ' | <span class="label label-primary">'._l('hrp_is_bonus').'</span>';
    }
    $row[] = $amount;
    $row[] = _d($aRow['date_assign']);
    $row[] = $aRow['description'];
    $options = '';
    if(has_permission('projects', '','edit') || has_permission('hrp_attendance', '','edit')){
        $options .= '<a href="#" onclick="assign_worker_modal('.$aRow['id'].'); return false;"  class="btn btn-primary btn-icon" ><i class="fa-regular fa-pen-to-square"></i></a>';
    }

    if(has_permission('projects', '','delete') || has_permission('hrp_attendance', '','delete')){
        $options .= '<a href="#" onclick="delete_assign_worker('.$aRow['id'].'); return false;" class="btn btn-danger btn-icon "><i class="fa fa-trash"></i></a>';
    }
    $row[] = $options;

    $output['aaData'][] = $row;
}
