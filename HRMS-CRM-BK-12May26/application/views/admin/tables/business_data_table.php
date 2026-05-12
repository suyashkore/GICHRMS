<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];

/*if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}*/

$aColumns = array_merge($aColumns, [
    'id',
    'name',
    'mobile_no',
    'email',
    'status',
    'updated_data',
    'created_date',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'business_data';

$join = [];
$additionalSelect = [];






$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   // $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    //$row[] = $aRow['item_code'];
    $itemcodeOutput = '<a href="#" data-toggle="modal" data-target="#hsn_modal" data-id="' . $aRow['id'] . '">' . $aRow['item_code'] . '</a>';
    $itemcodeOutput .= '<div class="row-options">';

    if (has_permission('items', '', 'edit')) {
        $itemcodeOutput .= '<a href="#" data-toggle="modal" data-target="#hsn_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
    }

    if (has_permission('items', '', 'delete')) {
        $itemcodeOutput .= ' | <a href="' . admin_url('business_data/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $itemcodeOutput .= '</div>';
    $row[] = $aRow['name'];
    //$descriptionOutput = '';
    //$descriptionOutput = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . $aRow['description'] . '</a>';
    

    //$row[] = $descriptionOutput;
    $row[] = $aRow['mobile_no'];
    $row[] = $aRow['email'];
   
    $row[] = $aRow['created_date'];
     $row[] = $aRow['updated_data'];

    //$row[] = $aRow['long_description'];

    /*$row[] = app_format_money($aRow['rate'], get_base_currency());

    $aRow['taxrate_1'] = $aRow['taxrate_1'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_1'] . '" data-taxid="' . $aRow['tax_id_1'] . '">' . app_format_number($aRow['taxrate_1']) . '%' . '</span>';

    $aRow['taxrate_2'] = $aRow['taxrate_2'] ?? 0;
    $row[]             = '<span data-toggle="tooltip" title="' . $aRow['taxname_2'] . '" data-taxid="' . $aRow['tax_id_2'] . '">' . app_format_number($aRow['taxrate_2']) . '%' . '</span>';*/
    //$row[]             = $aRow['capacity'];

    //$row[] = $aRow['start_date'];
    if($aRow['status'] == 1){
        $status = "Y";
    }else{
        $status = "N";
    }
    $row[] = $status;
    

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }
    $action = '<a href="#" data-toggle="modal" data-target="#business_modal" data-id="' . $aRow['id'] . '"><i class="fa fa-pencil"></i></a>';
    $action .= ' | <a href="' . admin_url('business_data/delete/' . $aRow['id']) . '" class="text-danger _delete"><i class="fa fa-trash"></i></a>';
    $row[] = $action;

    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
